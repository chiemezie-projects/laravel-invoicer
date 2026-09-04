<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $clients = collect([
            ['name'=>'Alice Johnson','company'=>'Acme LLC','email'=>'alice@acme.test','city'=>'New York','address'=>'123 Broadway'],
            ['name'=>'Bob Martin','company'=>'Globex Corp','email'=>'bob@globex.test','city'=>'San Francisco','address'=>'456 Market St'],
            ['name'=>'Carol White','company'=>null,'email'=>'carol@example.test','city'=>'Berlin','address'=>'789 Friedrichstr.'],
        ])->map(fn($d)=> Client::create($d));

        foreach ($clients as $i => $client) {
            $inv = Invoice::create([
                'invoice_number' => sprintf('INV-2026-%04d', $i+1),
                'client_id' => $client->id,
                'status' => ['draft','sent','paid'][$i % 3],
                'issue_date' => now()->subDays(rand(1,20)),
                'due_date' => now()->addDays(rand(5,20)),
                'tax_rate' => 19,
                'discount' => $i===1 ? 50 : 0,
                'currency' => 'USD',
                'notes' => 'Payment due within 14 days. Thank you!',
            ]);
            $inv->items()->createMany([
                ['description'=>'Consulting — Project Setup','quantity'=>2,'unit_price'=>500],
                ['description'=>'Development Hours','quantity'=>10,'unit_price'=>85],
                ['description'=>'Hosting & Support','quantity'=>1,'unit_price'=>199],
            ]);
        }
    }
}
