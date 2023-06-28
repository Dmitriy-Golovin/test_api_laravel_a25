<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkReport extends Model
{
    use HasFactory;

    const STATUS_UNPAID = 1;
    const STATUS_PAID = 2;
    const RATE_HOUR = 500;

    protected $fillable = [
        'employee_id',
        'hours',
        'status'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'hours' => $this->hours,
            'status' => $this->status,
            'employee' => $this->employee->serialize(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
