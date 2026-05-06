<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselorAssignment extends Model
{
    public function counselor() {
        return $this->belongsTo(Counselor::class);
    }

    public function conversation() {
        return $this->belongsTo(Conversation::class); // Existing conversations table
    }
}
