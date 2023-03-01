@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create New Schedule Status')

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.scheduleStatus.store') }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
                @csrf
              <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-4 col-xs-12">
                      <div class="form-group">
                          <label for="name">Name:</label>
                          <input type="text" class="form-control" name="name" placeholder="Name">
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-12">
                       <label class="form-label">Color</label>
                       <select  class="form-control select2" name="color" title="Choose color">
                        <option selected disabled></option>
                        @foreach(App\Models\ScheduleStatus::$colors as $color)
                            <option value="{{ $color}}" data-color="{{ $color }}">
                                <span class="text-{{ $color }}">{{ $color }}</span>
                            </option>
                        @endforeach
                        </select>
                  </div>
                  <div class="row">
                        <div class="col-12 align-items-center justify-content-center text-center">
                          <input type="submit" name="save" class="btn btn-primary me-1 btn_save" value="Save">
                        </div>
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
            $(".select2").select2({
                templateResult: formatState,
                templateSelection: formatState
          });

            function formatState (opt) {
                if (!opt.id) {
                    return opt.text.toUpperCase();
                }

                var color = $(opt.element).attr('data-color');
                console.log(color)
                if(!color){
                   return opt.text.toUpperCase();
                } else {
                    var $opt = $(
                       '<span class="text-white bg-' + color + '">' + opt.text.toUpperCase() + '</span>'
                    );
                    return $opt;
                }
            };
        });
    </script>
@endsection
