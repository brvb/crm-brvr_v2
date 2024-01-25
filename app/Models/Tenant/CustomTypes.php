<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTypes extends Model
{
    use HasFactory;
    protected $fillable = ['description','controller','field_name'];

}
