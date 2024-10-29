<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Activity extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'activities';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'activity_date',
        'points',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
