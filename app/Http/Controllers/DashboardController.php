<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->with('items')->get()->sum(fn ($inv) => $inv->total),
            'outstanding' => Invoice::whereIn('status', ['sent', 'overdue'])->with('items')->get()->sum(fn ($inv) => $inv->total),
            'draft_count' => Invoice::where('status', 'draft')->count(),
            'client_count' => Client::count(),
            'overdue_count' => Invoice::where('status', 'overdue')->count(),
        ];

        $recentInvoices = Invoice::with(['client', 'items'])->latest()->take(5)->get();
        $recentClients = Client::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentInvoices', 'recentClients'));
    }
}
