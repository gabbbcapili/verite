@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Add Supplier')

@section('vendor-style')

@endsection

@section('content')
<section id="card-actions">
  <form action="{{ route('supplier.store') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Create Supplier</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
                <div class="form-body">
                  <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">First Name:</label>
                            <input type="text" class="form-control" name="first_name" placeholder="First Name">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Last Name:</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Email:</label>
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-lg-4 col-xs-12">
                        <div class="form-group">
                            <label for="name">Template:</label>
                            <select class="form-control select2" name="template_id">
                              <option selected disabled></option>
                              @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
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
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-12 align-items-center justify-content-center text-center">
                          <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                          <button type="reset" class="btn btn-outline-warning mr-1">Reset </button>


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
  {{-- vendor files --}}

@endsection
@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
      $(document).ready(function(){
        $('.select2').select2();
      });
  </script>
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
