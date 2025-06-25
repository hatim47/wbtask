<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;
    protected $fillable = ['card_id', 'file_path','original_name'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
