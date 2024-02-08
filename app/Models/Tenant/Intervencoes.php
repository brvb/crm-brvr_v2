<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervencoes extends Model
{
    use HasFactory;

    protected $table = 'intervencoes';
    protected $fillable = ['id_pedido', 'material_ref_intervencao','material_descricao_intervencao','material_quantidade_intervencao','estado_pedido','descricao_realizado','anexos','assinatura_tecnico','assinatura_cliente','horas_alterado','user_id','data_inicio','hora_inicio','hora_final','data_final','created_at','updated_at'];


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
