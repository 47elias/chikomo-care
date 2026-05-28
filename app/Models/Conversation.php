<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'alias',
        'is_flagged',
        'risk_level',
    ];

    /**
     * The attributes that should be cast to native types.
     * Ensures booleans and timestamps are evaluated with data integrity.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_flagged' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the messages associated with this tracked interaction pipeline.
     * Defines strict one-to-many relationship mapping back to structural message threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }
}
