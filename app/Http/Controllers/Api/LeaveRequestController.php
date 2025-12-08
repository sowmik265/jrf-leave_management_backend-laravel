<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of all leave requests for the admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Fetch all leave requests with user and leave type information
        $leaveRequests = LeaveRequest::with('user', 'leaveType')->get();

        // Return the data as a JsonResponse
        return response()->json($leaveRequests);
    }

    /**
     * Display the specified leave request details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Find the leave request by ID, with user and leave type
        $leaveRequest = LeaveRequest::with('user', 'leaveType')->findOrFail($id);

        return response()->json($leaveRequest);
    }

    /**
     * Update the status of the leave request (approve/reject).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        // Validate the incoming status
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Find the leave request by ID
        $leaveRequest = LeaveRequest::findOrFail($id);

        // Update the status of the leave request
        $leaveRequest->status = $request->status;
        $leaveRequest->save();

        // Respond with the updated leave request
        return response()->json($leaveRequest);
    }
}
