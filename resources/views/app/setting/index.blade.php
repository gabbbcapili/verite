@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Settings')

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/trumbowyg/trumbowyg.min.css')) }}">
@endsection

@section('content')
<section id="card-actions">
  <form action="{{ route('settings.update') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Email Messages</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="row mb-2">
                <h4 class="card-title">Assessment Forms</h4>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Form Created:</label>
                        <textarea class="form-control trumbowyg" name="spaf_create">{!! $setting->spaf_create !!}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Form Resend (Reminder):</label>
                        <textarea class="form-control trumbowyg" name="spaf_reminder">{!! $setting->spaf_reminder !!}</textarea>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Form Declined (Additional Information Needed):</label>
                        <textarea class="form-control trumbowyg" name="spaf_resend">{!! $setting->spaf_resend !!}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Form Completed:</label>
                        <textarea class="form-control trumbowyg" name="spaf_completed">{!! $setting->spaf_completed !!}</textarea>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row mb-2">
                <h4 class="card-title">User</h4>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Welcome:</label>
                        <textarea class="form-control trumbowyg" name="user_welcome">{!! $setting->user_welcome !!}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Reset Password:</label>
                        <textarea class="form-control trumbowyg" name="user_reset">{!! $setting->user_reset !!}</textarea>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Client Welcome:</label>
                        <textarea class="form-control trumbowyg" name="welcome_client">{!! $setting->welcome_client !!}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Supplier Welcome:</label>
                        <textarea class="form-control trumbowyg" name="welcome_supplier">{!! $setting->welcome_supplier !!}</textarea>
                    </div>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Admin Change Role Of User (Reminder):</label>
                        <textarea class="form-control trumbowyg" name="admin_change_role_of">{!! $setting->admin_change_role_of !!}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Changed Role:</label>
                        <textarea class="form-control trumbowyg" name="user_changed_role">{!! $setting->user_changed_role !!}</textarea>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row mb-2">
                <h4 class="card-title">Footer</h4>
                  <div class="col-lg-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Footer Text:</label>
                        <textarea class="form-control trumbowyg" name="email_footer">{!! $setting->email_footer !!}</textarea>
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
                          <input type="submit" name="no_action" class="btn btn-primary me-1 btn_save" value="Save">
                          <button type="reset" class="btn btn-outline-warning mr-1">Clear </button>
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
