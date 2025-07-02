<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Http\Request;

class DevToolsController extends Controller
{
    public function index()
    {
        return view('admin.devtools.index');
    }

    public function autoGenerate()
    {
        try {
            \Artisan::call('timetable:auto-generate');
            return redirect()->back()->with('success', 'Timetables generated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate timetables: ' . $e->getMessage());
        }
    }

    public function detectConflicts()
    {
        $timetables = Timetable::orderBy('day')->orderBy('start_time')->get();

        $conflicts = collect();

        $grouped = $timetables->groupBy(fn($entry) => "{$entry->day}_{$entry->venue}");

        foreach ($grouped as $groupKey => $entries) {
            $entries = $entries->sortBy('start_time')->values();

            for ($i = 0; $i < $entries->count(); $i++) {
                for ($j = $i + 1; $j < $entries->count(); $j++) {
                    $a = $entries[$i];
                    $b = $entries[$j];

                    $aStart = strtotime($a->start_time);
                    $aEnd = strtotime($a->end_time);
                    $bStart = strtotime($b->start_time);
                    $bEnd = strtotime($b->end_time);

                    // Check for exact clash or overlapping
                    if (
                        ($bStart >= $aStart && $bStart < $aEnd) ||
                        ($aStart >= $bStart && $aStart < $bEnd)
                    ) {
                        $conflicts->push(compact('a', 'b'));
                    }
                }
            }
        }

        return view('admin.devtools.conflicts', compact('conflicts'));
    }
}
