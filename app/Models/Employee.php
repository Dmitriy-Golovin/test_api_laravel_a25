<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function serialize()
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
