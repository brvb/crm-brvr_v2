<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervencoes extends Model
{
    use HasFactory;

    protected $table = 'intervencoes';
    protected $fillable = ['id','id_pedido', 'produtos_ref','produtos_desc','produtos_qtd','descricao','estado_pedido','descricao_realizado','anexos','assinatura_tecnico','assinatura_cliente','horas_alterado','user_id','data_inicio','hora_inicio','hora_final','data_final','descontos','descricao_desconto','created_at','updated_at'];


    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido', 'id')->with('tipoEstado');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('id_pedido');
        });
    }

}
