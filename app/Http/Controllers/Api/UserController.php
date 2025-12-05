<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            User::orderBy('id', 'desc')->get(),
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'job_id' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'role' => 'required|string|in:user,admin',
            'status' => 'required|string|in:pending,active',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'job_id' => $request->job_id,
            'department' => $request->department,
            'designation' => $request->designation,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'job_id' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'role' => 'required|string|in:user,admin',
            'status' => 'required|string|in:pending,active',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->job_id = $request->job_id;
        $user->department = $request->department;
        $user->designation = $request->designation;
        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);
    }
}
