<?php

namespace App\Http\Controllers\Workcash;

use App\Http\Controllers\Controller;
use App\Models\WorkCash\WcCompany;
use App\Models\WorkCash\Coworker;
use Illuminate\Http\Request;
use App\Models\Contact\Contact;
use App\Jobs\ResizeAvatars;

/**
 * Used in the coworker VueJS component for ajax calls
 */
class CoworkersController extends Controller
{
    
    /**
     * Get the coworkers list
     * @return type
     */
    public function index(WcCompany $company)
    {
        $coworkers = $company->coworkers()->with('contact.gender')->get();
        $coworkersCollection = collect([]);
        
        foreach ($coworkers as $coworker) {
            if ($coworker->contact->is_dead) {
                continue;
            }
            
            $data = [
                'id' => $coworker->hashID(),
                'contact_id' => $coworker->contact->hashID(),
                'contact_is_partial' => $coworker->contact->is_partial,
                'has_avatar' => $coworker->contact->has_avatar,
                'avatar_url' => $coworker->contact->getAvatarURL(110),
                'initials' => $coworker->contact->getInitials(),
                'default_avatar_color' => $coworker->contact->default_avatar_color,
                'complete_name' => $coworker->contact->name,
                'name' => $coworker->contact->name,
                'first_name' => $coworker->contact->first_name,
                'last_name' => $coworker->contact->last_name,
                'gender_id' => !is_null($coworker->contact->gender) ? $coworker->contact->gender->id : "0",
                'role' => $coworker->role
            ];
            $coworkersCollection->push($data);
        }
        
        return $coworkersCollection;
    }
    
    public function store(Request $request, WcCompany $company)
    {
        // case of linking to an existing contact
        if ($request->get('contact_type') == 'existing') {
            $this->validate($request, [
                'existing_contact_id' => 'required|integer|min:1',
                'role' => 'required|string',
            ]);

            $partner = Contact::where('account_id', $request->user()->account_id)
                ->findOrFail($request->get('existing_contact_id'));
            
            $coworker = $company->addCoworker($partner, $request->get('role'));
            
            return $coworker;
        }
        
        // create new contact
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'max:100',
            'gender_id' => 'required|integer',
            'role' => 'required|max:50',
        ]);
        
        // set the name of the contact
        $contact = new Contact();
        $contact->account_id = $request->user()->account_id;
        $contact->setName($request->input('first_name'), $request->input('last_name'));
        $contact->gender_id = $request->input('gender_id');
        $contact->is_partial = true;
        if ($request->get('real_contact') == 'true') {
            $contact->is_partial = false;
        }
        
        // set avatar color
        $contact->setAvatarColor();
        
        $contact->save();
        
        if ($request->file('avatar') != '') {
            if ($contact->has_avatar) {
                $contact->deleteAvatars();
            }
            $contact->has_avatar = true;
            $contact->avatar_location = config('filesystems.default');
            $contact->avatar_file_name = $request->avatar->storePublicly('avatars', $contact->avatar_location);
            $contact->save();
            
            dispatch(new ResizeAvatars($contact));
        }
        
        $coworker = $company->addCoworker($contact, $request->get('role'));
        
        return $coworker;
    }
    
    public function update(Request $request, WcCompany $company, Coworker $coworker)
    {
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'max:100',
            'gender_id' => 'required|integer',
            'role' => 'required|max:50',
        ]);
        
        $contact = $coworker->contact;
        $contact->setName($request->input('first_name'), $request->input('last_name'));
        $contact->gender_id = $request->input('gender_id');
        $contact->save();
        
        if ($request->file('avatar') != '') {
            if ($contact->has_avatar) {
                $contact->deleteAvatars();
            }
            $contact->has_avatar = true;
            $contact->avatar_location = config('filesystems.default');
            $contact->avatar_file_name = $request->avatar->storePublicly('avatars', $contact->avatar_location);
            $contact->save();
            
            dispatch(new ResizeAvatars($contact));
        }
        
        $coworker->role = $request->input('role');
        $coworker->save();
        
        // check if the contact is partial
        if ($request->get('real_contact') == 'true') {
            $contact->is_partial = false;
            $contact->save();
        }
        
        return $coworker;
    }
    
    public function destroy(WcCompany $company, Coworker $coworker)
    {
        $res = $coworker->delete();
        
        return "success";
    }
    
    public function getCoworkerModalData()
    {
        // getting top 100 of existing contacts
        $existingContacts = auth()->user()->account->contacts()
                            ->real()
                            ->active()
                            ->select(['id', 'first_name', 'last_name', 'middle_name', 'nickname'])
                            ->sortedBy('name')
                            ->take(100)
                            ->get();
        
        // Building the list of contacts specifically for the dropdown which asks
        // for an id and a name. Also filter out the current contact.
        $arrayContacts = collect();
        foreach ($existingContacts as $existingContact) {
            $arrayContacts->push([
                'id' => $existingContact->id,
                'complete_name' => $existingContact->name,
            ]);
        }
        
        $data['genders'] = auth()->user()->account->genders;
        $data['existingContacts'] = $arrayContacts;
        
        return $data;
    }
    
}
