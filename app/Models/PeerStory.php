<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeerStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_alias',
        'title',
        'content',
        'is_approved',
        'rating_average',
        'total_ratings_count'
    ];
}
