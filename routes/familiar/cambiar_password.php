<?php

use App\Http\Controllers\FamiliarController;

Route::get('/', [FamiliarController::class, 'showChangePassword'])
    ->name('view');

Route::patch('/', [FamiliarController::class, 'changePassword'])
    ->name('update');
