<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Controllers: Public & Auth
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthController;

// Controllers: Admin 
use App\Http\Controllers\Admin\CategoryController  as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController   as AdminProductController;
use App\Http\Controllers\Admin\OrderController     as AdminOrderController;
use App\Http\Controllers\Admin\ReportController    as AdminReportController;
use App\Http\Controllers\Admin\UserController      as AdminUserController;

// Controllers: User 
use App\Http\Controllers\User\ProductController    as UserProductController;
use App\Http\Controllers\User\CategoryController   as UserCategoryController;
use App\Http\Controllers\User\CartController       as UserCartController;
use App\Http\Controllers\User\OrderController      as UserOrderController;

/*
|--------------------------------------------------------------------------
| Public pages (Lab00)
|---------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| Auth (Lab03)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verification (Lab03+)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')
    ->name('verification.notice');

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
| Admin routes (Lab06)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products',   AdminProductController::class);
        Route::resource('orders',     AdminOrderController::class);
        Route::resource('users',      AdminUserController::class);
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/charts', [AdminReportController::class, 'charts'])->name('reports.charts');
    });

/*
|--------------------------------------------------------------------------
| User routes (Lab02–Lab05)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Catalog
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/',          [UserProductController::class, 'index'])->name('index');
            Route::get('/{product}', [UserProductController::class, 'show'])->name('show');
        });
        Route::get('/categories', [UserCategoryController::class, 'index'])->name('categories.index');

        // Cart (Lab04)
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/',                [UserCartController::class, 'index'])->name('index');
            Route::post('add/{product}',   [UserCartController::class, 'add'])->name('add');
            Route::post('update/{item}',   [UserCartController::class, 'update'])->name('update');
            Route::delete('remove/{item}', [UserCartController::class, 'remove'])->name('remove');
            Route::delete('clear',         [UserCartController::class, 'clear'])->name('clear');
        });

        // Orders & Payment (Lab05)
        Route::get('/payment',  [UserOrderController::class, 'index'])->name('payment.index');
        Route::post('/payment', [UserOrderController::class, 'store'])->name('payment.store');

        Route::get('/orders',            [UserOrderController::class, 'orderHistory'])->name('orders.index');
        Route::get('/orders/{order}',    [UserOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/pay/momo', [UserOrderController::class, 'payAgain'])->name('orders.momo.pay');

        // MoMo callbacks
        Route::get('/momo/callback', [UserOrderController::class, 'callback'])->name('payment.momo.callback');
        Route::post('/momo/ipn',     [UserOrderController::class, 'ipn'])->name('payment.momo.ipn');
    });
