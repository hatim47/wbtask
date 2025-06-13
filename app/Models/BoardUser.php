<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardUser extends Model
{
    protected $table = 'board_user'; // Because the table name isn't plural
    protected $fillable = ['user_id', 'board_id','team_role','board_role', 'status' ];
    public $timestamps = true; // if you're using created_at/updated_at

public function users()
{
    return $this->belongsToMany(User::class, 'board_user','board_id','user_id')
                ->withPivot('status')
                ->withTimestamps();
}
}
