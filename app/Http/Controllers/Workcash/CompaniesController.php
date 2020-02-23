<?php

namespace App\Http\Controllers\Workcash;

use App\Http\Controllers\Controller;
use App\Models\WorkCash\WcCompany;
use App\Http\Requests\Workcash\CompaniesRequest;
use App\Jobs\ResizeAvatarsCommon;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    
    private $satisfactions;
    
    function __construct() {
        $this->satisfactions = [
            ['id'=>'5', 'name' => 'Excellent 5'],
            ['id'=>'4', 'name' => 'Good 4'],
            ['id'=>'3', 'name' => 'Medium 3'],
            ['id'=>'2', 'name' => 'Not Bad 2'],
            ['id'=>'1', 'name' => 'Bad 1']
        ];
        
    }
    
    public function create()
    {
        $company = new WcCompany();
        
        return view('workcash.company.create', ['satisfactions' => json_encode($this->satisfactions),
            'company' => $company]);
    }
    
    public function store(CompaniesRequest $request, WcCompany $company = null)
    {
        $company = $this->updateOrCreate($request, $company);
        
        return redirect()->route('workcash.index')
            ->with('success', trans('workcash.company_add_success', ['name' => $company->name]));
    }
    
    public function edit(WcCompany $company)
    {
        return view('workcash.company.edit', ['satisfactions' => json_encode($this->satisfactions),
            'company' => $company]);
    }
    
    public function update(CompaniesRequest $request, WcCompany $company)
    {
        $this->updateOrCreate($request, $company);
        
        return redirect()->route('workcash.index')
            ->with('success', trans('workcash.company_edit_success', ['name' => $company->name]));
    }
    
    public function show(WcCompany $company)
    {
        return view('workcash.company.show')
            ->withCompany($company);
    }
    
    public function setActiveTab(Request $request, WcCompany $company)
    {
        $allowedValues = ['notes', 'jobs', 'files'];
        $tab = $request->get('tab');

        if (! in_array($tab, $allowedValues)) {
            return 'not allowed';
        }

        $company->active_tab = $tab;
        $company->save();

        return $tab;
    }
    
    /**
     * Save resource in storage.
     *
     * @param GiftsRequest $request
     * @param Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(CompaniesRequest $request, WcCompany $company = null)
    {
        $user = auth()->user();
        
        $array = $request->only([
                'name',
                'description',
                'position',
                'yearly_income',
                'work_start_date',
                'work_end_date',
                'satisfaction_rate',
            ])
            + [
                'account_id' => $user->account_id
            ];

        if (is_null($company)) {
            $company = $user->account->wc_companies()->create($array);
        } else {
            $company->update($array);
        }
        
        if($request->hasFile('avatar'))
        {
            if ($company->has_avatar) {
                try {
                    $company->deleteAvatars();
                } catch (\Exception $e) {
                    return back()
                        ->withInput()
                        ->withErrors(trans('app.error_save'));
                }
            }
            $company->has_avatar = 1;
            $company->avatar_location = config('filesystems.default');
            $company->avatar_file_name = $request->avatar->storePublicly('avatars', $company->avatar_location);
            $company->save();
            
            dispatch(new ResizeAvatarsCommon($company->avatar_location, $company->avatar_file_name));
        }
        
        return $company;
    }
    
}