<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanMessage extends Model
{
    use HasFactory;

    protected $table = 'human_messages';

    protected $fillable = [
        'human_conversation_id',
        'content',
        'sender_type'
    ];

    /**
     * Get the isolated conversation stream that this human message belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(HumanConversation::class, 'human_conversation_id');
    }
}
