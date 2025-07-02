<div class="row mb-3">
    <div class="col-md-6">
        <label for="class_code" class="form-label">Class Code</label>
        <input 
            type="text" 
            name="class_code" 
            id="class_code" 
            class="form-control shadow-sm" 
            value="{{ old('class_code', $timetable->classCode->code ?? '') }}" 
            list="classCodes"
            required
        >
        <datalist id="classCodes">
            @foreach ($classCodes as $classCode)
                <option value="{{ $classCode->code }}"></option>
            @endforeach
        </datalist>
    </div>

    <div class="col-md-6">
        <label for="lecturer_name" class="form-label">Lecturer</label>
        <input 
            type="text" 
            name="lecturer_name" 
            id="lecturer_name" 
            class="form-control shadow-sm" 
            value="{{ old('lecturer_name', $timetable->lecturer_name ?? $timetable->lecturer->full_name ?? '') }}" 
            list="lecturerList"
            placeholder="Type to search lecturer..." 
            autocomplete="off" 
            required
        >
        <datalist id="lecturerList">
            @foreach ($lecturers as $lecturer)
                <option value="{{ $lecturer->full_name }}"></option>
            @endforeach
        </datalist>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="module_name" class="form-label">Module Name</label>
        <input 
            type="text" 
            name="module_name" 
            id="module_name" 
            class="form-control shadow-sm" 
            value="{{ old('module_name', $timetable->module_name ?? '') }}" 
            required
        >
    </div>

    <div class="col-md-6">
        <label for="venue" class="form-label">Venue</label>
        <input 
            type="text" 
            name="venue" 
            id="venue" 
            class="form-control shadow-sm" 
            value="{{ old('venue', $timetable->venue ?? '') }}" 
            required
        >
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="day" class="form-label">Day</label>
        <select name="day" id="day" class="form-select shadow-sm" required>
            <option value="">Select Day</option>
            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                <option value="{{ $day }}" {{ (old('day', $timetable->day ?? '') == $day) ? 'selected' : '' }}>
                    {{ $day }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="slot_number" class="form-label">Slot Number (1–20)</label>
        <select name="slot_number" id="slot_number" class="form-select shadow-sm" required>
            <option value="">Select Slot</option>
            @for ($i = 1; $i <= 20; $i++)
                <option value="{{ $i }}" {{ (old('slot_number', $timetable->slot_number ?? '') == $i) ? 'selected' : '' }}>
                    Slot {{ $i }}
                </option>
            @endfor
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="start_time" class="form-label">Start Time</label>
        <input 
            type="time" 
            name="start_time" 
            id="start_time" 
            class="form-control shadow-sm" 
            value="{{ old('start_time', $timetable->start_time ?? '') }}" 
            required
        >
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="end_time" class="form-label">End Time</label>
        <input 
            type="time" 
            name="end_time" 
            id="end_time" 
            class="form-control shadow-sm" 
            value="{{ old('end_time', $timetable->end_time ?? '') }}" 
            required
        >
    </div>
</div>
