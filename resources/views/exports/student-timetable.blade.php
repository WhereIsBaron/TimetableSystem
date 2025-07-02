<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Timetable</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; word-wrap: break-word; }
        th { background-color: #f1f1f1; font-size: 12px; }
        .day-cell { background-color: #f9f9f9; font-weight: bold; }
        .slot-header { background-color: #e9e9e9; font-weight: bold; text-align: center; font-size: 11px; }
        .entry { margin-bottom: 4px; padding: 4px; border: 1px solid #ccc; background-color: #f5f5f5; }
        .no-entry { color: #999; font-style: italic; }
    </style>
</head>
<body>
    <h2>Timetable for {{ $studentName }} (Class: {{ $classCode }})</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 100px;">Day</th>
                @foreach ($slotTimes as $slot => $label)
                    <th>
                        Slot {{ $slot }}<br>
                        <small>{{ $label }}</small>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($weekDays as $day)
                <tr>
                    <th class="day-cell">{{ $day }}</th>
                    @foreach ($slotTimes as $slot => $label)
                        <td>
                            @php
                                $entries = $timetables->filter(fn($e) => $e->day === $day && $e->slot_number == $slot);
                            @endphp

                            @if ($entries->isNotEmpty())
                                @foreach ($entries as $entry)
                                    <div class="entry">
                                        <strong>Module:</strong> {{ $entry->module_name }}<br>
                                        <strong>Lecturer:</strong> {{ optional($entry->lecturer)->full_name ?? 'N/A' }}<br>
                                        <strong>Class:</strong> {{ optional($entry->classCode)->code ?? 'N/A' }}<br>
                                        <strong>Time:</strong> {{ \Carbon\Carbon::parse($entry->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($entry->end_time)->format('H:i') }}<br>
                                        <strong>Venue:</strong> {{ $entry->venue }}
                                    </div>
                                @endforeach
                            @else
                                <div class="no-entry">No entries</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
