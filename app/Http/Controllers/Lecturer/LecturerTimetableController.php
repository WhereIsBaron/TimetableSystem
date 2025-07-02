<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Timetable;

class LecturerTimetableController extends Controller
{
    protected function getSlots(): array
    {
        return [
            1 => ['start' => '08:00', 'end' => '09:30'],
            2 => ['start' => '10:00', 'end' => '11:30'],
            3 => ['start' => '12:00', 'end' => '13:30'],
            4 => ['start' => '14:00', 'end' => '15:30'],
        ];
    }

    public function index(): View
    {
        $userId = Auth::id();

        $timetables = Timetable::where('user_id', $userId)
            ->orderBy('day')
            ->orderBy('slot_number')
            ->get();

        return view('lecturer.timetables.dashboard', [
            'timetables' => $timetables,
            'slots' => $this->getSlots(),
        ]);
    }

    public static function showDashboardView()
    {
        $userId = Auth::id();

        $timetables = Timetable::where('user_id', $userId)
            ->orderBy('day')
            ->orderBy('slot_number')
            ->get();

        $controller = new self();

        return view('lecturer.timetables.dashboard', [
            'timetables' => $timetables,
            'slots' => $controller->getSlots(),
        ]);
    }
}
