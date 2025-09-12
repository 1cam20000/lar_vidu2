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
| Lưu ý: đã alias 'verified' trong bootstrap/app.php
|   $middleware->alias(['verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class])
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // đánh dấu verified

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
    });

/*
|--------------------------------------------------------------------------
| Customer routes (xem sản phẩm/danh mục)
|--------------------------------------------------------------------------
| Cho phép public xem; nếu muốn yêu cầu đăng nhập/verified cho 1 số hành động,
| bạn có thể bọc riêng bằng middleware(['auth','verified']) ở các route cần thiết.
*/
Route::prefix('products')
    ->name('user.products.')
    ->group(function () {
        Route::get('/',           [UserProductController::class, 'index'])->name('index');
        Route::get('/{product}',  [UserProductController::class, 'show'])->name('show');
    });

Route::get('/categories', [UserCategoryController::class, 'index'])->name('user.categories.index');

// Giỏ hàng (chỉ customer đã login & verified mới dùng)
Route::middleware(['auth', 'verified'])->prefix('cart')->name('user.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('update/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::delete('clear', [CartController::class, 'clear'])->name('clear');
});
