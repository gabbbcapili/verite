@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Audit Settings')

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.min.css')) }}">
@endsection

@section('content')
<section id="card-actions">
  <form action="{{ route('settings.auditUpdate') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Audit</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="row mb-2">
                <!-- <h4 class="card-title">Audit</h4> -->
                <div class="col-lg-4 col-xs-12">
                    <div class="form-group">
                        <label for="name">Status (Ready for Audit):</label>
                        <select class="form-control select2" name="status_for_audit_name">
                          @foreach($scheduleStatuses as $scheduleStatus)
                            <option value="{{ $scheduleStatus->id }}" {{ $setting->status_for_audit_name == $scheduleStatus->id ? 'selected' : '' }}>{{ $scheduleStatus->name }}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-12 align-items-center justify-content-center text-center">
                          <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
</section>
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/trumbowyg/trumbowyg.min.js')) }}"></script>
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
      $(document).ready(function(){
        $('.trumbowyg').trumbowyg();
      });
  </script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
