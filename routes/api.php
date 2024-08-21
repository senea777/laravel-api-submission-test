<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionController;

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(SubmissionController::class)->group(function(){
    Route::post('submissions', 'create')->name('submissions.create');
});
