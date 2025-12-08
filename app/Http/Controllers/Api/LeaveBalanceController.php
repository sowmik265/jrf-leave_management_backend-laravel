<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\UserLeaveBalance;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    /**
     * Fetch all leave balances for a specific user.
     */
    public function getLeaveBalances($userId)
    {
        $balances = UserLeaveBalance::with('leaveType')
            ->where('user_id', $userId)
            ->where('year', date('Y'))
            ->get();

        return response()->json($balances, 200);
    }

    /**
     * Assign or update leave balances for a user.
     */
    public function assignLeaveBalance(Request $request, $userId)
    {
        // Input validation
        $request->validate([
            'balances' => 'required|array|min:1',
            'balances.*.leave_type_id' => 'required|exists:leave_types,id',
            'balances.*.total_quota' => 'required|integer|min:0',
        ]);

        // Ensure user exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $currentYear = date('Y');

        foreach ($request->balances as $item) {

            $totalQuota = $item['total_quota'];

            // Check existing balance
            $existing = UserLeaveBalance::where([
                'user_id' => $userId,
                'leave_type_id' => $item['leave_type_id'],
                'year' => $currentYear,
            ])->first();

            if ($existing) {
                // Update existing balance and recalculate remaining
                $existing->update([
                    'total_quota' => $totalQuota,
                    'remaining_quota' => $totalQuota - $existing->used_quota,
                ]);
            } else {
                // Create new leave balance record
                UserLeaveBalance::create([
                    'user_id' => $userId,
                    'leave_type_id' => $item['leave_type_id'],
                    'total_quota' => $totalQuota,
                    'used_quota' => 0,
                    'remaining_quota' => $totalQuota,
                    'year' => $currentYear,
                ]);
            }
        }

        return response()->json([
            'message' => 'Leave balances updated successfully.'
        ], 200);
    }
}
