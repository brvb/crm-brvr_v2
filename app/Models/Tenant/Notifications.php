<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $table = 'notifications';

    protected $fillable = ['sender_user_id', 'receiver_user_id','read','type','group_chat'];

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id', 'id');
    }

    public function receivedUser()
    {
        return $this->belongsTo(User::class, 'receiver_user_id', 'id');
    }


}
