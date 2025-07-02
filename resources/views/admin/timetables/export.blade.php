<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Timetables</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Timetables Export</h2>
    <table>
        <thead>
            <tr>
                <th>Class</th>
                <th>Instructor</th>
                <th>Room</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timetables as $entry)
                <tr>
                    <td>{{ $entry->class_code }}</td>
                    <td>{{ $entry->instructor_name }}</td>
                    <td>{{ $entry->room }}</td>
                    <td>{{ $entry->day_of_week }}</td>
                    <td>{{ $entry->start_time }}</td>
                    <td>{{ $entry->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>