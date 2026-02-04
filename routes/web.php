<?php

use App\Http\Controllers\ExportController;
use App\Livewire\PassportCapture;
use Illuminate\Support\Facades\Route;

Route::get('/', PassportCapture::class)->name('capture');

Route::get('/export/xlsx', [ExportController::class, 'xlsx'])->name('export.xlsx');
Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');
