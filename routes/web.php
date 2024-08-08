<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\BotmanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BotController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/emailverify', function () {
    return view('emailverify');
});

Route::get('/otpverify', function () {
    return view('otpverify');
});
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('login');
    });

    Route::get('/signup', function () {
        return view('signup');
    });

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/bots', [BotController::class, 'bots'])->name('bots');
    Route::post('/savebot', [BotController::class, 'savebot'])->name('savebot');

    
    Route::get('/agent', [BotController::class, 'agent'])->name('agent');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Admin-only routes
    Route::middleware(['role:1'])->group(function () {
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/analytics', [AnalyticsController::class, 'update'])->name('analytics.update');
    });
});


Route::get('logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::match(['get','post'],'/botman',[BotmanController::class,'handle']);
Route::post('/twilio/whatsapp', [TwilioController::class, 'handleIncomingMessage']);
// Route::post('/chatbot', [ChatBotController::class, 'chatbot'])->name('chatbot');
Route::get('/chatbot-script/{id}',[ChatBotController::class, 'getChatbotScript'])->name('chatbot.script');
Route::get('/test-chatbot-script', [ChatBotController::class, 'testChatbotScript']);

