<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class); // A leave request belongs to one user
    }

    // Define the relationship with the LeaveType model
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class); // A leave request belongs to one leave type
    }

    // Custom method to check if leave is approved or rejected
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
