<?php

// Serves the HTML shell of the JavaScript single-page app; everything else is handled client-side and via routes/api.php.

use Illuminate\Support\Facades\Route;

Route::view('/', 'app');
