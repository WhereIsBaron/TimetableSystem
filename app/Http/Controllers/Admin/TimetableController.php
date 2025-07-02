<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\User;
use App\Models\ClassCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::with(['classCode', 'lecturer'])->orderBy('day')->orderBy('slot_number')->get();
        return view('admin.timetables.TimetableManagement', compact('timetables'));
    }

    public function create()
    {
        $lecturers = User::where('role', 'Lecturer')->select('id', 'full_name')->get();
        $classCodes = ClassCode::select('id', 'code')->get();
        return view('admin.timetables.create', compact('lecturers', 'classCodes'));
    }

    public function edit($id)
    {
        $timetable = Timetable::with(['classCode', 'lecturer'])->findOrFail($id);
        $lecturers = User::where('role', 'Lecturer')->select('id', 'full_name')->get();
        $classCodes = ClassCode::select('id', 'code')->get();
        return view('admin.timetables.edit', compact('timetable', 'lecturers', 'classCodes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $baseRoute = $user->role === 'is_facultyAdmin' ? 'faculty' : 'admin';

        if ($request->filled(['day', 'slot_number', 'module_name'])) {
            $validated = $request->validate($this->rulesSingle());
            $validated['start_time'] = $this->trimTime($validated['start_time']);
            $validated['end_time'] = $this->trimTime($validated['end_time']);

            $classCode = ClassCode::firstOrCreate(['code' => $validated['class_code']]);
            $validated['class_code_id'] = $classCode->id;
            unset($validated['class_code']);

            $lecturer = User::firstOrCreate(
                ['full_name' => $validated['lecturer_name'], 'role' => 'Lecturer'],
                ['name' => $validated['lecturer_name']]
            );
            $validated['user_id'] = $lecturer->id;
            unset($validated['lecturer_name']);

            Timetable::create($validated);

            return redirect()->route("$baseRoute.timetables.index")->with('success', 'Timetable slot created successfully.');
        }

        $entries = $request->input('entries', []);
        $savedCount = 0;

        foreach ($entries as $day => $slots) {
            if (!in_array($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) continue;

            foreach ($slots as $slot => $entry) {
                if (!is_numeric($slot)) continue;

                if (
                    empty($entry['module_name']) &&
                    empty($entry['venue']) &&
                    empty($entry['start_time']) &&
                    empty($entry['end_time']) &&
                    empty($entry['class_code']) &&
                    empty($entry['lecturer_name'])
                ) continue;

                try {
                    $validated = validator(array_merge($entry, [
                        'day' => $day,
                        'slot_number' => (int)$slot,
                    ]), $this->rulesMatrix())->validate();

                    $validated['start_time'] = $this->trimTime($validated['start_time']);
                    $validated['end_time'] = $this->trimTime($validated['end_time']);

                    $classCode = ClassCode::firstOrCreate(['code' => $validated['class_code']]);
                    $validated['class_code_id'] = $classCode->id;
                    unset($validated['class_code']);

                    $lecturer = User::firstOrCreate(
                        ['full_name' => $validated['lecturer_name'], 'role' => 'Lecturer'],
                        ['name' => $validated['lecturer_name']]
                    );
                    $validated['user_id'] = $lecturer->id;
                    unset($validated['lecturer_name']);

                    Timetable::create($validated);
                    $savedCount++;
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Validation failed for matrix entry', [
                        'day' => $day,
                        'slot' => $slot,
                        'errors' => $e->errors()
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Error saving matrix entry', [
                        'day' => $day,
                        'slot' => $slot,
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        }

        if ($savedCount > 0) {
            return redirect()->route("$baseRoute.timetables.index")->with('success', "$savedCount entries created successfully.");
        }

        return redirect()->back()->withErrors('Invalid submission. Please fill either a single slot or the weekly matrix.');
    }

    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);
        $validated = $request->validate($this->rulesSingle());

        $validated['start_time'] = $this->trimTime($validated['start_time']);
        $validated['end_time'] = $this->trimTime($validated['end_time']);

        $classCode = ClassCode::firstOrCreate(['code' => $validated['class_code']]);
        $validated['class_code_id'] = $classCode->id;
        unset($validated['class_code']);

        $lecturer = User::firstOrCreate(
            ['full_name' => $validated['lecturer_name'], 'role' => 'Lecturer'],
            ['name' => $validated['lecturer_name']]
        );
        $validated['user_id'] = $lecturer->id;
        unset($validated['lecturer_name']);

        $timetable->update($validated);

        $baseRoute = Auth::user()->role === 'is_facultyAdmin' ? 'faculty' : 'admin';

        return redirect()->route("$baseRoute.timetables.index")->with('success', 'Timetable updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $timetable = Timetable::findOrFail($id);
            $timetable->delete();

            $baseRoute = Auth::user()->role === 'is_facultyAdmin' ? 'faculty' : 'admin';

            return redirect()->route("$baseRoute.timetables.index")->with('success', 'Timetable deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Failed to delete timetable', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withErrors('Failed to delete timetable. Please try again.');
        }
    }

    protected function rulesSingle(): array
    {
        return [
            'day'            => 'required|string|max:20',
            'slot_number'    => 'required|integer|min:1|max:20',
            'venue'          => 'required|string|max:255',
            'module_name'    => 'required|string|max:255',
            'class_code'     => 'required|string|max:50',
            'start_time'     => 'required|string|max:10',
            'end_time'       => 'required|string|max:10',
            'lecturer_name'  => 'required|string|max:255',
        ];
    }

    protected function rulesMatrix(): array
    {
        return $this->rulesSingle();
    }

    private function trimTime(string $time): string
    {
        return preg_replace('/^(\d{1,2}:\d{2})(:\d{2})?$/', '$1', $time);
    }
}
