<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->class_code) {
            return view('student.dashboard', ['timetables' => collect()]);
        }

        $timetables = Timetable::with(['classCode', 'lecturer'])
            ->whereHas('classCode', fn($q) => $q->where('code', $user->class_code))
            ->orderBy('day')
            ->orderBy('slot_number')
            ->get();

        return view('student.dashboard', compact('timetables'));
    }
}
