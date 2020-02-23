<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  {{ method_field($method) }}
  {{ csrf_field() }}

  @include('partials.errors')

  {{-- Company Name --}}
  <div class="pa4-ns ph3 pv2 b--gray-monica">
    <form-input
        value="{{ old('name') ?? $company->name }}"
        :input-type="'text'"
        :id="'name'"
        :required="true"
        :title="'{{ trans('workcash.company_add_name') }}'">
    </form-input>
  </div>

  {{-- Company Description --}}
  <div class="pa4-ns ph3 pv2 bb b--gray-monica">
    <p class="mb2 b">
      {{ trans('workcash.company_add_description') }}
    </p>
    <textarea rows="3" name="description" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required >{{ old('description') ?? $company->description }}</textarea>
  </div>

  {{-- Avatar --}}
  <div class="pa4-ns ph3 pv2 bb b--gray-monica">
    <div class="mb3 mb0-ns">
      <label for="avatar">{{ trans('workcash.company_add_avatar') }}</label>
      <input type="file" class="form-control-file" name="avatar" id="avatar">
      <small id="fileHelp" class="form-text text-muted">{{ trans('people.information_edit_max_size', ['size' => config('monica.max_upload_size')]) }}</small>
    </div>
  </div>

  {{-- Your position --}}
  <div class="pa4-ns ph3 pv2 b--gray-monica">
    <form-input
        value="{{ old('position') ?? $company->position }}"
        :input-type="'text'"
        :id="'position'"
        :required="true"
        :title="'{{ trans('workcash.company_add_role') }}'">
    </form-input>
  </div>

  {{-- Income --}}
  <div class="pa4-ns ph3 pv2 bb b--gray-monica">
    <p class="mb2 b">
      {{ trans('workcash.company_add_income') }}
    </p>
    <input type="number" name="yearly_income" step=".01" value="{{ old('yearly_income') ?? $company->yearly_income }}" maxlength="254" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
  </div>

  {{-- Start work date --}}
  <div class="pa4-ns ph3 pv2 ">
    <p class="mb2 b">
      {{ trans('workcash.company_add_startdate') }}
    </p>
    <input type="date" name="work_start_date" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('work_start_date') ?? (! is_null($company->work_start_date) ? $company->work_start_date->toDateString() : now(\App\Helpers\DateHelper::getTimezone())->toDateString()) }}" required>
  </div>

  {{-- End work date --}}
  <div class="pa4-ns ph3 pv2 bb b--gray-monica">
    <p class="mb2">
      {{ trans('workcash.company_add_enddate') }}
    </p>
    <input type="date" name="work_end_date" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('work_end_date') ?? (! is_null($company->work_end_date) ? $company->work_end_date->toDateString() : '')  }}" >
  </div>

  {{-- Satisfaction factor --}}
  <div class="pa4-ns ph3 pv2 bb b--gray-monica">
    <div class="mb3 mb0-ns">
      <form-select
        :options="{{ $satisfactions }}"
        value="{{ old('satisfaction_rate') ?? $company->satisfaction_rate ?? '5' }}"
        :required="true"
        :title="'{{ trans('workcash.company_add_satisfaction') }}'"
        :id="'satisfaction_rate'">
      </form-select>
    </div>
  </div>

  {{-- Form actions --}}
  <div class="ph4-ns ph3 pv3 bb b--gray-monica">
    <div class="flex-ns justify-between">
      <div>
          <a href="{{ route('workcash.index') }}" class="btn btn-secondary w-auto-ns w-100 mb2 pb0-ns">{{ trans('app.cancel') }}</a>
      </div>
      <div>
        <button class="btn btn-primary w-auto-ns w-100 mb2 pb0-ns" name="save" type="submit">{{ trans('app.save') }}</button>
      </div>
    </div>
  </div>
</form>