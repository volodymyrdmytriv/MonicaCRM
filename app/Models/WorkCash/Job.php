<?php

namespace App\Models\WorkCash;

use App\Models\WorkCash\Coworker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wc_jobs';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'completed_at'];
    
    public function coworkers()
    {
        return $this->belongsToMany(Coworker::class, 'wc_job_coworker');
    }
    
}