<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'team_id',
        'pattern',
        'image_path',
    ];





    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function columns() {
        return $this->hasMany(Column::class);
    }
    public function boardUsers()
{
    return $this->hasMany(BoardUser::class);
}
public function users()
{
    return $this->belongsToMany(User::class, 'board_user')
                ->withPivot('status')
                ->withTimestamps();
}

}
