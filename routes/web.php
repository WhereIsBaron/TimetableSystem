<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Admin\TimetableController as AdminTimetableController;
use App\Http\Controllers\Faculty\TimetableController as FacultyTimetableController;
use App\Http\Controllers\Lecturer\LecturerTimetableController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\Admin\DevToolsController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\FacultyAdminMiddleware;

// Show Authentication Forms
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::get('/login', fn() => view('auth.login'))->name('login');

// Handle Authentication Requests
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ✅ Theme Toggle Route
Route::post('/toggle-theme', function (Request $request) {
    session(['theme' => $request->theme]);
    return response()->json(['status' => 'ok']);
});

// Dashboard Routes (Protected with Auth Middleware)
Route::middleware(['auth'])->group(function () {

    // ✅ Role-based redirection
    Route::get('/dashboard', function () {
        if (!Auth::check()) return redirect('/login');

        $role = Auth::user()->role;

        return match ($role) {
            'is_admin' => redirect()->route('admin.dashboard'),
            'is_facultyAdmin' => redirect()->route('faculty.dashboard'),
            'Lecturer' => \App\Http\Controllers\Lecturer\LecturerTimetableController::showDashboardView(),
            default => redirect()->route('student.dashboard'),
        };
    })->name('dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

    // ✅ Admin Timetable + Management Routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('timetables', AdminTimetableController::class);
        Route::get('timetables-export', [AdminTimetableController::class, 'exportCsv'])->name('timetables.export');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Dev Tools
        Route::prefix('devtools')->name('devtools.')->group(function () {
            Route::get('/', [DevToolsController::class, 'index'])->name('index');
            Route::post('/auto-generate', [DevToolsController::class, 'autoGenerate'])->name('autoGenerate');
            Route::get('/detect-conflicts', [DevToolsController::class, 'detectConflicts'])->name('detectConflicts'); // changed from POST to GET
        });
    });

    // Faculty Admin Dashboard
    Route::get('/faculty/dashboard', fn() => view('faculty.facultydashboard'))
        ->middleware(FacultyAdminMiddleware::class)
        ->name('faculty.dashboard');

    // ✅ Faculty Admin Timetable Routes
    Route::middleware([FacultyAdminMiddleware::class])->prefix('faculty')->name('faculty.')->group(function () {
        Route::resource('timetables', FacultyTimetableController::class)->except(['update']);
        Route::put('timetables/{timetable}', [FacultyTimetableController::class, 'update'])->name('timetables.update');
        Route::get('timetables-export', [FacultyTimetableController::class, 'exportCsv'])->name('timetables.export');
    });

    // ✅ Student Dashboard using Controller (loads timetable by class code)
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/timetables-export', [StudentDashboardController::class, 'export'])->name('student.timetables.export');

    // ✅ Shared Class Timetable View (used by any role)
    Route::get('/timetables/class-view', [AdminTimetableController::class, 'classView'])->name('timetables.classView');

    // ✅ Lecturer Timetable Routes
    Route::prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/timetables', [LecturerTimetableController::class, 'index'])->name('timetables.index');
        Route::get('/dashboard', [LecturerTimetableController::class, 'showDashboardView'])->name('dashboard');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Optional test route
Route::get('/test-admin-middleware', fn() => "✔️ You passed the admin middleware.")
    ->middleware(['web', 'auth', AdminMiddleware::class]);
