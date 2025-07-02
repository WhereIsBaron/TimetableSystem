<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Show student timetable dashboard.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $classCode = $user->class_code;

        $timetables = Timetable::with(['lecturer', 'classCode'])
            ->whereHas('classCode', fn($q) => $q->where('code', $classCode))
            ->get();

        return view('student.dashboard', compact('timetables'));
    }

    /**
     * Export student timetable as PDF.
     */
    public function export(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $classCode = $user->class_code;

        $timetables = Timetable::with(['lecturer', 'classCode'])
            ->whereHas('classCode', fn($q) => $q->where('code', $classCode))
            ->get();

        $slotTimes = [
            1 => 'Early Morning Lessons',
            2 => 'Late Morning Lessons',
            3 => 'Early Afternoon Lessons',
            4 => 'Late Afternoon Lessons',
        ];

        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $pdf = Pdf::loadView('exports.student-grid-timetable', [
            'timetables' => $timetables,
            'studentName' => $user->full_name,
            'classCode' => $classCode,
            'slotTimes' => $slotTimes,
            'weekDays' => $weekDays,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('My_Timetable.pdf');
    }
}
