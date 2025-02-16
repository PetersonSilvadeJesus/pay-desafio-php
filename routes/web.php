<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\ProfileIsComplete;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cart', function () {
    $products = Product::all();
    return view('cart', ['products' => $products]);
})->middleware(['auth', 'verified', ProfileIsComplete::class])->name('cart');

Route::get('/thanks/{id}', function (string $id) {
    $payment = Payment::find($id);
    return view('thanks', ['payment' => $payment]);
})->middleware(['auth', 'verified'])->name('thanks');

Route::get('/orders', function () {
    $payments = Payment::byUser()->get();
    return view('orders', ['payments' => $payments]);
})->middleware(['auth', 'verified'])->name('orders');

Route::post('/checkout', [PaymentController::class, 'checkout'])
    ->name('payment.checkout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/complete-profile', [ProfileController::class, 'completeProfile'])->name('profile.complete-profile');
    Route::post('/update-cpf_cnpj', [ProfileController::class, 'updateCpfCnpj'])->name('profile.update-cpf_cnpj');
});

require __DIR__.'/auth.php';
