<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Admin\TimetableController as AdminTimetableController;
use App\Models\Timetable;
use App\Models\User;
use App\Models\ClassCode;
use Illuminate\Http\Request;

class TimetableController extends AdminTimetableController
{
    protected string $baseRoute = 'faculty';

    public function create()
    {
        $lecturers = User::where('role', 'Lecturer')->select('id', 'full_name')->get();
        $classCodes = ClassCode::select('id', 'code')->get();
        $baseRoute = $this->baseRoute;

        return view('admin.timetables.create', compact('lecturers', 'classCodes', 'baseRoute'));
    }

    public function edit($id)
    {
        $timetable = Timetable::with(['classCode', 'lecturer'])->findOrFail($id);
        $lecturers = User::where('role', 'Lecturer')->select('id', 'full_name')->get();
        $classCodes = ClassCode::select('id', 'code')->get();
        $baseRoute = $this->baseRoute;

        return view('admin.timetables.edit', compact('timetable', 'lecturers', 'classCodes', 'baseRoute'));
    }
}
