@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Audit Form')

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <form action="{{ route('auditForm.update', $auditFormHeader) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                  <h4 class="card-title text-center">{{  $auditForm->template->name }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Assign Name for Form Answers:</label>
                                    <input type="text" name="formName" class="form-control" value="{{ $auditFormHeader->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" id="template_preview">
                            @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $auditFormHeader->answers])
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
                                <input type="checkbox" name="save_finish_later" id="save_finish_later" hidden>
                                <button type="button" class="btn btn-warning save_finish_later"><i data-feather="save"></i> Save & Finish Later</button>
                                <button type="submit" class="btn btn-primary me-1 btn_save"><i data-feather="save"></i> Save</button>
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
    <script src="{{ asset('vendors/js/jquery/jquery-ui.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script src="{{ asset('js/scripts/tables/table-question.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '.save_finish_later', function(){
            $('#save_finish_later').prop('checked', true);
            $('.btn_save').click();
        });
    </script>
    <script type="text/javascript">
    if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('/serviceWorker.js').then(function(){
          });
          navigator.serviceWorker.ready.then( registration => {
            registration.active.postMessage({ current_url: self.location.href });
          });
    }
    </script>
@endsection