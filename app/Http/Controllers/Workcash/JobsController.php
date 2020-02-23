<?php

namespace App\Http\Controllers\Workcash;

use App\Http\Controllers\Controller;
use App\Models\WorkCash\Job;
use App\Models\WorkCash\WcCompany;
use App\Http\Requests\Workcash\JobsRequest;
use Carbon\Carbon;
use App\Http\Resources\Job\Job as JobResource;
use Illuminate\Http\Request;

/**
 * Used in the jobs VueJS component for ajax calls
 */
class JobsController extends Controller
{
    /**
     * Get the jobs list
     * @return type
     */
    public function index(WcCompany $company)
    {
        $jobs = $company->jobs()->with('coworkers.contact')
                ->latest('start_date')
                ->get();
        
        return JobResource::collection($jobs);
    }
    
    public function store(JobsRequest $request, WcCompany $company)
    {
        $job = $this->updateOrCreate($request, $company);
        
        return $job;
    }
    
    public function update(JobsRequest $request, WcCompany $company, Job $job)
    {
        $job = $this->updateOrCreate($request, $company, $job);
        
        return $job;
    }
    
    public function destroy(WcCompany $company, Job $job)
    {
        $res = $job->delete();
        
        return "success";
    }
    
    public function completed(Request $request, WcCompany $company, Job $job)
    {
        if($request->input('completed') == '1')
        {
            $job->completed = 1;
            $job->completed_at = Carbon::now();
        }
        else if($request->input('completed') == '0')
        {
            $job->completed = 0;
            $job->completed_at = null;
        }
        $job->save();
        
        return new JobResource($job);
    }
    
    /**
     * Save resource in storage.
     *
     * @param GiftsRequest $request
     * @param Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(JobsRequest $request, WcCompany $company, Job $job = null)
    {
        $account_id = $request->user()->account_id;
        
        $array = $request->only([
                'title',
                'description',
                'start_date',
            ])
            + [
                'account_id' => $account_id,
            ];
        
        if(is_null($job))
        {
            if($request->input('completed') == '1')
            {
                $array['completed'] = 1;
                $array['completed_at'] = Carbon::now();
            }
            else
            {
                $array['completed'] = 0;
                $array['completed_at'] = null;
            }

            $job = $company->jobs()->create($array);
        }
        else
        {
            if($request->input('completed') == '1')
            {
                if(!$job->completed)
                {
                    $array['completed'] = 1;
                    $array['completed_at'] = Carbon::now();
                }
            }
            else 
            {
                $array['completed'] = 0;
                $array['completed_at'] = null;
            }

            $job->update($array);
        }
        
        $coworker_ids = [];
        if($request->has('coworkers'))
        {
            $coworkers = $request->input('coworkers');
            foreach($coworkers as $coworker_id)
            {
                $coworker_ids[] = app('idhasher')->decodeId($coworker_id);
            }
        }
        $job->coworkers()->sync($coworker_ids);
        
        return $job;
    }
    
    
}
