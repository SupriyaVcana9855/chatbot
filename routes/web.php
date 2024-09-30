<?php

use App\Http\Controllers\AiAgentBotController;
use App\Http\Controllers\AiAgentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\BotmanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

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
Route::get('/abc', [ChatBotController::class, 'abc'])->name('abc');
Route::get('/scriptchatbots/{id}', [ChatBotController::class, 'scriptchatbots'])->name('scriptchatbots');

Route::get('/scriptchatbot/{id}',[ChatBotController::class, 
'scriptchatbot'])->name('scriptchatbot');

Route::get('/chatbot/{id}', [ChatBotController::class, 'show']);
Route::post('/chatbot/message', [ChatBotController::class, 'handleMessage']);


Route::get('/change/status', [ChatBotController::class, 'changestatus']);


Route::get('/sendTestEmail', function () {
    return view('emailverify');
});


Route::get('/otpverify', function () {
    return view('otpverify');
});

Route::get('/welcome', [DashboardController::class, 'welcome'])->name('welcome');
Route::post('/send', [DashboardController::class, 'sendMessage'])->name('send.message');
    


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
Route::get('/get-answer', [ChatBotController::class, 'getAnswer'])->name('getAnswer');

/// Admin Routes
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bots', [BotController::class, 'bots'])->name('bots');
    Route::post('/savebot', [BotController::class, 'savebot'])->name('savebot');
    Route::post('/updateBot', [BotController::class, 'updateBot'])->name('updateBot');
    Route::get('/delete-bot/{id?}',[BotController::class,'deleteBot'])->name('deleteBot');
  
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/get-questions', [ChatBotController::class, 'getQuestion'])->name('questions');
    Route::post('/add-questions', [ChatBotController::class, 'addQuestion'])->name('addQuestion');
    Route::get('/add-questions/{id}', [ChatBotController::class, 'botQuestion'])->name('botQuestion');

    Route::get('templates', [TemplateController::class, 'templates'])->name('templates');
    Route::get('template-view/{id?}', [TemplateController::class, 'templateView'])->name('templateview');
    Route::post('add-bot-template', [TemplateController::class, 'addBotTemplate'])->name('addbottemplate');

    Route::get('/add-template/{id?}', [TemplateController::class, 'addTemplateView'])->name('addTemplateview');
    Route::post('/save-template', [TemplateController::class, 'saveTemplate'])->name('saveTemplate');
    Route::get('/delete-template/{id?}',[TemplateController::class,'templateDelete'])->name('templatedelete');


    Route::get('chatanalytics', [DashboardController::class, 'chatanalytics'])->name('chatanalytics');

    Route::get('/bot-flow/{id}', [ChatBotController::class, 'botFlow'])->name('botFlow');
    Route::get('/get-answer', [ChatBotController::class, 'getAnswer'])->name('getAnswer');
    Route::post('/addQuestionFlow', [ChatBotController::class, 'addQuestionFlow'])->name('addQuestionFlow');
    Route::get('/bot-questions-listing/{id}', [ChatBotController::class, 'singleBotListing'])->name('singleBotListing');

    Route::get('/faq/{chat_bot_id?}/{questions_id?}', [FaqController::class, 'faq'])->name('faq');
    Route::post('/addFaq', [FaqController::class, 'addFaq'])->name('addFaq');
    Route::get('/bot-faq-listing/{id}', [FaqController::class, 'singleBotFaqListing'])->name('singleBotFaqListing');
    Route::get('/delete-faq/{id?}',[FaqController::class,'deleteFaq'])->name('deleteFaq');


    Route::get('/agent',[AiAgentBotController::class,'agents'])->name('agent');
    Route::get('/add-agent/{id?}',[AiAgentBotController::class,'addAgentform'])->name('addagentform');
    Route::post('/save-agent',[AiAgentBotController::class,'saveAgent'])->name('saveAgent');
    Route::get('/delete-agent/{id?}',[AiAgentBotController::class,'deleteAgent'])->name('deleteAgent');

    Route::get('/live-chat',[AiAgentBotController::class,'liveChatAgent'])->name('live-chat');
    Route::post('/messages', [AiAgentBotController::class, 'message'])->name('message');


    Route::get('/clear', function() {
        // Clear the cache
        Artisan::call('optimize:clear');
        return redirect()->route('admin.dashboard');
    });
    
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

Route::match(['get', 'post'], '/botman', [BotmanController::class, 'handle']);
Route::post('/twilio/whatsapp', [TwilioController::class, 'handleIncomingMessage']);
// Route::post('/chatbot', [ChatBotController::class, 'chatbot'])->name('chatbot');
Route::get('/chatbot-script/{id}', [ChatBotController::class, 'getChatbotScript'])->name('chatbot.script');
Route::get('/test-chatbot-script', [ChatBotController::class, 'testChatbotScript']);

Route::get('/website-bot', [ChatBotController::class, 'websiteChat'])->name('website.bot');


Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/bot-chat/{id?}', [ChatBotController::class, 'botChat'])->name('botChat');
Route::get('/editPrefrence', [ChatBotController::class, 'editPrefrence'])->name('editPrefrence');

