<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Timetable</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>My Timetable - {{ auth()->user()->full_name }}</h2>
    <p><strong>Class Code:</strong> {{ auth()->user()->class_code ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Slot</th>
                <th>Time</th>
                <th>Module</th>
                <th>Lecturer</th>
                <th>Venue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timetables as $entry)
                <tr>
                    <td>{{ $entry->day }}</td>
                    <td>{{ $entry->slot_number }}</td>
                    <td>{{ $entry->start_time }} - {{ $entry->end_time }}</td>
                    <td>{{ $entry->module_name }}</td>
                    <td>{{ optional($entry->lecturer)->full_name ?? 'N/A' }}</td>
                    <td>{{ $entry->venue }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
