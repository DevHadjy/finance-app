<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/animation', function () {
        return view('animation');
    })->name('animation');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('despesas', DespesaController::class)->except(['show']);
    Route::get('despesas/import', [DespesaController::class, 'importForm'])->name('despesas.importForm');
    Route::post('despesas/import', [DespesaController::class, 'import'])->name('despesas.import');
});

require __DIR__.'/auth.php';