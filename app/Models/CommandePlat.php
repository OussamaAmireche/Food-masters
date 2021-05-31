<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandePlat extends Model
{
    use HasFactory;
    protected $table = 'commande_plat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plat_id',
        'quantité',
        'commande_id',
    ];
}
