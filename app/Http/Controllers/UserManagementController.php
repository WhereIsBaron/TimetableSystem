<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index()
    {
        $authId = Auth::id();

        $users = User::when($authId, function ($query) use ($authId) {
            return $query->where('id', '!=', $authId);
        })->orderBy('class_code')->get();

        $groupedUsers = $users->groupBy(function ($user) {
            return $user->class_code ?? 'Unassigned';
        });

        return view('admin.users.usermanagement', compact('groupedUsers'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'student_id'  => 'nullable|string|max:50',
            'class_code'  => 'nullable|string|max:50',
            'role'        => 'required|in:is_admin,is_facultyAdmin,Lecturer,Student',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'student_id'  => 'nullable|string|max:50',
            'class_code'  => 'nullable|string|max:50',
            'role'        => 'required|in:is_admin,is_facultyAdmin,Lecturer,Student',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
