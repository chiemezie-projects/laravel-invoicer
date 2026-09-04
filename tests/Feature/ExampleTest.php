<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('Mini Invoicer');
    }

    public function test_clients_and_invoices_routes(): void
    {
        $this->get(route('clients.index'))->assertOk();
        $this->get(route('invoices.index'))->assertOk();
        $this->get(route('clients.create'))->assertOk();
        $this->get(route('invoices.create'))->assertOk();
    }

    public function test_can_create_client_and_invoice(): void
    {
        $client = \App\Models\Client::create(['name'=>'Test Co','email'=>'test@test.test','company'=>'Test Co']);
        $resp = $this->post(route('invoices.store'), [
            'invoice_number'=>'INV-TEST-001',
            'client_id'=>$client->id,
            'status'=>'draft',
            'issue_date'=>now()->toDateString(),
            'due_date'=>now()->addDays(7)->toDateString(),
            'tax_rate'=>10,
            'discount'=>5,
            'currency'=>'USD',
            'notes'=>'Test',
            'items'=>[
                ['description'=>'Item A','quantity'=>2,'unit_price'=>100],
                ['description'=>'Item B','quantity'=>1,'unit_price'=>50],
            ]
        ]);
        $resp->assertRedirect();
        $this->assertDatabaseHas('invoices',['invoice_number'=>'INV-TEST-001']);
        $inv = \App\Models\Invoice::where('invoice_number','INV-TEST-001')->first();
        $this->assertEquals(270.00, (float)$inv->total);
        // Just verify total calc works
        $this->assertEquals(270.00, (float)$inv->total);
    }
}
