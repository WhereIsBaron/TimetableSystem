@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('partials.flash')

    @php
        $isFaculty = auth()->user()->role === 'is_facultyAdmin';
        $baseRoute = $isFaculty ? 'faculty' : 'admin';
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>✏️ Edit Timetable Entry</h2>
        <div>
            <a href="{{ url("/$baseRoute/dashboard") }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <a href="{{ route("$baseRoute.timetables.index") }}" class="btn btn-outline-primary">
                <i class="bi bi-table"></i> View Timetables
            </a>
        </div>
    </div>

    <div class="card rounded-4 shadow-sm p-4 dark:bg-dark text-dark-emphasis dark:text-light">
        <form method="POST" action="{{ route("$baseRoute.timetables.update", $timetable->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold">Editing Slot:</label>
                <div class="form-control bg-light">
                    Slot {{ $timetable->slot_number }} — {{ $timetable->day }}
                </div>
                <input type="hidden" name="slot_number" value="{{ $timetable->slot_number }}">
            </div>

            <div class="row">
                {{-- Class Code --}}
                <div class="mb-3 col-md-6">
                    <label for="class_code" class="form-label">Class Code</label>
                    <input type="text" name="class_code" id="class_code"
                        value="{{ old('class_code', $timetable->classCode->code ?? '') }}"
                        class="form-control @error('class_code') is-invalid @enderror"
                        list="classCodes">
                    @error('class_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Lecturer Name --}}
                <div class="mb-3 col-md-6">
                    <label for="lecturer_name" class="form-label">Lecturer</label>
                    <input type="text" name="lecturer_name" id="lecturer_name"
                        value="{{ old('lecturer_name', $timetable->lecturer->full_name ?? '') }}"
                        class="form-control @error('lecturer_name') is-invalid @enderror"
                        list="lecturers">
                    @error('lecturer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Module --}}
                <div class="mb-3 col-md-6">
                    <label for="module_name" class="form-label">Module Name</label>
                    <input type="text" name="module_name" id="module_name"
                        value="{{ old('module_name', $timetable->module_name) }}"
                        class="form-control @error('module_name') is-invalid @enderror">
                    @error('module_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Venue --}}
                <div class="mb-3 col-md-6">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" name="venue" id="venue"
                        value="{{ old('venue', $timetable->venue) }}"
                        class="form-control @error('venue') is-invalid @enderror">
                    @error('venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Start Time --}}
                <div class="mb-3 col-md-6">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" name="start_time" id="start_time"
                        value="{{ old('start_time', $timetable->start_time) }}"
                        class="form-control @error('start_time') is-invalid @enderror">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- End Time --}}
                <div class="mb-3 col-md-6">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" name="end_time" id="end_time"
                        value="{{ old('end_time', $timetable->end_time) }}"
                        class="form-control @error('end_time') is-invalid @enderror">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Day (readonly) --}}
                <div class="mb-3 col-md-6">
                    <label for="day" class="form-label">Day</label>
                    <input type="text" readonly class="form-control" name="day" id="day" value="{{ $timetable->day }}">
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update Timetable
                </button>
            </div>
        </form>
    </div>

    {{-- DATALISTS --}}
    <datalist id="classCodes">
        @foreach($classCodes as $classCode)
            <option value="{{ $classCode->code }}"></option>
        @endforeach
    </datalist>

    <datalist id="lecturers">
        @foreach($lecturers as $lecturer)
            <option value="{{ $lecturer->full_name }}"></option>
        @endforeach
    </datalist>
</div>
@endsection
