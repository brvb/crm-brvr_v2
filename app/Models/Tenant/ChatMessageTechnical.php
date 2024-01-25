<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class ChatMessageTechnical extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $table = 'chat_message_tecnicos';

    protected $fillable = ['message', 'user_id','tech_id'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }

}
