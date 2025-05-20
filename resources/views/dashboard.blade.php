<<<<<<< HEAD
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
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
>>>>>>> d36a851 (Install Breeze)
