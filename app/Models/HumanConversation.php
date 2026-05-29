<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanConversation extends Model
{
    use HasFactory;

    protected $table = 'human_conversations';

    protected $fillable = [
        'counselor_id',
        'token',
        'alias',
        'is_flagged',
        'risk_level',
        'status'
    ];

    /**
     * Get the counselor assigned to this human conversation.
     * Maps to the counselors table from the database configuration.
     */
    public function counselor()
    {
        return $this->belongsTo(Counselor::class, 'counselor_id');
    }

    /**
     * Get all separated chat messages belonging to this live human chat.
     */
    public function messages()
    {
        return $this->hasMany(HumanMessage::class, 'human_conversation_id');
    }

    /**
     * Get the historical session logging updates linked to this chat room record.
     */
    public function logs()
    {
        return $this->hasMany(HumanCounselorLog::class, 'human_conversation_id');
    }
}
