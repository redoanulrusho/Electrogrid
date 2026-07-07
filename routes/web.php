<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login'); // Redirect home to login for quick preview
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Simulated successful login - redirects to grid admin dashboard
    return redirect()->route('admin.dashboard');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    // Simulated successful registration - redirects to login with flash success alert message
    return redirect()->route('login')->with('success', 'Terminal Node mapped successfully to Feeder Node!');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


