<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blogs extends Model
{
    use HasFactory;
    protected $fillable = ["title", "description", "user_id"];

    // public function getRouteKeyName()
    // {
    //     return 'title';
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
