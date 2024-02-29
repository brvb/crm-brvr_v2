<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedidos extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['reference', 'number', 'customer_id', 'contacto_adicional', 'tipo_pedido', 'tipo_servico', 'descricao','descricao_reabertura', 'anexos','location_id','nr_serie','marca','modelo','nome_equipamento','descricao_equipamento','riscado','partido','bom_estado','estado_normal','transformador','mala','tinteiro','ac','descricao_extra','anexos_equipamentos','prioridade','tech_id','origem_pedido','tipo_agendamento','quem_pediu','data_agendamento','hora_agendamento','observacoes_agendamento','estado','horas_alterado','created_at','updated_at'];


    public function intervencoes()
    {
        return $this->hasMany(Intervencoes::class,'id_pedido','id');
    }
    public function tech()
    {
        return $this->belongsTo(TeamMember::class,'tech_id','id');
    }

    public function servicesToDo()
    {
        return $this->belongsTo(Services::class, 'tipo_servico', 'id');
    }

    public function tipoPedido()
    {
        return $this->belongsTo(TiposPedidos::class, 'tipo_pedido', 'id');
    }

    public function tipoEstado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(CustomerLocations::class, 'location_id', 'id')->with('locationCounty')->with('locationDistrict');
    }

    public function prioridadeStat()
    {
        return $this->belongsTo(Prioridades::class, 'prioridade', 'id');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('created_at');
        });
    }
}
