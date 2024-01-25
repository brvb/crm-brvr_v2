<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TaskServices;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Database\Eloquent\Builder;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TasksReports extends Model
{
    use HasFactory;
    use ComposhipsEagerLimit;

    protected $fillable = ['reference','customer_id', 'location_id', 'task_id','status','additional_description', 'applicant_name', 'applicant_contact', 'preview_date', 'preview_hour', 'scheduled_date', 'scheduled_hour', 'tech_id','relatorio','conclusao','informacoes_confidenciais','concluido','infoConclusao'];

    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'task_id', 'id');
    }

    public function taskCustomer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id')->with('customerCounty')->with('customerDistrict');
    }

    public function taskLocation()
    {
        return $this->belongsTo(CustomerLocations::class, 'location_id', 'id')->with('locationCounty')->with('locationDistrict');
    }

    public function servicesToDo()
    {
        return $this->hasMany(TaskServices::class, 'task_id', 'task_id')->with('service');
    }

    public function tech()
    {
        return $this->belongsTo(TeamMember::class,'tech_id','id');
    }

    public function getHoursTask()
    {
        return $this->hasMany(TasksTimes::class,'task_id','task_id');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordered', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('scheduled_date');
        });
    }


}
