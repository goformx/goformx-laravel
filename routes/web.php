<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('forms/{id}', [PublicFormController::class, 'show'])->name('forms.fill');

Route::get('demo', DemoController::class)->name('demo');

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('forms', [FormController::class, 'index'])->name('forms.index');
    Route::post('forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('forms/{id}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::get('forms/{id}/preview', [FormController::class, 'preview'])->name('forms.preview');
    Route::get('forms/{id}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
    Route::get('forms/{id}/submissions/{sid}', [FormController::class, 'submission'])->name('forms.submissions.show');
    Route::get('forms/{id}/embed', [FormController::class, 'embed'])->name('forms.embed');
    Route::put('forms/{id}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('forms/{id}', [FormController::class, 'destroy'])->name('forms.destroy');
});

require __DIR__.'/settings.php';
