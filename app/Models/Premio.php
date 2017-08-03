<?php
namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class Premio extends Model
{    
    protected $table = 'premio';
    protected $fillable = array('campaña','nombre_premio','cantidad');
}