<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $table = 'election';

    protected $fillable = [
        'title',
        'image',
        'status',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'election_id');
    }


}
