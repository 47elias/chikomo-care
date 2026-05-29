<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanCounselorLog extends Model
{
    use HasFactory;

    protected $table = 'human_counselor_logs';

    protected $fillable = [
        'human_conversation_id',
        'counselor_id',
        'session_started_at',
        'session_ended_at',
        'summary_notes'
    ];

    /**
     * Get the human conversation track record reference linked to this entry line.
     */
    public function conversation()
    {
        return $this->belongsTo(HumanConversation::class, 'human_conversation_id');
    }

    /**
     * Get the specific specialist profiles managing this archived case item row.
     */
    public function counselor()
    {
        return $this->belongsTo(Counselor::class, 'counselor_id');
    }
}
