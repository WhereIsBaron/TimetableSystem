<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Timetable;

class DetectTimetableConflicts extends Command
{
    protected $signature = 'timetable:detect-conflicts';
    protected $description = 'Detect conflicting class schedules by room, instructor, or time';

    public function handle()
    {
        $conflicts = Timetable::all()
            ->groupBy(function ($t) {
                return $t->room . '|' . $t->day . '|' . $t->start_time;
            })
            ->filter(function ($group) {
                return $group->count() > 1;
            });

        if ($conflicts->isEmpty()) {
            $this->info('✅ No conflicts found.');
        } else {
            $this->warn("⚠️ Conflicts found:");

            foreach ($conflicts as $key => $group) {
                [$room, $day, $time] = explode('|', $key);
                $this->line("Room: $room | Day: $day | Time: $time");

                foreach ($group as $t) {
                    $this->line("- Class: {$t->class_code} | Instructor: {$t->instructor}");
                }

                $this->line(""); // spacing
            }
        }

        return Command::SUCCESS;
    }
}
