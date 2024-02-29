<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PivotEstados extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $table = 'pivot_estados_pedidos_tipos_pedidos';
    protected $fillable = ['id_estado_pedido', 'id_tipo_pedido', 'created_at', 'updated_at'];


    public function estadoPedido()
    {
        return $this->hasMany(EstadoPedido::class,'id','id_estado_pedido');
    }
    public function tipoPedido()
    {
        return $this->hasMany(TiposPedidos::class,'id','id_tipo_pedido');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }
}
