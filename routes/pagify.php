<?php

use Millat\Pagify\Http\Controllers\PageBuilderController;
use Illuminate\Support\Facades\Route;

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

// Setup the PageBuilderController routes with optional prefix and middleware
Route::controller(PageBuilderController::class)->prefix(config('pagify.url_prefix', 'pagify'))->middleware(config('pagify.route_middleware', []))->group(function () {
    Route::get('pages/{id}/build', 'build')->name('pagify.build'); // Show builder view for editing a page
    Route::post('sections/settings', 'getSectionSettings')->name('pagify.get.section.settings'); // Get settings for a section
    Route::post('pages/settings/update', 'updatePageSettings')->name('pagify.update.page.settings'); // Update settings for a page
});

// Standalone routes outside the group
Route::get('pages/{id}/iframe', [PageBuilderController::class, 'iframe'])->middleware(config('pagify.route_middleware', []))->name('pagify.iframe'); // Show iframe view for previewing a page
Route::post('pagify/upload/files', [PageBuilderController::class, 'uploadFiles'])->name('pagify.upload.files'); // Upload files for a page
