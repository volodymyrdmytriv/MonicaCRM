@extends('layouts.skeleton')

@section('content')
  <section class="ph3 ph0-ns workcash-editform">

    {{-- Breadcrumb --}}
    <div class="mt4 mw7 center mb3">
      <div class="mw9 center">
          <ul>
            <li class="di">
              <a href="{{ route('workcash.index') }}">{{ trans('workcash.breadcrumb_dashboard') }}</a>
            </li>
            <li class="di">
              &gt; {{ trans('workcash.company_add_title') }}
            </li>
          </ul>
      </div>
    </div>
    
    <div class="mw7 center mb3">
      <h3 class="f3 fw5">{{ trans('workcash.company_edit_title') }}</h3>
    </div>
    
    <div class="mw7 center br3 ba b--gray-monica bg-white mb5">
        @include('workcash.company.form', [
          'method' => 'PUT',
          'action' => route('workcash.companies.update', $company)
        ])
    </div>
</section>
@endsection
