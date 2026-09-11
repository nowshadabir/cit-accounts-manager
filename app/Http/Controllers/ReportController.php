<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\FundDeposit;
use App\Models\FundDisbursal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display the comprehensive report generator and statement view.
     */
    public function index(Request $request): View
    {
        $reportData = $this->buildReportData($request);

        return view('reports.index', $reportData);
    }

    /**
     * Export the filtered report as a professionally formatted Excel (.xlsx) workbook.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $data = $this->buildReportData($request);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('CIT Accounts Management')
            ->setLastModifiedBy(Auth::user()->name ?? 'System')
            ->setTitle('CIT Financial Audit & Ledger Report')
            ->setSubject('Financial Report')
            ->setDescription('Comprehensive Accounts and Settlement Report generated from CIT Accounts System.');

        // Sheet 1: Executive Summary
        $this->buildExcelExecutiveSummarySheet($spreadsheet->getActiveSheet(), $data);

        // Sheet 2: Member Settlement Ledger
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Member Settlements');
        $this->buildExcelSettlementSheet($sheet2, $data);

        // Sheet 3: Fund Advances
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Fund Advances');
        $this->buildExcelDisbursalsSheet($sheet3, $data);

        // Sheet 4: Expense Vouchers
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Expense Vouchers');
        $this->buildExcelExpensesSheet($sheet4, $data);

        // Sheet 5: Capital Deposits
        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Capital Deposits');
        $this->buildExcelDepositsSheet($sheet5, $data);

        // Sheet 6: Combined Transactions Log
        $sheet6 = $spreadsheet->createSheet();
        $sheet6->setTitle('All Transactions Log');
        $this->buildExcelTransactionsSheet($sheet6, $data);

        // Set active sheet back to Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'CIT_Financial_Report_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Build and compute all report datasets according to request filters.
     */
    protected function buildReportData(Request $request): array
    {
        $userId = $request->filled('user_id') ? (int) $request->input('user_id') : null;
        $datePreset = $request->input('date_range', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $disbursalId = $request->filled('disbursal_id') ? (int) $request->input('disbursal_id') : null;
        $category = $request->input('category');
        $reportType = $request->input('report_type', 'all');
        $search = $request->input('search');

        // Resolve Date Range based on Presets
        if ($datePreset !== 'custom' && $datePreset !== 'all') {
            $now = Carbon::now();
            switch ($datePreset) {
                case 'today':
                    $startDate = $now->toDateString();
                    $endDate = $now->toDateString();
                    break;
                case 'this_week':
                    $startDate = $now->copy()->startOfWeek()->toDateString();
                    $endDate = $now->copy()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = $now->copy()->startOfMonth()->toDateString();
                    $endDate = $now->copy()->endOfMonth()->toDateString();
                    break;
                case 'last_month':
                    $startDate = $now->copy()->subMonth()->startOfMonth()->toDateString();
                    $endDate = $now->copy()->subMonth()->endOfMonth()->toDateString();
                    break;
                case 'this_quarter':
                    $startDate = $now->copy()->startOfQuarter()->toDateString();
                    $endDate = $now->copy()->endOfQuarter()->toDateString();
                    break;
                case 'this_year':
                    $startDate = $now->copy()->startOfYear()->toDateString();
                    $endDate = $now->copy()->endOfYear()->toDateString();
                    break;
            }
        } elseif ($datePreset === 'all') {
            $startDate = null;
            $endDate = null;
        }

        // Base Queries
        $depositsQuery = FundDeposit::with(['user', 'creator']);
        $disbursalsQuery = FundDisbursal::with(['user', 'givenBy', 'approver']);
        $expensesQuery = Expense::with(['user', 'creator']);

        // Apply User Filter
        if ($userId) {
            $depositsQuery->where('user_id', $userId);
            $disbursalsQuery->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('given_by_user_id', $userId);
            });
            $expensesQuery->where('user_id', $userId);
        }

        // Apply Specific Disbursal Filter
        if ($disbursalId) {
            $specificDisbursal = FundDisbursal::find($disbursalId);
            $disbursalsQuery->where('id', $disbursalId);
            if ($specificDisbursal) {
                $expensesQuery->where('user_id', $specificDisbursal->user_id);
            }
        } else {
            $specificDisbursal = null;
        }

        // Apply Category Filter
        if ($category) {
            $expensesQuery->where('category', $category);
        }

        // Apply Date Range Filter
        if ($startDate) {
            $depositsQuery->whereDate('deposit_date', '>=', $startDate);
            $disbursalsQuery->whereDate('disbursal_date', '>=', $startDate);
            $expensesQuery->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $depositsQuery->whereDate('deposit_date', '<=', $endDate);
            $disbursalsQuery->whereDate('disbursal_date', '<=', $endDate);
            $expensesQuery->whereDate('expense_date', '<=', $endDate);
        }

        // Apply Keyword Search
        if ($search) {
            $depositsQuery->where(function ($q) use ($search) {
                $q->where('contributor_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });

            $disbursalsQuery->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });

            $expensesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Execute queries ordered by date
        $deposits = $depositsQuery->orderBy('deposit_date', 'desc')->orderBy('id', 'desc')->get();
        $disbursals = $disbursalsQuery->orderBy('disbursal_date', 'desc')->orderBy('id', 'desc')->get();
        $expenses = $expensesQuery->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->get();

        // Calculate Totals for filtered set
        $totalDeposits = (float) $deposits->sum('amount');
        $totalDisbursals = (float) $disbursals->sum('amount');
        $totalExpenses = (float) $expenses->sum('amount');
        $treasuryBalance = $totalDeposits - $totalDisbursals;
        $netOperatingBalance = $totalDeposits - $totalExpenses;

        // Calculate User Settlement Ledgers
        $users = User::orderBy('name')->get();
        $userLedgers = [];
        $totalHoldingByUsers = 0.0;
        $totalReimbursementDue = 0.0;
        $pendingReimbursementsCount = 0;

        foreach ($users as $user) {
            if ($userId && $user->id !== $userId) {
                continue;
            }

            // Calculate for this user with date range applied if specified
            $userDisbursalsQ = FundDisbursal::where('user_id', $user->id);
            $userExpensesQ = Expense::where('user_id', $user->id);
            $userDepositsQ = FundDeposit::where('user_id', $user->id);

            if ($startDate) {
                $userDisbursalsQ->whereDate('disbursal_date', '>=', $startDate);
                $userExpensesQ->whereDate('expense_date', '>=', $startDate);
                $userDepositsQ->whereDate('deposit_date', '>=', $startDate);
            }
            if ($endDate) {
                $userDisbursalsQ->whereDate('disbursal_date', '<=', $endDate);
                $userExpensesQ->whereDate('expense_date', '<=', $endDate);
                $userDepositsQ->whereDate('deposit_date', '<=', $endDate);
            }

            $userAllocated = (float) $userDisbursalsQ->sum('amount');
            $userSpent = (float) $userExpensesQ->sum('amount');
            $userDeposited = (float) $userDepositsQ->sum('amount');
            $balance = $userAllocated - $userSpent;

            $status = 'settled';
            if ($userSpent > $userAllocated) {
                $status = 'reimburse';
                $reimburseAmt = $userSpent - $userAllocated;
                $totalReimbursementDue += $reimburseAmt;
                $pendingReimbursementsCount++;
            } elseif ($userAllocated > $userSpent) {
                $status = 'holding';
                $holdingAmt = $userAllocated - $userSpent;
                $totalHoldingByUsers += $holdingAmt;
            }

            $userLedgers[] = [
                'user' => $user,
                'allocated' => $userAllocated,
                'spent' => $userSpent,
                'deposited' => $userDeposited,
                'balance' => $balance,
                'due_reimbursement' => max(0, $userSpent - $userAllocated),
                'unspent_fund' => max(0, $userAllocated - $userSpent),
                'status' => $status,
                'expense_count' => $userExpensesQ->count(),
                'disbursal_count' => $userDisbursalsQ->count(),
            ];
        }

        // Build Combined Chronological Transactions Log
        $transactions = collect();

        foreach ($deposits as $deposit) {
            $transactions->push([
                'type' => 'Deposit',
                'type_badge' => 'emerald',
                'date' => $deposit->deposit_date,
                'ref_id' => 'DEP-' . str_pad($deposit->id, 4, '0', STR_PAD_LEFT),
                'member' => $deposit->contributor_name . ($deposit->user ? ' (' . $deposit->user->name . ')' : ''),
                'title' => $deposit->description ?: 'Capital Deposit',
                'category_method' => $deposit->payment_method . ($deposit->reference_no ? ' [' . $deposit->reference_no . ']' : ''),
                'inflow' => (float) $deposit->amount,
                'outflow' => 0.0,
                'amount' => (float) $deposit->amount,
                'raw_model' => $deposit,
            ]);
        }

        foreach ($disbursals as $disbursal) {
            $fromName = $disbursal->givenBy ? $disbursal->givenBy->name : 'Treasury';
            $toName = $disbursal->user ? $disbursal->user->name : 'Member';
            $transactions->push([
                'type' => 'Disbursal',
                'type_badge' => 'indigo',
                'date' => $disbursal->disbursal_date,
                'ref_id' => 'DIS-' . str_pad($disbursal->id, 4, '0', STR_PAD_LEFT),
                'member' => "{$fromName} ➔ {$toName}",
                'title' => $disbursal->purpose,
                'category_method' => $disbursal->payment_method . ($disbursal->reference_no ? ' [' . $disbursal->reference_no . ']' : ''),
                'inflow' => 0.0,
                'outflow' => (float) $disbursal->amount,
                'amount' => (float) $disbursal->amount,
                'raw_model' => $disbursal,
            ]);
        }

        foreach ($expenses as $expense) {
            $transactions->push([
                'type' => 'Expense',
                'type_badge' => 'rose',
                'date' => $expense->expense_date,
                'ref_id' => 'EXP-' . str_pad($expense->id, 4, '0', STR_PAD_LEFT),
                'member' => $expense->user ? $expense->user->name : 'Member',
                'title' => $expense->title,
                'category_method' => $expense->category,
                'inflow' => 0.0,
                'outflow' => (float) $expense->amount,
                'amount' => (float) $expense->amount,
                'raw_model' => $expense,
            ]);
        }

        // Sort all transactions descending by date, then by ref_id
        $sortedTransactions = $transactions->sortByDesc(function ($item) {
            return $item['date']->format('Y-m-d') . '_' . $item['ref_id'];
        })->values();

        // Selected user object
        $selectedUser = $userId ? User::find($userId) : null;
        $allDisbursalsList = FundDisbursal::with(['user', 'givenBy'])->latest('disbursal_date')->get();

        return [
            'users' => $users,
            'selectedUserId' => $userId,
            'selectedUser' => $selectedUser,
            'datePreset' => $datePreset,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'disbursalId' => $disbursalId,
            'specificDisbursal' => $specificDisbursal,
            'allDisbursalsList' => $allDisbursalsList,
            'category' => $category,
            'reportType' => $reportType,
            'search' => $search,
            'categories' => EntryController::CATEGORIES,
            'paymentMethods' => EntryController::PAYMENT_METHODS,
            'deposits' => $deposits,
            'disbursals' => $disbursals,
            'expenses' => $expenses,
            'totalDeposits' => $totalDeposits,
            'totalDisbursals' => $totalDisbursals,
            'totalExpenses' => $totalExpenses,
            'treasuryBalance' => $treasuryBalance,
            'netOperatingBalance' => $netOperatingBalance,
            'userLedgers' => $userLedgers,
            'totalHoldingByUsers' => $totalHoldingByUsers,
            'totalReimbursementDue' => $totalReimbursementDue,
            'pendingReimbursementsCount' => $pendingReimbursementsCount,
            'transactions' => $sortedTransactions,
            'generatedAt' => Carbon::now(),
        ];
    }

    /**
     * Build Sheet 1: Executive Summary.
     */
    protected function buildExcelExecutiveSummarySheet($sheet, array $data): void
    {
        $sheet->setTitle('Executive Summary');
        $sheet->setShowGridLines(true);

        // Header Banner
        $sheet->setCellValue('A1', 'CIT ACCOUNTS MANAGEMENT SYSTEM');
        $sheet->setCellValue('A2', 'Executive Financial Audit & Ledger Statement');
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');

        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('1E293B');
        $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->getColor()->setRGB('64748B');

        // Report Metadata
        $periodText = ($data['startDate'] && $data['endDate'])
            ? Carbon::parse($data['startDate'])->format('d M Y') . ' to ' . Carbon::parse($data['endDate'])->format('d M Y')
            : ($data['startDate'] ? 'From ' . Carbon::parse($data['startDate'])->format('d M Y') : 'All Historical Records');

        $userFilterText = $data['selectedUser'] ? $data['selectedUser']->name : 'All Members / System Wide';

        $sheet->setCellValue('A4', 'Report Period:');
        $sheet->setCellValue('B4', $periodText);
        $sheet->setCellValue('D4', 'Generated On:');
        $sheet->setCellValue('E4', $data['generatedAt']->format('d M Y, h:i A'));

        $sheet->setCellValue('A5', 'Scope / Member:');
        $sheet->setCellValue('B5', $userFilterText);
        $sheet->setCellValue('D5', 'Generated By:');
        $sheet->setCellValue('E5', Auth::user()->name ?? 'Administrator');

        $sheet->getStyle('A4:A5')->getFont()->setBold(true)->getColor()->setRGB('475569');
        $sheet->getStyle('D4:D5')->getFont()->setBold(true)->getColor()->setRGB('475569');

        // Key Financial Metrics Table
        $sheet->setCellValue('A7', 'FINANCIAL OVERVIEW & TREASURY METRICS');
        $sheet->mergeCells('A7:E7');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A7:E7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1E293B');

        $metrics = [
            ['Total Capital Raised & Deposited (Inflow)', $data['totalDeposits'], 'Cumulative funds deposited into central treasury'],
            ['Total Funds Disbursed / Advances Allocated', $data['totalDisbursals'], 'Funds given to members for expenses & advances'],
            ['Central Treasury Remaining Balance', $data['treasuryBalance'], 'Remaining un-disbursed funds in treasury'],
            ['Total Documented Expenses (Vouchers)', $data['totalExpenses'], 'Total verified bills and expense vouchers'],
            ['Net Operational Fund Balance', $data['netOperatingBalance'], 'Total deposits minus actual spent expenses'],
            ['Net Advances Holding by Members', $data['totalHoldingByUsers'], 'Advances currently held by members (unspent)'],
            ['Total Reimbursements Due to Members', $data['totalReimbursementDue'], 'Amount institute owes to members who overspent'],
        ];

        $row = 8;
        $sheet->setCellValue('A' . $row, 'Metric Particulars');
        $sheet->setCellValue('B' . $row, 'Amount (BDT)');
        $sheet->setCellValue('C' . $row, 'Operational Context & Notes');
        $sheet->mergeCells('C' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');

        foreach ($metrics as $metric) {
            $row++;
            $sheet->setCellValue('A' . $row, $metric[0]);
            $sheet->setCellValue('B' . $row, $metric[1]);
            $sheet->setCellValue('C' . $row, $metric[2]);
            $sheet->mergeCells('C' . $row . ':E' . $row);

            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            if (in_array($row, [8, 10, 12])) {
                $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
            }
        }

        $sheet->getStyle('A8:E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        // Member Settlements Overview Section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'MEMBER SETTLEMENT SUMMARY');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F766E');

        $row++;
        $sheet->setCellValue('A' . $row, 'Member Name');
        $sheet->setCellValue('B' . $row, 'Advances Received');
        $sheet->setCellValue('C' . $row, 'Total Spent');
        $sheet->setCellValue('D' . $row, 'Net Balance');
        $sheet->setCellValue('E' . $row, 'Settlement Status');
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');

        $startLedgerRow = $row + 1;
        foreach ($data['userLedgers'] as $ledger) {
            $row++;
            $sheet->setCellValue('A' . $row, $ledger['user']->name);
            $sheet->setCellValue('B' . $row, $ledger['allocated']);
            $sheet->setCellValue('C' . $row, $ledger['spent']);
            $sheet->setCellValue('D' . $row, $ledger['balance']);

            $statusText = match ($ledger['status']) {
                'reimburse' => 'Will Receive ৳' . number_format($ledger['due_reimbursement'], 2) . ' from institute',
                'holding' => 'Holding ৳' . number_format($ledger['unspent_fund'], 2) . ' unspent fund',
                default => 'Settled & Balanced',
            };
            $sheet->setCellValue('E' . $row, $statusText);

            $sheet->getStyle('B' . $row . ':D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        $sheet->getStyle('A' . $startLedgerRow . ':E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Sheet 2: Member Settlements.
     */
    protected function buildExcelSettlementSheet($sheet, array $data): void
    {
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'MEMBER FINANCIAL SETTLEMENT & ADVANCE RECONCILIATION');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('1E293B');

        $headers = [
            'A3' => 'Member Name',
            'B3' => 'Email Address',
            'C3' => 'Advances Received (BDT)',
            'D3' => 'Expenses Incurred (BDT)',
            'E3' => 'Net Difference (BDT)',
            'F3' => 'Settlement Action',
            'G3' => 'Institute Due / Holding (BDT)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:G3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1E293B');

        $row = 3;
        foreach ($data['userLedgers'] as $ledger) {
            $row++;
            $sheet->setCellValue('A' . $row, $ledger['user']->name);
            $sheet->setCellValue('B' . $row, $ledger['user']->email);
            $sheet->setCellValue('C' . $row, $ledger['allocated']);
            $sheet->setCellValue('D' . $row, $ledger['spent']);
            $sheet->setCellValue('E' . $row, $ledger['balance']);

            $actionText = match ($ledger['status']) {
                'reimburse' => 'Reimbursement Owed to Member',
                'holding' => 'Member Holding Advance',
                default => 'Fully Settled',
            };
            $sheet->setCellValue('F' . $row, $actionText);

            $dueAmount = match ($ledger['status']) {
                'reimburse' => $ledger['due_reimbursement'],
                'holding' => $ledger['unspent_fund'],
                default => 0.0,
            };
            $sheet->setCellValue('G' . $row, $dueAmount);

            $sheet->getStyle('C' . $row . ':E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Totals Row
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTALS');
        $sheet->setCellValue('C' . $row, '=SUM(C4:C' . ($row - 1) . ')');
        $sheet->setCellValue('D' . $row, '=SUM(D4:D' . ($row - 1) . ')');
        $sheet->setCellValue('E' . $row, '=SUM(E4:E' . ($row - 1) . ')');
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        $sheet->getStyle('C' . $row . ':E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A3:G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Sheet 3: Fund Advances & Disbursals.
     */
    protected function buildExcelDisbursalsSheet($sheet, array $data): void
    {
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'FUND ADVANCES & DISBURSALS LOG');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('1E293B');

        $headers = [
            'A3' => 'Disbursal ID',
            'B3' => 'Date',
            'C3' => 'Disbursed By (Sender)',
            'D3' => 'Recipient Member',
            'E3' => 'Purpose / Heading',
            'F3' => 'Payment Method',
            'G3' => 'Ref / Voucher No',
            'H3' => 'Amount (BDT)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:H3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:H3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4338CA');

        $row = 3;
        foreach ($data['disbursals'] as $disbursal) {
            $row++;
            $sheet->setCellValue('A' . $row, 'DIS-' . str_pad($disbursal->id, 4, '0', STR_PAD_LEFT));
            $sheet->setCellValue('B' . $row, $disbursal->disbursal_date ? $disbursal->disbursal_date->format('Y-m-d') : '');
            $sheet->setCellValue('C' . $row, $disbursal->givenBy ? $disbursal->givenBy->name : 'Treasury');
            $sheet->setCellValue('D' . $row, $disbursal->user ? $disbursal->user->name : 'N/A');
            $sheet->setCellValue('E' . $row, $disbursal->purpose);
            $sheet->setCellValue('F' . $row, $disbursal->payment_method);
            $sheet->setCellValue('G' . $row, $disbursal->reference_no ?: '—');
            $sheet->setCellValue('H' . $row, (float) $disbursal->amount);

            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Totals Row
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL ADVANCES DISBURSED');
        $sheet->setCellValue('H' . $row, '=SUM(H4:H' . ($row - 1) . ')');
        $sheet->getStyle('A' . $row . ':H' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A3:H' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Sheet 4: Expense Vouchers.
     */
    protected function buildExcelExpensesSheet($sheet, array $data): void
    {
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'DOCUMENTED EXPENSE VOUCHERS LOG');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('1E293B');

        $headers = [
            'A3' => 'Voucher ID',
            'B3' => 'Date',
            'C3' => 'Claiming Member',
            'D3' => 'Category',
            'E3' => 'Voucher Title / Particulars',
            'F3' => 'Receipt Document',
            'G3' => 'Amount (BDT)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:G3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('BE123C');

        $row = 3;
        foreach ($data['expenses'] as $expense) {
            $row++;
            $sheet->setCellValue('A' . $row, 'EXP-' . str_pad($expense->id, 4, '0', STR_PAD_LEFT));
            $sheet->setCellValue('B' . $row, $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '');
            $sheet->setCellValue('C' . $row, $expense->user ? $expense->user->name : 'N/A');
            $sheet->setCellValue('D' . $row, $expense->category);
            $sheet->setCellValue('E' . $row, $expense->title . ($expense->description ? ' (' . $expense->description . ')' : ''));
            $sheet->setCellValue('F' . $row, $expense->document_path ? 'Attached (' . $expense->document_filename . ')' : 'No Attachment');
            $sheet->setCellValue('G' . $row, (float) $expense->amount);

            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Totals Row
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL EXPENSES INCURRED');
        $sheet->setCellValue('G' . $row, '=SUM(G4:G' . ($row - 1) . ')');
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A3:G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Sheet 5: Capital Deposits.
     */
    protected function buildExcelDepositsSheet($sheet, array $data): void
    {
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'CAPITAL DEPOSITS & FUND INFLOWS');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('1E293B');

        $headers = [
            'A3' => 'Deposit ID',
            'B3' => 'Date',
            'C3' => 'Contributor Name',
            'D3' => 'Payment Method',
            'E3' => 'Reference No',
            'F3' => 'Purpose / Description',
            'G3' => 'Amount (BDT)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:G3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('047857');

        $row = 3;
        foreach ($data['deposits'] as $deposit) {
            $row++;
            $sheet->setCellValue('A' . $row, 'DEP-' . str_pad($deposit->id, 4, '0', STR_PAD_LEFT));
            $sheet->setCellValue('B' . $row, $deposit->deposit_date ? $deposit->deposit_date->format('Y-m-d') : '');
            $sheet->setCellValue('C' . $row, $deposit->contributor_name);
            $sheet->setCellValue('D' . $row, $deposit->payment_method);
            $sheet->setCellValue('E' . $row, $deposit->reference_no ?: '—');
            $sheet->setCellValue('F' . $row, $deposit->description ?: 'Capital Deposit');
            $sheet->setCellValue('G' . $row, (float) $deposit->amount);

            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Totals Row
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL CAPITAL DEPOSITED');
        $sheet->setCellValue('G' . $row, '=SUM(G4:G' . ($row - 1) . ')');
        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A3:G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Sheet 6: Chronological Transactions Log.
     */
    protected function buildExcelTransactionsSheet($sheet, array $data): void
    {
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'CHRONOLOGICAL FINANCIAL TRANSACTIONS STATEMENT');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('1E293B');

        $headers = [
            'A3' => 'Ref ID',
            'B3' => 'Date',
            'C3' => 'Type',
            'D3' => 'Associated Member / Flow',
            'E3' => 'Particulars / Purpose',
            'F3' => 'Category / Method',
            'G3' => 'Amount (BDT)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:G3')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('334155');

        $row = 3;
        foreach ($data['transactions'] as $tx) {
            $row++;
            $sheet->setCellValue('A' . $row, $tx['ref_id']);
            $sheet->setCellValue('B' . $row, $tx['date']->format('Y-m-d'));
            $sheet->setCellValue('C' . $row, $tx['type']);
            $sheet->setCellValue('D' . $row, $tx['member']);
            $sheet->setCellValue('E' . $row, $tx['title']);
            $sheet->setCellValue('F' . $row, $tx['category_method']);
            $sheet->setCellValue('G' . $row, (float) $tx['amount']);

            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        $sheet->getStyle('A3:G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
