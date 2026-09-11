<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\FundDeposit;
use App\Models\FundDisbursal;
use App\Models\User;
use App\Services\FtpStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EntryController extends Controller
{
    /**
     * Standard expense categories.
     */
    public const CATEGORIES = [
        'Office & Supplies' => 'Office & Supplies',
        'Travel & Transport' => 'Travel & Transport',
        'Food & Refreshments' => 'Food & Refreshments',
        'IT & Software' => 'IT & Software',
        'Printing & Stationery' => 'Printing & Stationery',
        'Utility & Bills' => 'Utility & Bills',
        'Event & Meeting' => 'Event & Meeting',
        'Miscellaneous' => 'Miscellaneous',
    ];

    /**
     * Standard payment methods.
     */
    public const PAYMENT_METHODS = [
        'Cash' => 'Cash',
        'Bank Transfer' => 'Bank Transfer',
        'bKash' => 'bKash',
        'Nagad' => 'Nagad',
        'Cheque' => 'Cheque',
        'Other' => 'Other',
    ];

    /**
     * Display the comprehensive entry form, user ledgers, and transactions.
     */
    public function index(Request $request): View
    {
        $selectedUserId = $request->input('user_id');
        $activeTab = $request->input('tab', $request->input('type', 'ledgers'));

        // Central Treasury Metrics
        $totalFundRaised = (float) FundDeposit::sum('amount');
        $totalDisbursed = (float) FundDisbursal::sum('amount');
        $totalExpenses = (float) Expense::sum('amount');
        $treasuryBalance = $totalFundRaised - $totalDisbursed;
        $netFundBalance = $totalFundRaised - $totalExpenses;

        // User Settlement Summaries
        $users = User::orderBy('name')->get();
        $userLedgers = [];

        foreach ($users as $user) {
            $allocated = (float) FundDisbursal::where('user_id', $user->id)->sum('amount');
            $spent = (float) Expense::where('user_id', $user->id)->sum('amount');
            $balance = $allocated - $spent;

            $status = 'none';
            if ($spent > $allocated) {
                $status = 'reimburse'; // Institute owes user
            } elseif ($allocated > $spent) {
                $status = 'holding'; // User is holding institute cash
            } elseif ($allocated > 0 || $spent > 0) {
                $status = 'settled';
            }

            $givenToOthers = (float) FundDisbursal::where('given_by_user_id', $user->id)->where('user_id', '!=', $user->id)->sum('amount');
            $givenCount = FundDisbursal::where('given_by_user_id', $user->id)->where('user_id', '!=', $user->id)->count();

            $userLedgers[] = [
                'user' => $user,
                'allocated' => $allocated,
                'spent' => $spent,
                'balance' => $balance,
                'due_reimbursement' => max(0, $spent - $allocated),
                'unspent_fund' => max(0, $allocated - $spent),
                'given_to_others' => $givenToOthers,
                'given_count' => $givenCount,
                'status' => $status,
                'expense_count' => Expense::where('user_id', $user->id)->count(),
                'disbursal_count' => FundDisbursal::where('user_id', $user->id)->count(),
            ];
        }

        // Recent deposits
        $depositsQuery = FundDeposit::with(['user', 'creator'])->latest('deposit_date');
        if ($selectedUserId) {
            $depositsQuery->where('user_id', $selectedUserId);
        }
        $deposits = $depositsQuery->paginate(10, ['*'], 'deposits_page');

        // Recent disbursals
        $disbursalsQuery = FundDisbursal::with(['user', 'givenBy', 'approver'])->latest('disbursal_date');
        if ($selectedUserId) {
            $disbursalsQuery->where(function ($q) use ($selectedUserId) {
                $q->where('user_id', $selectedUserId)
                  ->orWhere('given_by_user_id', $selectedUserId);
            });
        }
        $disbursals = $disbursalsQuery->paginate(10, ['*'], 'disbursals_page');

        // Recent expenses
        $expensesQuery = Expense::with(['user', 'creator'])->latest('expense_date');
        if ($selectedUserId) {
            $expensesQuery->where('user_id', $selectedUserId);
        }
        $expenses = $expensesQuery->paginate(10, ['*'], 'expenses_page');

        $categories = self::CATEGORIES;
        $paymentMethods = self::PAYMENT_METHODS;

        return view('entries.index', compact(
            'totalFundRaised',
            'totalDisbursed',
            'totalExpenses',
            'treasuryBalance',
            'netFundBalance',
            'users',
            'userLedgers',
            'deposits',
            'disbursals',
            'expenses',
            'categories',
            'paymentMethods',
            'selectedUserId',
            'activeTab'
        ));
    }

    /**
     * Record a new fund contribution / income.
     */
    public function storeDeposit(Request $request, FtpStorageService $ftp): RedirectResponse
    {
        $validated = $request->validate([
            'contributor_name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'deposit_date' => ['required', 'date'],
            'payment_method' => ['required', 'string'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $docPath = null;
        $docUrl = null;
        $docFilename = null;

        if ($request->hasFile('document')) {
            $upload = $ftp->upload($request->file('document'), 'deposits');
            $docPath = $upload['path'];
            $docUrl = $upload['url'];
            $docFilename = $upload['filename'];
        }

        FundDeposit::create([
            'contributor_name' => $validated['contributor_name'],
            'user_id' => $validated['user_id'] ?? null,
            'amount' => $validated['amount'],
            'deposit_date' => $validated['deposit_date'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'description' => $validated['description'] ?? null,
            'document_path' => $docPath,
            'document_url' => $docUrl,
            'document_filename' => $docFilename,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('entries.index', ['tab' => 'deposits'])
            ->with('status', 'Fund deposit of ৳' . number_format($validated['amount'], 2) . ' recorded successfully.');
    }

    /**
     * Disburse funds from treasury to a person.
     */
    public function storeDisbursal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'given_by_user_id' => ['nullable', 'exists:users,id'],
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'disbursal_date' => ['required', 'date'],
            'purpose' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $recipient = User::findOrFail($validated['user_id']);
        $givenByUserId = $validated['given_by_user_id'] ?? Auth::id();
        $givenByUser = User::find($givenByUserId);
        $senderName = $givenByUser ? $givenByUser->name : 'Treasury';

        FundDisbursal::create([
            'given_by_user_id' => $givenByUserId,
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'disbursal_date' => $validated['disbursal_date'],
            'purpose' => $validated['purpose'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('entries.index', ['tab' => 'disbursals'])
            ->with('status', "Fund advance of ৳" . number_format($validated['amount'], 2) . " given by {$senderName} to {$recipient->name} recorded successfully.");
    }

    /**
     * Record an expense voucher with document upload.
     */
    public function storeExpense(Request $request, FtpStorageService $ftp): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $docPath = null;
        $docUrl = null;
        $docFilename = null;

        if ($request->hasFile('document')) {
            $upload = $ftp->upload($request->file('document'), 'vouchers');
            $docPath = $upload['path'];
            $docUrl = $upload['url'];
            $docFilename = $upload['filename'];
        }

        Expense::create([
            'user_id' => $validated['user_id'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'document_path' => $docPath,
            'document_url' => $docUrl,
            'document_filename' => $docFilename,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('entries.index', ['tab' => 'expenses'])
            ->with('status', 'Expense voucher of ৳' . number_format($validated['amount'], 2) . ' saved with document.');
    }

    /**
     * Download or view attached document securely.
     */
    public function downloadDocument(string $type, int $id, FtpStorageService $ftp): Response|RedirectResponse
    {
        $model = match($type) {
            'expense' => Expense::findOrFail($id),
            'deposit' => FundDeposit::findOrFail($id),
            default => abort(404),
        };

        if (empty($model->document_path)) {
            abort(404, 'No document attached.');
        }

        // If direct public URL exists, redirect
        if (!empty($model->document_url)) {
            return redirect()->away($model->document_url);
        }

        try {
            $contents = $ftp->streamFile($model->document_path);
        } catch (\Throwable $e) {
            abort(500, 'Unable to retrieve document: ' . $e->getMessage());
        }

        if ($contents === null) {
            abort(404, 'Document file not found on remote storage.');
        }

        $extension = pathinfo($model->document_path, PATHINFO_EXTENSION);
        $filename = $model->document_filename ?: ('document_' . $id . '.' . $extension);
        if (!str_contains($filename, '.')) {
            $filename .= '.' . $extension;
        }

        $mime = match(strtolower($extension)) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };

        return response($contents, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . addslashes($filename) . '"',
            'Content-Length' => strlen($contents),
            'Cache-Control' => 'no-cache, private',
        ]);
    }

    /**
     * Delete a fund deposit.
     */
    public function destroyDeposit(FundDeposit $deposit, FtpStorageService $ftp): RedirectResponse
    {
        if ($deposit->document_path) {
            $ftp->delete($deposit->document_path);
        }
        $deposit->delete();

        return redirect()->route('entries.index', ['tab' => 'deposits'])
            ->with('status', 'Fund deposit record deleted successfully.');
    }

    /**
     * Delete a fund disbursal.
     */
    public function destroyDisbursal(FundDisbursal $disbursal): RedirectResponse
    {
        $disbursal->delete();

        return redirect()->route('entries.index', ['tab' => 'disbursals'])
            ->with('status', 'Fund disbursal record deleted successfully.');
    }

    /**
     * Delete an expense voucher.
     */
    public function destroyExpense(Expense $expense, FtpStorageService $ftp): RedirectResponse
    {
        if ($expense->document_path) {
            $ftp->delete($expense->document_path);
        }
        $expense->delete();

        return redirect()->route('entries.index', ['tab' => 'expenses'])
            ->with('status', 'Expense voucher record deleted successfully.');
    }
}
