<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_id' => 'required|string|unique:users',
            'class_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'class_code' => $request->class_code,
            'account_id' => $request->account_id ?? null,
            'role' => $request->role ?? 'Student',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            // ✅ Direct role-based redirect
            switch ($user->role) {
                case 'is_admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
                case 'is_facultyAdmin':
                    return redirect()->route('faculty.dashboard')->with('success', 'Welcome, Faculty Admin!');
                case 'Lecturer':
                    return redirect()->route('lecturer.dashboard')->with('success', 'Welcome, Lecturer!');
                default:
                    return redirect()->route('student.dashboard')->with('success', 'Welcome, Student!');
            }            
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Logout User
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
