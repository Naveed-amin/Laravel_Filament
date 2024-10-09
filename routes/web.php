<?php

use App\Http\Controllers\OpenAIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// OpenAI Route
Route::get('/openai/generate-text', [OpenAIController::class, 'generateText'])->name('openai.generate-text');
