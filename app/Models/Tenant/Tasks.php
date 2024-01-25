<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tasks extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['number', 'reference', 'customer_id', 'location_id', 'resume', 'additional_description', 'applicant_name', 'applicant_contact', 'preview_date', 'preview_hour', 'scheduled_date', 'scheduled_hour', 'tech_id','origem_pedido','quem_pediu','tipo_pedido','alert_email','nr_serie','marca','modelo','nome_equipamento','descricao_equipamento','riscado','partido','bom_estado','estado_normal','transformador','mala','tinteiro','ac','descricao_extra', 'imagem','prioridade'];

    public function taskCustomer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id')->with('customerCounty')->with('customerDistrict');
    }

    public function taskLocation()
    {
        return $this->belongsTo(CustomerLocations::class, 'location_id', 'id')->with('locationCounty')->with('locationDistrict');
    }

    public function customerServiceList()
    {
        return $this->hasMany(CustomerServices::class, ['customer_id', 'location_id'], ['customer_id', 'location_id'])->with('service');
    }

    public function servicesToDo()
    {
        return $this->hasMany(TaskServices::class, 'task_id', 'id')->with('service');
    }

    public function tech()
    {
        return $this->belongsTo(TeamMember::class,'tech_id','id');
    }

    public function taskReports()
    {
        return $this->belongsTo(TasksReports::class,'reference','reference');
    }

    public function tasksTimes()
    {
        return $this->HasMany(TasksTimes::class,'task_id','id');
    }

    public function prioridades()
    {
        return $this->belongsTo(Prioridades::class,'prioridade','id');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('scheduled_date');
        });
    }
}
