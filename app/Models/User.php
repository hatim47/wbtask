<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'image_path',
        'is_active',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

//     public function teams()
//     {
//         return $this->belongsToMany(Team::class, "user_team", "user_id", "team_id")
//             ->withPivot("status");
//     }

//     public function cards()
// {
//     return $this->belongsToMany(CardUser::class, 'card_user', 'user_id', 'card_id');
// }
public function teams()
{
    return $this->belongsToMany(Team::class, 'user_team')->withPivot('status');
}

public function cardUsers()
{
    return $this->hasMany(CardUser::class, 'user_id');
}
public function commentUsers()
{
    return $this->hasMany(CardComment::class, 'user_id');
}

    public function teamRelations()
    {
        return $this->hasMany(UserTeam::class);
    }
    public function boards()
{
    return $this->belongsToMany(Board::class, 'board_user')
                ->withPivot('status')
                ->withTimestamps();
}
}
