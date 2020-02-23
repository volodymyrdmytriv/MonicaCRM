<?php

namespace App\Http\Resources\Job;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\Resource;

class Job extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $coworkers = [];
        foreach($this->coworkers as $coworker)
        {
            $coworkers[] = [
                'id' => $coworker->hashID(),
                'contact_id' => $coworker->contact->hashID(),
                'contact_is_partial' => $coworker->contact->is_partial,
                'complete_name' => $coworker->contact->name,
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => DateHelper::getShortDate($this->start_date),
            'start_date_str' => $this->start_date->toDateString(),
            'completed' => (bool) $this->completed,
            'completed_at' => DateHelper::getShortDate($this->completed_at),
            'coworkers' => $coworkers
        ];
    }
}
