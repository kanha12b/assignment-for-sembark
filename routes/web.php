<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\ShortUrl;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

    require __DIR__.'/auth.php';

Route::get('/{code}', function ($code) {
    $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

    $shortUrl->increment('hits');

    return redirect()->away($shortUrl->original_url);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:SUPERADMIN'])->group(function(){
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});

Route::middleware(['auth', 'role:ADMIN'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

Route::middleware(['auth', 'role:MEMBER'])->group(function(){
    Route::get('/member/dashboard', [MemberController::class, 'index'])->name('member.dashboard');
});

Route::middleware(['auth', 'role:SUPERADMIN'])->group(function () {
    Route::post('/superadmin/invite', [SuperAdminController::class, 'invite'])->name('superadmin.invite');
});

Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::post('/admin/invite-user', [AdminController::class, 'inviteUser'])
        ->name('admin.invite.user');
});

Route::middleware(['auth', 'role:ADMIN,MEMBER'])->group(function () {
    Route::post('/short-url', [ShortUrlController::class, 'store'])
        ->name('admin.short-url.store');
});

    