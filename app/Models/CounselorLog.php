<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'counselor_id',
        'session_started_at',
        'session_ended_at',
        'summary_notes'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
