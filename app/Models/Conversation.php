<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['token', 'alias', 'is_flagged', 'risk_level'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
