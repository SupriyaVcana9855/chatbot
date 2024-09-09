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

Route::get('/scriptchatbot/{id}',[ChatBotController::class, 'scriptchatbot'])->name('scriptchatbot');

Route::get('/chatbot/{id}', [ChatBotController::class, 'show']);
Route::post('/chatbot/message', [ChatBotController::class, 'handleMessage']);

Route::get('/sendTestEmail', function () {
    return view('emailverify');
});

  
Route::get('/otpverify', function () {
    return view('otpverify');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/setup/{id}', [BotController::class, 'setup'])->name('setup');
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
    // Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/get-answer',[ChatBotController::class, 'getAnswer'])->name('getAnswer');

/// Admin Routes
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bots', [BotController::class, 'bots'])->name('bots');
    Route::post('/savebot', [BotController::class, 'savebot'])->name('savebot');
    Route::post('/updateBot', [BotController::class, 'updateBot'])->name('updateBot');
    Route::get('/agent', [BotController::class, 'agent'])->name('agent');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/get-questions',[ChatBotController::class, 'getQuestion'])->name('questions');
    Route::post('/add-questions',[ChatBotController::class, 'addQuestion'])->name('addQuestion');
    Route::get('/add-questions/{id}',[ChatBotController::class, 'botQuestion'])->name('botQuestion');

    Route::get('templates', [DashboardController::class, 'templates'])->name('templates');
    Route::get('template-view', [DashboardController::class, 'templateView'])->name('templateview');

    Route::get('/bot-flow/{id}',[ChatBotController::class, 'botFlow'])->name('botFlow');
    Route::get('/get-answer',[ChatBotController::class, 'getAnswer'])->name('getAnswer');
    Route::post('/addQuestionFlow',[ChatBotController::class, 'addQuestionFlow'])->name('addQuestionFlow');
    Route::get('/bot-questions-listing/{id}',[ChatBotController::class, 'singleBotListing'])->name('singleBotListing');
});


// Subadmin Routes
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/subadmin/dashboard', [DashboardController::class, 'index'])->name('subadmin.dashboard');
    // Specific routes that subadmin can access...
});

// User Routes
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    // Other user routes...
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

Route::get('/website-bot', [ChatBotController::class, 'websiteChat'])->name('website.bot');


Route::get('/bot-chat', function () {
    return view('bots.bot-chat');
});
Route::get('/editPrefrence', [ChatBotController::class, 'editPrefrence'])->name('editPrefrence');
