<?php
namespace App\domain\models;

use Illuminate\Database\Eloquent\Model;

class Flavor extends Model
{
    protected $table = 'sabor';
    protected $fillable = ['caracteristica'];

    public $timestamps = false;
}
