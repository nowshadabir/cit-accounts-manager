<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\FundDeposit;
use App\Models\FundDisbursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EntryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_entry_form(): void
    {
        $response = $this->get('/entry-form');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_entry_form(): void
    {
        $user = User::factory()->create([
            'role' => 'super admin',
        ]);

        $response = $this->actingAs($user)->get('/entry-form');

        $response->assertStatus(200);
        $response->assertSee('Treasury & Financial Entries', false);
        $response->assertSee('Member Settlements', false);
    }

    public function test_can_record_fund_deposit(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('bank_receipt.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post('/entry-form/deposit', [
            'contributor_name' => 'Person A',
            'amount' => '200000',
            'deposit_date' => '2026-09-12',
            'payment_method' => 'Bank Transfer',
            'reference_no' => 'CHQ-998822',
            'description' => 'Capital contribution round 1',
            'document' => $file,
        ]);

        $response->assertRedirect('/entry-form?tab=deposits');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('fund_deposits', [
            'contributor_name' => 'Person A',
            'amount' => 200000.00,
            'payment_method' => 'Bank Transfer',
        ]);
    }

    public function test_can_record_fund_disbursal_to_user(): void
    {
        $admin = User::factory()->create(['name' => 'Kazi Sayed', 'role' => 'super admin']);
        $recipient = User::factory()->create(['name' => 'Abir']);

        $response = $this->actingAs($admin)->post('/entry-form/disbursal', [
            'given_by_user_id' => $admin->id,
            'user_id' => $recipient->id,
            'amount' => '10000',
            'disbursal_date' => '2026-09-12',
            'purpose' => 'Advance for office supplies',
            'payment_method' => 'Cash',
        ]);

        $response->assertRedirect('/entry-form?tab=disbursals');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('fund_disbursals', [
            'given_by_user_id' => $admin->id,
            'user_id' => $recipient->id,
            'amount' => 10000.00,
            'purpose' => 'Advance for office supplies',
        ]);

        $viewResponse = $this->actingAs($admin)->get('/entry-form?tab=disbursals');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('From:');
        $viewResponse->assertSee('Kazi Sayed');
        $viewResponse->assertSee('To:');
        $viewResponse->assertSee('Abir');
    }

    public function test_can_record_expense_voucher_with_document(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['name' => 'Person B']);

        $file = UploadedFile::fake()->image('invoice.jpg');

        $response = $this->actingAs($user)->post('/entry-form/expense', [
            'user_id' => $user->id,
            'category' => 'Office & Supplies',
            'amount' => '12500',
            'expense_date' => '2026-09-12',
            'title' => 'Purchased printer toner and desk chair',
            'description' => 'Paid in cash with attached invoice',
            'document' => $file,
        ]);

        $response->assertRedirect('/entry-form?tab=expenses');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'amount' => 12500.00,
            'title' => 'Purchased printer toner and desk chair',
        ]);
    }

    public function test_user_settlement_ledger_shows_reimbursement_when_user_spends_more_than_allocated(): void
    {
        $user = User::factory()->create(['name' => 'Person B']);

        // Disburse 10,000 to Person B
        FundDisbursal::create([
            'user_id' => $user->id,
            'amount' => 10000.00,
            'disbursal_date' => '2026-09-12',
            'purpose' => 'Advance',
            'payment_method' => 'Cash',
        ]);

        // Person B spends 12,500
        Expense::create([
            'user_id' => $user->id,
            'category' => 'Office & Supplies',
            'amount' => 12500.00,
            'expense_date' => '2026-09-12',
            'title' => 'Office Items',
        ]);

        $response = $this->actingAs($user)->get('/entry-form');

        $response->assertStatus(200);
        // Assert that the page displays that user will receive 2,500 reimbursement from institute
        $response->assertSee('Will receive ৳ 2,500.00');
        $response->assertSee('from institute');
    }

    public function test_user_settlement_ledger_shows_unspent_holding_when_user_spends_less_than_allocated(): void
    {
        $user = User::factory()->create(['name' => 'Person C']);

        // Disburse 10,000 to Person C
        FundDisbursal::create([
            'user_id' => $user->id,
            'amount' => 10000.00,
            'disbursal_date' => '2026-09-12',
            'purpose' => 'Advance',
            'payment_method' => 'Cash',
        ]);

        // Person C spends 4,000
        Expense::create([
            'user_id' => $user->id,
            'category' => 'Food & Refreshments',
            'amount' => 4000.00,
            'expense_date' => '2026-09-12',
            'title' => 'Meeting snacks',
        ]);

        $response = $this->actingAs($user)->get('/entry-form');

        $response->assertStatus(200);
        // Assert that the page displays user holding unspent fund of 6,000
        $response->assertSee('Holding ৳ 6,000.00');
        $response->assertSee('unspent institute fund');
    }

    public function test_can_download_document_from_local_storage(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/deposits/receipt.pdf', 'dummy pdf content');

        $user = User::factory()->create();
        $deposit = FundDeposit::create([
            'contributor_name' => 'John Doe',
            'amount' => 50000.00,
            'deposit_date' => '2026-09-12',
            'payment_method' => 'Cash',
            'document_path' => 'uploads/deposits/receipt.pdf',
            'document_filename' => 'receipt.pdf',
        ]);

        $response = $this->actingAs($user)->get("/documents/deposit/{$deposit->id}");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="receipt.pdf"');
        $this->assertEquals('dummy pdf content', $response->getContent());
    }

    public function test_view_only_user_cannot_create_or_delete_entries(): void
    {
        $viewUser = User::factory()->create(['role' => 'view']);

        // Cannot create deposit
        $depositResponse = $this->actingAs($viewUser)->post('/entry-form/deposit', [
            'contributor_name' => 'View User Contrib',
            'amount' => '1000',
            'deposit_date' => '2026-09-12',
            'payment_method' => 'Cash',
        ]);
        $depositResponse->assertStatus(403);

        // Cannot create disbursal
        $disbursalResponse = $this->actingAs($viewUser)->post('/entry-form/disbursal', [
            'user_id' => $viewUser->id,
            'amount' => '1000',
            'disbursal_date' => '2026-09-12',
            'purpose' => 'Test',
            'payment_method' => 'Cash',
        ]);
        $disbursalResponse->assertStatus(403);

        // Cannot create expense
        $expenseResponse = $this->actingAs($viewUser)->post('/entry-form/expense', [
            'user_id' => $viewUser->id,
            'category' => 'Office & Supplies',
            'amount' => '1000',
            'expense_date' => '2026-09-12',
            'title' => 'Test expense',
        ]);
        $expenseResponse->assertStatus(403);

        // Cannot delete deposit
        $deposit = FundDeposit::create([
            'contributor_name' => 'John',
            'amount' => 1000,
            'deposit_date' => '2026-09-12',
            'payment_method' => 'Cash',
        ]);
        $deleteResponse = $this->actingAs($viewUser)->delete("/entry-form/deposit/{$deposit->id}");
        $deleteResponse->assertStatus(403);
    }

    public function test_view_only_user_can_view_entry_form_with_read_only_mode(): void
    {
        $viewUser = User::factory()->create(['role' => 'view']);

        $response = $this->actingAs($viewUser)->get('/entry-form');

        $response->assertStatus(200);
        $response->assertSee('View-Only Access (Read Only)');
        $response->assertDontSee('+ Record Expense');
    }
}
