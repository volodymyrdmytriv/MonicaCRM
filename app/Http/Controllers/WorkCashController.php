<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WorkCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = auth()->user()->account()->first();
        
        $companies = $account->wc_companies()
                ->oldest('work_end_date')
                ->latest('work_start_date')
                ->get();
        
        $data = [
            'companies' => $companies,
            
        ];
        return view('workcash.index', $data);
    }
    
    
    
}