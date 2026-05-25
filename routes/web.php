<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivity;
use App\Http\Controllers\Admin\AgentController as AdminAgents;
use App\Http\Controllers\Admin\ConnectorController as AdminConnectors;
use App\Http\Controllers\Admin\UserConnectorController as AdminUserConnectors;
use App\Http\Controllers\Admin\TrainingController as AdminTrainings;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SkillController as AdminSkills;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptions;
use App\Http\Controllers\Admin\UserController as AdminUsers;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ConnectorOAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/training', [PageController::class, 'training'])->name('training');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send')->middleware('throttle:30,1');

Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe');

Route::get('/digital-avatars', [AvatarController::class, 'index'])->name('digital-avatars');
Route::post('/digital-avatars', [AvatarController::class, 'store'])->name('digital-avatars.store');

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
    Route::get('/dashboard/connectors', [AuthController::class, 'userConnectors'])->name('dashboard.connectors');
    Route::get('/dashboard/profile', [AuthController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [AuthController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::put('/dashboard/profile/password', [AuthController::class, 'updatePassword'])->name('dashboard.profile.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Connector OAuth
    Route::get('/connectors/{slug}/authorize',   [ConnectorOAuthController::class, 'authorize'])->name('connectors.authorize');
    Route::get('/connectors/{slug}/callback',    [ConnectorOAuthController::class, 'callback'])->name('connectors.callback');
    Route::post('/connectors/{slug}/disconnect', [ConnectorOAuthController::class, 'disconnect'])->name('connectors.disconnect');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/activity', [AdminActivity::class, 'index'])->name('activity');
    Route::get('/trainings', [AdminTrainings::class, 'index'])->name('trainings.index');
    Route::get('/trainings/create', [AdminTrainings::class, 'create'])->name('trainings.create');
    Route::post('/trainings', [AdminTrainings::class, 'store'])->name('trainings.store');
    Route::get('/trainings/{training}/edit', [AdminTrainings::class, 'edit'])->name('trainings.edit');
    Route::put('/trainings/{training}', [AdminTrainings::class, 'update'])->name('trainings.update');
    Route::delete('/trainings/{training}', [AdminTrainings::class, 'destroy'])->name('trainings.destroy');
    Route::get('/users', [AdminUsers::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUsers::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-admin', [AdminUsers::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::get('/subscriptions', [AdminSubscriptions::class, 'allAgents'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [AdminSubscriptions::class, 'show'])->name('subscriptions.show');
    Route::put('/subscriptions/{subscription}', [AdminSubscriptions::class, 'update'])->name('subscriptions.update');
    Route::get('/subscriptions/{subscription}/skills/{skill}', [\App\Http\Controllers\Admin\SubscriptionSkillController::class, 'show'])->name('subscriptions.skills.show');
    Route::put('/subscriptions/{subscription}/skills/{skill}', [\App\Http\Controllers\Admin\SubscriptionSkillController::class, 'update'])->name('subscriptions.skills.update');
    Route::post('/subscriptions/{subscription}/skills/{skill}/refresh', [\App\Http\Controllers\Admin\SubscriptionSkillController::class, 'refresh'])->name('subscriptions.skills.refresh');
    Route::post('/subscriptions/{subscription}/skills/populate', [\App\Http\Controllers\Admin\SubscriptionSkillController::class, 'populate'])->name('subscriptions.skills.populate');
    Route::get('/users/{user}/agents', [AdminSubscriptions::class, 'userAgents'])->name('users.agents');
    Route::post('/users/{user}/agents', [AdminSubscriptions::class, 'assign'])->name('users.agents.assign');
    Route::delete('/users/{user}/agents/{subscription}', [AdminSubscriptions::class, 'revoke'])->name('users.agents.revoke');
    Route::get('/skills', [AdminSkills::class, 'index'])->name('skills.index');
    Route::get('/skills/create', [AdminSkills::class, 'create'])->name('skills.create');
    Route::post('/skills', [AdminSkills::class, 'store'])->name('skills.store');
    Route::get('/skills/{skill}/edit', [AdminSkills::class, 'edit'])->name('skills.edit');
    Route::put('/skills/{skill}', [AdminSkills::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [AdminSkills::class, 'destroy'])->name('skills.destroy');
    Route::get('/user-connectors', [AdminUserConnectors::class, 'index'])->name('user-connectors.index');
    Route::get('/users/{user}/connectors', [AdminUserConnectors::class, 'userConnectors'])->name('users.connectors');
    Route::post('/users/{user}/connectors', [AdminUserConnectors::class, 'assign'])->name('users.connectors.assign');
    Route::get('/users/{user}/connectors/{userConnector}/edit',    [AdminUserConnectors::class, 'edit'])->name('users.connectors.edit');
    Route::put('/users/{user}/connectors/{userConnector}',         [AdminUserConnectors::class, 'update'])->name('users.connectors.update');
    Route::get('/users/{user}/connectors/{userConnector}/config',  [AdminUserConnectors::class, 'editConfig'])->name('users.connectors.config');
    Route::put('/users/{user}/connectors/{userConnector}/config',  [AdminUserConnectors::class, 'updateConfig'])->name('users.connectors.config.update');
    Route::delete('/users/{user}/connectors/{userConnector}', [AdminUserConnectors::class, 'revoke'])->name('users.connectors.revoke');
    Route::get('/connectors', [AdminConnectors::class, 'index'])->name('connectors.index');
    Route::get('/connectors/create', [AdminConnectors::class, 'create'])->name('connectors.create');
    Route::post('/connectors', [AdminConnectors::class, 'store'])->name('connectors.store');
    Route::get('/connectors/{connector}/edit', [AdminConnectors::class, 'edit'])->name('connectors.edit');
    Route::put('/connectors/{connector}', [AdminConnectors::class, 'update'])->name('connectors.update');
    Route::delete('/connectors/{connector}', [AdminConnectors::class, 'destroy'])->name('connectors.destroy');
    Route::get('/agents', [AdminAgents::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [AdminAgents::class, 'create'])->name('agents.create');
    Route::post('/agents', [AdminAgents::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}', [AdminAgents::class, 'show'])->name('agents.show');
    Route::get('/agents/{agent}/skills/{skill}', [\App\Http\Controllers\Admin\AgentSkillController::class, 'show'])->name('agents.skills.show');
    Route::put('/agents/{agent}/skills/{skill}', [\App\Http\Controllers\Admin\AgentSkillController::class, 'update'])->name('agents.skills.update');
    Route::post('/agents/{agent}/skills/{skill}/refresh', [\App\Http\Controllers\Admin\AgentSkillController::class, 'refresh'])->name('agents.skills.refresh');
    Route::get('/agents/{agent}/edit', [AdminAgents::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [AdminAgents::class, 'update'])->name('agents.update');
    Route::delete('/agents/{agent}', [AdminAgents::class, 'destroy'])->name('agents.destroy');
});
