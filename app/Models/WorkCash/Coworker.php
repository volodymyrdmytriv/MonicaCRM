<?php

namespace App\Models\WorkCash;

use App\Models\ModelBindingHasher as Model;
use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coworker extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wc_coworkers';
    
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    
}