<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\County;
use App\Models\ShippingCharge;



class DashboardController extends Controller
{
    public function index() {
        // Fetch total sales (grand_total) grouped by date
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $salesData->pluck('date');
        $values = $salesData->pluck('total');

        // Get the top-selling books by joining the books table
        $topSellingBooks = OrderItem::join('books', 'order_items.book_id', '=', 'books.id')
            ->select('books.title', DB::raw('SUM(order_items.qty) as total_sales'))
            ->groupBy('books.title')
            ->orderBy('total_sales', 'desc')
            ->limit(10) // Adjust as necessary
            ->get();

        // Extract book titles and sales values
        $bookLabels = $topSellingBooks->pluck('title');
        $bookValues = $topSellingBooks->pluck('total_sales');

        // Get sales by category by joining categories and books tables
        $salesByCategory = OrderItem::join('books', 'order_items.book_id', '=', 'books.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.qty) as total_sales'))
            ->groupBy('categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Extract category names and sales values
        $categoryLabels = $salesByCategory->pluck('name');
        $categoryValues = $salesByCategory->pluck('total_sales');

         // Fetch average shipping cost per county
         $shippingCosts = ShippingCharge::join('county', 'shipping_charges.county_id', '=', 'county.id')
         ->select('county.name', DB::raw('AVG(shipping_charges.amount) as avg_shipping_cost'))
         ->groupBy('county.name')
         ->orderBy('avg_shipping_cost', 'desc')
         ->get();

     $countyLabels = $shippingCosts->pluck('name');
     $countyValues = $shippingCosts->pluck('avg_shipping_cost');

        return view('admin.dashboard', compact('labels', 'values', 'bookLabels', 'bookValues', 'categoryLabels', 'categoryValues', 'countyLabels', 'countyValues'));
    }
}
