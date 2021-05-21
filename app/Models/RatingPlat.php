<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingPlat extends Model
{
    use HasFactory;
    protected $table = 'rating_plat';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_client',
        'id_plat',
        'rating',
    ];
}
