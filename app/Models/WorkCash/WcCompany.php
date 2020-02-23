<?php

namespace App\Models\WorkCash;

use Illuminate\Support\Facades\Storage;
use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Models\Contact\Contact;
use App\Models\WorkCash\Coworker;
use App\Models\WorkCash\Job;
use App\Models\ModelBindingHasher as Model;

class WcCompany extends Model
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wc_companies';
    
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
    protected $dates = ['work_start_date', 'work_end_date'];

    /**
     * Delete avatars files.
     * This does not touch avatar_location or avatar_file_name properties of the contact.
     */
    public function deleteAvatars()
    {
        if (! $this->has_avatar || $this->avatar_location == 'external') {
            return;
        }

        $storage = Storage::disk($this->avatar_location);
        $this->deleteAvatarSize($storage);
        $this->deleteAvatarSize($storage, 110);
        $this->deleteAvatarSize($storage, 174);
    }
    
    /**
     * Delete avatar file for one size.
     *
     * @param Filesystem $storage
     * @param int $size
     */
    private function deleteAvatarSize(Filesystem $storage, int $size = null)
    {
        $avatarFileName = $this->avatar_file_name;

        if (! is_null($size)) {
            $filename = pathinfo($avatarFileName, PATHINFO_FILENAME);
            $extension = pathinfo($avatarFileName, PATHINFO_EXTENSION);
            $avatarFileName = 'avatars/'.$filename.'_'.$size.'.'.$extension;
        }

        try {
            if ($storage->exists($avatarFileName)) {
                $storage->delete($avatarFileName);
            }
        } catch (FileNotFoundException $e) {
            return;
        }
    }
    
    
    /**
     * Returns the URL of the avatar with the given size.
     *
     * @param  int $size
     * @return string
     */
    public function getAvatarURL($size = 110)
    {
        // it either returns null or the gravatar url if it's defined
        if (! $this->avatar_file_name) {
            return '';
        }

        $originalAvatarUrl = $this->avatar_file_name;
        $avatarFilename = pathinfo($originalAvatarUrl, PATHINFO_FILENAME);
        $avatarExtension = pathinfo($originalAvatarUrl, PATHINFO_EXTENSION);
        $resizedAvatar = 'avatars/'.$avatarFilename.'_'.$size.'.'.$avatarExtension;

        return asset(Storage::disk($this->avatar_location)->url($resizedAvatar));
    }

    public function getStarsCount()
    {
        if($this->satisfaction_rate >=1 && $this->satisfaction_rate <=5 )
        {
            return $this->satisfaction_rate;
        }
        
        return 1;
    }
    
    public function workingNow()
    {
        if( is_null($this->work_end_date) )
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Get short string dates
     * @param string $type can be 'start' or 'end' for start_date or end_date
     */
    public function workDateShort(string $type='start', $def='')
    {
        if($type == 'start')
        {
            if(is_null($this->work_start_date))
            {
                return $def;
            }
            
            return DateHelper::getShortDate($this->work_start_date);
        }
        else if($type == 'end')
        {
            if(is_null($this->work_end_date))
            {
                return $def;
            }
            
            return DateHelper::getShortDate($this->work_end_date);
        }
        
        return $def;
    }
    
    public function getYearlyIncomeFormatted()
    {
        $value = 0;
        if(!is_null($this->yearly_income))
        {
            $value = $this->yearly_income;
        }
        
        return MoneyHelper::format($value);
    }
    
    public function addCoworker(Contact $contact, string $role)
    {
        $coworker = new Coworker();
        $coworker->account_id = $this->account_id;
        $coworker->company_id = $this->id;
        $coworker->contact_id = $contact->id;
        $coworker->role = $role;
        $coworker->save();
        
        return $coworker;
    }
    
    public function coworkers()
    {
        return $this->hasMany(Coworker::class, 'company_id');
    }
    
    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }
    
}