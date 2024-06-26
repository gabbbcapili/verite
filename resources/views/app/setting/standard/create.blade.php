@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create New Audit Standard')

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.standard.store') }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-12 col-xs-12">
                      <div class="form-group">
                          <label for="name">Name:</label>
                          <input type="text" class="form-control" name="name" placeholder="Name">
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-12 align-items-center justify-content-center text-center">
                      <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                    </div>
                  </div>
              </form>
        </div>
    </div>
</section>
@endsection

@section('vendor-script')


@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
          });
    </script>
@endsection
