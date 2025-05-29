<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    use HasFactory;

    // Specify which fields are mass assignable
    protected $fillable = [
        'team_id',
        'email',
        'token',
        'status',
    ];

    // Optional: Define relationship to Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

