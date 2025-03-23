<?php

use Illuminate\Support\Facades\Route;

// routes/web.php

use App\Http\Controllers\MemLogController;

Route::get('/fetch-logs', [MemLogController::class, 'fetchLogs'])->name('logs.fetch');  // Route for fetching new logs via AJAX

Route::get('/logs', [MemLogController::class, 'index'])->name('logs.index');  // Route for the index page
    