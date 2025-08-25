<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController  as AdminProductController;
use App\Http\Controllers\User\ProductController   as UserProductController;
use App\Http\Controllers\User\CategoryController  as UserCategoryController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;

// Email verification
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| Auth (Register / Login / Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verification (Lab03+)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return $request->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')->with('success', 'Email đã được xác thực.')
        : redirect()->route('welcome')->with('success', 'Email đã được xác thực.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Đã gửi lại email xác thực.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Admin routes (chỉ admin & đã verify)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products',   AdminProductController::class);
        Route::resource('orders',     \App\Http\Controllers\Admin\OrderController::class);
    });

/*
|--------------------------------------------------------------------------
| Customer-facing catalog
|--------------------------------------------------------------------------
*/
Route::prefix('products')->name('user.products.')->group(function () {
    Route::get('/',          [UserProductController::class, 'index'])->name('index');
    Route::get('/{product}', [UserProductController::class, 'show'])->name('show');
});
Route::get('/categories', [UserCategoryController::class, 'index'])->name('user.categories.index');

/*
|--------------------------------------------------------------------------
| Cart (Lab04)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('cart')->name('user.cart.')->group(function () {
    Route::get('/',              [CartController::class, 'index'])->name('index');
    Route::post('add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('update/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::delete('clear',       [CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| Orders / Payment (Lab05: COD & MoMo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Checkout
    Route::get('/payment',  [OrderController::class, 'index'])->name('user.payment.index');
    Route::post('/payment', [OrderController::class, 'store'])->name('user.payment.store');

    // Order history & detail
    Route::get('/orders',         [OrderController::class, 'orderHistory'])->name('user.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');

    // Pay again with MoMo
    Route::get('/orders/{order}/pay/momo', [OrderController::class, 'payAgain'])->name('user.orders.momo.pay');
});

/*
|--------------------------------------------------------------------------
| MoMo callbacks (return & IPN)
|--------------------------------------------------------------------------
*/
Route::get('/momo/callback', [OrderController::class, 'callback'])->name('user.payment.momo.callback');
Route::post('/momo/ipn',    [OrderController::class, 'ipn'])->name('user.payment.momo.ipn');
