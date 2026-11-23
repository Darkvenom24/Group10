<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RailwayPortalController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PracticalsController;
use App\Http\Controllers\PhpPracticalsController;

// -----------------------------------------------------------------------------
// Core Hub & Authentication Routes
// -----------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/quick-login/{username}', [AuthController::class, 'quickLogin'])->name('quick.login');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/switch-system/{code}', [AuthController::class, 'switchSystem'])->name('switch.system');

// -----------------------------------------------------------------------------
// Indian Railways Multi-System Portal (Requirements 2, 3, 4, 5, 6)
// -----------------------------------------------------------------------------
Route::prefix('railway')->name('railway.')->group(function () {
    Route::get('/', [RailwayPortalController::class, 'index'])->name('index');
    Route::post('/book', [RailwayPortalController::class, 'book'])->name('book');
    Route::get('/bookings', [RailwayPortalController::class, 'bookings'])->name('bookings');
    Route::get('/ticket/{id}', [RailwayPortalController::class, 'showTicket'])->name('ticket');
    Route::get('/ticket/{id}/edit', [RailwayPortalController::class, 'editTicket'])->name('ticket.edit');
    Route::put('/ticket/{id}', [RailwayPortalController::class, 'updateTicket'])->name('ticket.update');
    Route::delete('/ticket/{id}', [RailwayPortalController::class, 'cancelTicket'])->name('ticket.cancel');
    Route::get('/systems', [RailwayPortalController::class, 'systems'])->name('systems');
});

// -----------------------------------------------------------------------------
// Team Members (Ridham, Riya, Vaibhav)
// -----------------------------------------------------------------------------
Route::prefix('members')->name('members.')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->name('index');
    Route::get('/{id}', [MemberController::class, 'show'])->name('show');
});

// -----------------------------------------------------------------------------
// Web Technology Practicals (HTML, CSS, JS Calculator, Forms)
// -----------------------------------------------------------------------------
Route::prefix('practicals')->name('practicals.')->group(function () {
    Route::get('/1', [PracticalsController::class, 'practical1'])->name('p1');
    Route::get('/2', [PracticalsController::class, 'practical2'])->name('p2');
    Route::get('/4', [PracticalsController::class, 'practical4'])->name('p4');
    Route::get('/7', [PracticalsController::class, 'practical7'])->name('p7');
    Route::post('/7', [PracticalsController::class, 'practical7Submit'])->name('p7.submit');
    Route::get('/7-data', [PracticalsController::class, 'practical7Data'])->name('p7.data');
});

// -----------------------------------------------------------------------------
// PHP Practicals Suite (9, 10, 11, 12 CRUD & Database Explorer)
// -----------------------------------------------------------------------------
Route::prefix('php')->name('php.')->group(function () {
    Route::get('/', [PhpPracticalsController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/9-1', [PhpPracticalsController::class, 'p9_1'])->name('9_1');
    Route::match(['get', 'post'], '/9-2', [PhpPracticalsController::class, 'p9_2'])->name('9_2');
    Route::match(['get', 'post'], '/9-3', [PhpPracticalsController::class, 'p9_3'])->name('9_3');
    Route::match(['get', 'post'], '/10-1', [PhpPracticalsController::class, 'p10_1'])->name('10_1');
    Route::match(['get', 'post'], '/10-2', [PhpPracticalsController::class, 'p10_2'])->name('10_2');
    Route::match(['get', 'post'], '/11-1', [PhpPracticalsController::class, 'p11_1'])->name('11_1');
    Route::match(['get', 'post'], '/11-2', [PhpPracticalsController::class, 'p11_2'])->name('11_2');
    Route::match(['get', 'post'], '/11-3', [PhpPracticalsController::class, 'p11_3'])->name('11_3');
    Route::match(['get', 'post'], '/11-4', [PhpPracticalsController::class, 'p11_4'])->name('11_4');
    Route::get('/12', [PhpPracticalsController::class, 'p12'])->name('12');
    Route::get('/db-explorer', [PhpPracticalsController::class, 'dbExplorer'])->name('db_explorer');
});
