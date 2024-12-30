<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailTrackingController;
use App\Http\Controllers\CampaignController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/track/click', [EmailTrackingController::class, 'trackClick']);
Route::get('/track/open', [EmailTrackingController::class, 'trackOpen']);
Route::get('/campaign/{campaignId}/open-rate', [CampaignController::class, 'calculateOpenRate']);

