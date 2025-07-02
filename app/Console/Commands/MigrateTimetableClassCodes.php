<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Timetable;
use App\Models\ClassCode;

class MigrateTimetableClassCodes extends Command
{
    protected $signature = 'migrate:classcodes';

    protected $description = 'Migrate string class_code in timetables to class_code_id using class_codes table';

    public function handle()
    {
        $timetables = Timetable::whereNotNull('class_code')->get();
        $updatedCount = 0;

        foreach ($timetables as $timetable) {
            $code = trim(preg_replace('/\s+/', '', $timetable->class_code)); // normalize e.g., "BSSM 3100" → "BSSM3100"

            $classCode = ClassCode::whereRaw("REPLACE(code, ' ', '') = ?", [$code])->first();

            if ($classCode) {
                $timetable->class_code_id = $classCode->id;
                $timetable->save();

                $updatedCount++;
                $this->info("Updated timetable ID {$timetable->id} with class_code_id = {$classCode->id}");
            } else {
                $this->warn("No match for timetable ID {$timetable->id} with class_code = {$timetable->class_code}");
            }
        }

        $this->info("✅ Migration complete. {$updatedCount} timetables updated.");
    }
}
