<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function index()
    {
        $classes = CourseClass::with('schedules')->get();
        return response()->json($classes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_code' => 'required|string|unique:classes',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1'
        ]);

        $class = CourseClass::create($validated);
        return response()->json($class, 201);
    }

    public function show(CourseClass $class)
    {
        return response()->json($class->load('schedules'));
    }

    public function update(Request $request, CourseClass $class)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
            'capacity' => 'integer|min:1'
        ]);

        $class->update($validated);
        return response()->json($class);
    }

    public function destroy(CourseClass $class)
    {
        $class->delete();
        return response()->json(null, 204);
    }
}