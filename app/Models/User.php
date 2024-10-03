<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_photo_path',
        'role',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function instructedCourses(): HasMany
{
    return $this->hasMany(Course::class, 'instructor_id');
}

public function calculateProgress(User $user)
{
    $totalLessons = $this->lessons()->count();
    $completedLessons = $this->lessons()
        ->whereHas('progress', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed');
        })
        ->count();

    return $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
}
}
