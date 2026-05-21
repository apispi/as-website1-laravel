<?php

use App\Http\Controllers\Admin\AgentController as AdminAgents;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptions;
use App\Http\Controllers\Admin\UserController as AdminUsers;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/training', [PageController::class, 'training'])->name('training');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe');

Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
Route::get('/agents/{slug}', [AgentController::class, 'show'])->name('agents.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Auth routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/catalog', [AuthController::class, 'catalog'])->name('dashboard.catalog');
    Route::get('/dashboard/agents', [AuthController::class, 'userAgents'])->name('dashboard.agents.index');
    Route::get('/dashboard/agents/{subscription}', [AuthController::class, 'userAgent'])->name('dashboard.agents.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUsers::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUsers::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-admin', [AdminUsers::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::get('/subscriptions', [AdminSubscriptions::class, 'allAgents'])->name('subscriptions.index');
    Route::get('/users/{user}/agents', [AdminSubscriptions::class, 'userAgents'])->name('users.agents');
    Route::post('/users/{user}/agents', [AdminSubscriptions::class, 'assign'])->name('users.agents.assign');
    Route::delete('/users/{user}/agents/{subscription}', [AdminSubscriptions::class, 'revoke'])->name('users.agents.revoke');
    Route::get('/agents', [AdminAgents::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [AdminAgents::class, 'create'])->name('agents.create');
    Route::post('/agents', [AdminAgents::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}/edit', [AdminAgents::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [AdminAgents::class, 'update'])->name('agents.update');
    Route::delete('/agents/{agent}', [AdminAgents::class, 'destroy'])->name('agents.destroy');
});
