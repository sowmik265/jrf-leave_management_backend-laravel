<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types.
     */
    public function index()
    {
        return response()->json(
            LeaveType::orderBy('id', 'desc')->get(),
            200
        );
    }

    /**
     * Store a newly created leave type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name',
            'description' => 'nullable|string',
            'max_days_per_year' => 'required|integer|min:0',
            'carry_forward' => 'required|boolean',
            'requires_document' => 'required|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $leaveType = LeaveType::create($validated);

        return response()->json([
            'message' => 'Leave type created successfully',
            'data' => $leaveType,
        ], 201);
    }

    /**
     * Update the specified leave type.
     */
    public function update(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
            'max_days_per_year' => 'required|integer|min:0',
            'carry_forward' => 'required|boolean',
            'requires_document' => 'required|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $leaveType->update($validated);

        return response()->json([
            'message' => 'Leave type updated successfully',
            'data' => $leaveType
        ], 200);
    }

    /**
     * Remove the specified leave type.
     */
    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $leaveType->delete();

        return response()->json([
            'message' => 'Leave type deleted successfully'
        ], 200);
    }
}
