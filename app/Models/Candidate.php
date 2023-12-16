<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidate';

    protected $fillable = [
        'election_id',
        'fname',
        'lname',
        'image',
        'email',
        'phone',
        'status',
    ];

    protected $appends = [
        'total_votes'
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }
}
