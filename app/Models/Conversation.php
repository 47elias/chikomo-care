<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicitly bound to ensure alignment with your database architecture layout.
     *
     * @var string
     */
    protected $table = 'conversations';

    /**
     * The attributes that are mass assignable.
     * Updated to handle counselor matching metrics securely.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'counselor_id',
        'token',
        'alias',
        'status',
        'is_flagged',
        'risk_level',
        'is_human_request',
    ];

    /**
     * The attributes that should be cast to native types.
     * Ensures booleans and timestamps are evaluated with data integrity.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_flagged' => 'boolean',
        'is_human_request' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the messages associated with this tracked interaction pipeline.
     * Defines strict one-to-many relationship mapping back to structural message threads.
     *
    * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    /**
     * Get the historical session timeline tracking indices for this conversation.
     *
    * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(CounselorLog::class, 'conversation_id', 'id');
    }

    /**
     * Get the administrative counselor profile assigned to guide this chat pipeline session.
     * Maps back securely directly to the system users collection table schema.
     *
    * @return BelongsTo
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id', 'id');
    }
}
