<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class ChatMessage extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $table = 'chat_message';

    protected $fillable = ['message', 'user_id','customer_id'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }

}
