<?php


namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Despesa::where('user_id', Auth::id())->sum('valor_despesa');
        $count = Despesa::where('user_id', Auth::id())->count();
        $recent = Despesa::where('user_id', Auth::id())->orderBy('data_despesa', 'desc')->take(5)->get();

        return view('dashboard', compact('total', 'count', 'recent'));
    }
}