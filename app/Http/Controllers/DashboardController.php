<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalStockIn = StockTransaction::where('type', 'masuk')->sum('quantity');
        $totalStockOut = StockTransaction::where('type', 'keluar')->sum('quantity');
        $lowStockCount = Product::lowStock()->count();
                $topMinProducts = Product::with('category')->lowStock()->orderBy('stock', 'asc')->limit(5)->get();
        $topMaxProducts = Product::with('category')->orderBy('stock', 'desc')->limit(5)->get();
        $latestTransactions = StockTransaction::with(['product.category', 'user'])->latest()->limit(5)->get();
        $monthlyTransactions = StockTransaction::select(DB::raw('DATE(transaction_date) as date'), DB::raw("SUM(CASE WHEN type = 'masuk' THEN quantity ELSE 0 END) as total_in"), DB::raw("SUM(CASE WHEN type = 'keluar' THEN quantity ELSE 0 END) as total_out"))->whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year)->groupBy(DB::raw('DATE(transaction_date)'))->orderBy('date')->get();

        $chartLabels = $monthlyTransactions->pluck("date")->map(function ($date) {
            return date("d M", strtotime($date));
        })->values();

        $chartIn = $monthlyTransactions->pluck("total_in")->map(function ($value) {
            return (int) $value;
        })->values();

        $chartOut = $monthlyTransactions->pluck("total_out")->map(function ($value) {
            return (int) $value;
        })->values();

        return view("dashboard.index", compact(
            "totalProducts",
            "totalStock",
            "totalStockIn",
            "totalStockOut",
            "lowStockCount",
            "topMinProducts",
            "topMaxProducts",
            "latestTransactions",
            "chartLabels",
            "chartIn",
            "chartOut"
        ));
    }
}