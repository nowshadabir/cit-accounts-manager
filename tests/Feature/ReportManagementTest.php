<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\FundDeposit;
use App\Models\FundDisbursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_reports_page(): void
    {
        $response = $this->get('/reports');
        $response->assertRedirect('/login');

        $exportResponse = $this->get('/reports/export');
        $exportResponse->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reports_page(): void
    {
        $user = User::factory()->create(['name' => 'Report Viewer', 'role' => 'view']);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Financial Reports', false);
        $response->assertSee('Settlement Ledger', false);
        $response->assertSee('Export to Excel (.xlsx)', false);
    }

    public function test_report_displays_kpi_and_member_settlements_correctly(): void
    {
        $userA = User::factory()->create(['name' => 'Kazi Sayed']);
        $userB = User::factory()->create(['name' => 'Abir Hossain']);

        // Deposit 500,000
        FundDeposit::create([
            'contributor_name' => 'Kazi Sayed',
            'user_id' => $userA->id,
            'amount' => 500000.00,
            'deposit_date' => '2026-09-01',
            'payment_method' => 'Bank Transfer',
            'description' => 'Initial pool capital',
        ]);

        // Disburse 20,000 to Abir
        FundDisbursal::create([
            'given_by_user_id' => $userA->id,
            'user_id' => $userB->id,
            'amount' => 20000.00,
            'disbursal_date' => '2026-09-05',
            'purpose' => 'Office setup advance',
            'payment_method' => 'Cash',
        ]);

        // Abir spends 25,000 (5,000 reimbursement due)
        Expense::create([
            'user_id' => $userB->id,
            'category' => 'Office & Supplies',
            'amount' => 25000.00,
            'expense_date' => '2026-09-10',
            'title' => 'Executive Desk & Chair',
        ]);

        $response = $this->actingAs($userA)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('৳ 500,000.00'); // Total deposited
        $response->assertSee('৳ 20,000.00');  // Total disbursed
        $response->assertSee('৳ 25,000.00');  // Total spent
        $response->assertSee('Will receive ৳ 5,000.00 from institute'); // Reimbursement due
        $response->assertSee('Executive Desk & Chair');
        $response->assertSee('Office setup advance');
    }

    public function test_can_filter_report_by_specific_user(): void
    {
        $userA = User::factory()->create(['name' => 'Alice Member']);
        $userB = User::factory()->create(['name' => 'Bob Member']);

        Expense::create([
            'user_id' => $userA->id,
            'category' => 'Food & Refreshments',
            'amount' => 3000.00,
            'expense_date' => '2026-09-08',
            'title' => 'Alice Team Lunch',
        ]);

        Expense::create([
            'user_id' => $userB->id,
            'category' => 'IT & Software',
            'amount' => 8000.00,
            'expense_date' => '2026-09-09',
            'title' => 'Bob Server Subscription',
        ]);

        $response = $this->actingAs($userA)->get("/reports?user_id={$userA->id}");

        $response->assertStatus(200);
        $response->assertSee('Alice Team Lunch');
        $response->assertDontSee('Bob Server Subscription');
    }

    public function test_can_filter_report_by_timespan_and_date_range(): void
    {
        $user = User::factory()->create();

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Utility & Bills',
            'amount' => 4500.00,
            'expense_date' => '2026-01-15',
            'title' => 'January Electric Bill',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Utility & Bills',
            'amount' => 5200.00,
            'expense_date' => '2026-09-12',
            'title' => 'September Internet Bill',
        ]);

        $response = $this->actingAs($user)->get('/reports?date_range=custom&start_date=2026-09-01&end_date=2026-09-30');

        $response->assertStatus(200);
        $response->assertSee('September Internet Bill');
        $response->assertDontSee('January Electric Bill');
    }

    public function test_can_filter_report_by_specific_disbursal(): void
    {
        $user = User::factory()->create(['name' => 'Tareq']);

        $disbursal1 = FundDisbursal::create([
            'user_id' => $user->id,
            'amount' => 10000.00,
            'disbursal_date' => '2026-09-01',
            'purpose' => 'Conference tickets advance',
            'payment_method' => 'Cash',
        ]);

        $disbursal2 = FundDisbursal::create([
            'user_id' => $user->id,
            'amount' => 15000.00,
            'disbursal_date' => '2026-09-05',
            'purpose' => 'Marketing banner prints',
            'payment_method' => 'Bank Transfer',
        ]);

        $response = $this->actingAs($user)->get("/reports?disbursal_id={$disbursal1->id}");

        $response->assertStatus(200);
        $response->assertSee('Conference tickets advance');
        $response->assertSee('Subtotal: ৳ 10,000.00');
        $response->assertDontSee('Subtotal: ৳ 25,000.00');
    }

    public function test_can_export_report_as_excel_spreadsheet(): void
    {
        $user = User::factory()->create(['name' => 'Excel User', 'role' => 'super admin']);

        FundDeposit::create([
            'contributor_name' => 'Investor One',
            'amount' => 100000.00,
            'deposit_date' => '2026-09-10',
            'payment_method' => 'Bank Transfer',
            'description' => 'Seed deposit',
        ]);

        FundDisbursal::create([
            'user_id' => $user->id,
            'amount' => 15000.00,
            'disbursal_date' => '2026-09-11',
            'purpose' => 'Advance for equipment',
            'payment_method' => 'Cash',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Office & Supplies',
            'amount' => 12000.00,
            'expense_date' => '2026-09-12',
            'title' => 'Equipment Purchase',
        ]);

        $response = $this->actingAs($user)->get('/reports/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('CIT_Financial_Report_', $response->headers->get('Content-Disposition'));
    }
}
