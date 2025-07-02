<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Timetable</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; word-wrap: break-word; }
        th { background-color: #f1f1f1; }
        .day-cell { background-color: #f8f9fa; font-weight: bold; width: 100px; }
        .slot-header { background-color: #f1f3f5; font-weight: bold; text-align: center; }
        .entry { margin-bottom: 6px; padding: 4px; border: 1px solid #ccc; background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>📅 Timetable for {{ $studentName }} (Class: {{ $classCode }})</h2>

    <table>
        <thead>
            <tr>
                <th class="day-cell">Day</th>
                @foreach ($slotTimes as $slot => $label)
                    <th class="slot-header">
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
                                $entries = $timetables->filter(fn($entry) => $entry->day === $day && $entry->slot_number == $slot);
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
                                <em>No entries</em>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
