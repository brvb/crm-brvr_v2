<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotacao extends Model
{
    use HasFactory;
    protected $table = 'annotations';
    protected $fillable = ['id','user_id','text','ordem'];
}
