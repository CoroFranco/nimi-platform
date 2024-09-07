<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'question',
        'answers',
        'correct_answer',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'answers' => 'array',
    ];

    /**
     * Get the lesson that owns the quiz question.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}