@extends('layouts.skeleton')

@section('title', $company->name )

@section('content')
<div class="workcash-company">
    
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <ul class="horizontal">
              <li>
                <a href="{{ route('workcash.index') }}">{{ trans('workcash.breadcrumb_dashboard') }}</a>
              </li>
              <li>
                {{ $company->name }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="ph3 ph5-ns pv2 cf w-100 mt4 mt0-ns">
        <div class="mw9 center dt w-100 box-shadow pa4 relative flex relative">
            {{-- AVATAR --}}
            <div class="flex-none w-10">
                @if ($company->has_avatar)
                <img src="{{ $company->getAvatarURL(174) }}" style="w-100">
                @else
                <div class="avatar one-letter w-100">
                   {{ $company->name }}
                </div>
                @endif
            </div>
            <div class="flex-auto pl3">
                <div class="f3 b mb2">{{ $company->name }}
                    @for ($i = 1; $i <= $company->getStarsCount(); $i++)
                    <svg class="relative" style="top: 5px" width="23" height="22" viewBox="0 0 23 22" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                       <path d="M14.6053 7.404L14.7224 7.68675L15.0275 7.7111C16.7206 7.84628 18.1486 7.92359 19.2895 7.98536C19.9026 8.01855 20.4327 8.04725 20.8765 8.07803C21.5288 8.12327 21.9886 8.17235 22.2913 8.24003C22.3371 8.25027 22.3765 8.26035 22.4102 8.27001C22.3896 8.29619 22.3651 8.32572 22.336 8.35877C22.1328 8.58945 21.7914 8.89714 21.2913 9.31475C20.9474 9.60184 20.5299 9.93955 20.0459 10.331C19.1625 11.0455 18.0577 11.9391 16.7762 13.0302L16.543 13.2288L16.614 13.5267C17.0045 15.1663 17.3689 16.5406 17.6601 17.6391C17.819 18.2381 17.956 18.7552 18.0638 19.1886C18.2209 19.8206 18.3149 20.2704 18.3428 20.5768C18.347 20.6222 18.3493 20.6616 18.3505 20.6957C18.3176 20.6838 18.2798 20.6688 18.2367 20.6502C17.9532 20.5277 17.5539 20.2981 17.0014 19.9522C16.6264 19.7175 16.1833 19.4306 15.671 19.099C14.7143 18.4797 13.5162 17.7042 12.0695 16.8199L11.8087 16.6604L11.5478 16.82C10.0753 17.7209 8.86032 18.5085 7.89223 19.136C7.3851 19.4648 6.94572 19.7496 6.57253 19.9838C6.01576 20.3332 5.61353 20.5656 5.32808 20.6899C5.28721 20.7077 5.25111 20.7222 5.21941 20.7339C5.22088 20.7009 5.22355 20.663 5.22783 20.6197C5.25839 20.3111 5.35605 19.8582 5.51781 19.2225C5.62627 18.7962 5.76269 18.2914 5.92018 17.7087C6.22053 16.5972 6.59748 15.2024 7.00309 13.5286L7.07553 13.2297L6.84141 13.0303C5.52399 11.9079 4.39683 10.9982 3.50024 10.2747C3.03915 9.90254 2.63904 9.57963 2.30539 9.30232C1.80195 8.88388 1.45729 8.57562 1.25116 8.34437C1.22315 8.31293 1.19929 8.28466 1.17903 8.25939C1.20999 8.25084 1.24557 8.24198 1.28628 8.233C1.58841 8.1663 2.048 8.11835 2.701 8.07418C3.1353 8.0448 3.65101 8.01744 4.24568 7.98589C5.39523 7.9249 6.83989 7.84824 8.56208 7.71111L8.86638 7.68688L8.98388 7.40514C9.61646 5.88824 10.1238 4.58366 10.5314 3.53571C10.7656 2.93365 10.9668 2.4163 11.1399 1.99205C11.3854 1.39027 11.5751 0.972355 11.7339 0.708729C11.7601 0.66516 11.7838 0.628777 11.8048 0.598565C11.8256 0.628571 11.849 0.664658 11.8748 0.707817C12.0327 0.971308 12.2212 1.38911 12.465 1.99089C12.6368 2.41509 12.8365 2.93242 13.0689 3.53445C13.4735 4.58244 13.9771 5.88709 14.6053 7.404Z"
                           fill="#F2C94C" stroke="#DCBB58"/>
                    </svg>
                    @endfor
                </div>
                <div class="mb2">
                @if ($company->workingNow())
                    {{ trans('workcash.companies_working_now', ['from' => $company->workDateShort('start')] ) }}
                @else
                    {{ trans('workcash.companies_worked', ['from' => $company->workDateShort('start'), 'to'=> $company->workDateShort('end') ]) }}
                @endif
                    {{ trans('workcash.companies_worked_as', ['position' => $company->position]) }}
                </div>
                <div class="mb2">
                    {{ trans('workcash.company_yearly_income', ['value' => $company->getYearlyIncomeFormatted()]) }}
                </div>
                <div class="i">{{ $company->description }}</div>
            </div>
            <div class="absolute tc edit-company-button">
                <a href="{{ route('workcash.companies.edit', $company) }}" class="btn">{{ trans('workcash.company_edit_button') }}</a>
            </div>
            
        </div>
    </div>
    
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3">
                    <company-coworkers company-id="{{ $company->hashID() }}"></company-coworkers>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9">
                    <company-views company-id="{{ $company->hashID() }}" default-active-tab="{{ $company->active_tab }}" ></company-views>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
