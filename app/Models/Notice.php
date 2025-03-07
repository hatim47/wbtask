<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "notices";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tos',
        'froms',
        'title',
        'card_id',
        'status',
    ];
    public function tos()
    {
        return $this->belongsTo(User::class ,'tos');
    }
    public function froms()
    {
        return $this->belongsTo(User::class,'froms');
    }

}
