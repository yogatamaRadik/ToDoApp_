<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua rute aplikasi ToDo kamu terdaftar di sini.
| Rute "/" langsung diarahkan ke halaman utama ToDo (index).
|
*/

Route::get('/', function () {
    return view('home');
});

// Halaman utama ToDo
Route::get('/', [TodoController::class, 'index'])->name('home');

// Tambah tugas
Route::post('/add-task', [TodoController::class, 'store'])->name('add-task');

// Update status tugas (jika nanti kamu tambahkan fitur update)
Route::patch('/update-task/{id}', [TodoController::class, 'update'])->name('update-task');

// Hapus tugas
Route::delete('/delete-task/{id}', [TodoController::class, 'destroy'])->name('delete-task');
