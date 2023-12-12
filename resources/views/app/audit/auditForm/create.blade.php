@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create Audit Form')

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <form action="{{ route('auditForm.store', $auditForm) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @csrf
        <div class="row sticky-top">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                                <input type="checkbox" name="save_finish_later" id="save_finish_later" hidden>
                                <input type="checkbox" name="approveForm" id="approveForm" hidden>
                                <button type="button" class="btn btn-warning save_finish_later"><i data-feather="pocket"></i> Save & Continue Later</button>
                                <button type="submit" class="btn btn-primary btn_save"><i data-feather="save"></i> Save & Submit</button>
                                <button type="button" class="btn btn-success approve" data-title="Are you sure to approve this form?"><i data-feather="check-circle"></i> Save & Approve</button>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    <input type="text" name="formName" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" id="template_preview">
                            @include('app.template.spaf.preview', ['template' => $auditForm->template])
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

        $(document).on('click', '.approve', function(){
            Swal.fire({
                title:$(this).data('title'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#approveForm').prop('checked', true);
                    $('.btn_save').click();
                }
              });
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

    var idleTime = 0;
    $(document).ready(function () {
        // Increment the idle time counter every minute.
        var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

        // Zero the idle timer on mouse movement.
        $(this).mousemove(function (e) {
            idleTime = 0;
        });
        $(this).keypress(function (e) {
            idleTime = 0;
        });
    });

    function timerIncrement() {
        idleTime = idleTime + 1;
        if (idleTime > 30) { // 20 minutes
            idleTime = 0;
            Swal.fire({
                icon: 'danger',
                title: 'A gentle reminder to save this form.',
                icon: "warning",
                showConfirmButton: true,
                showClass: {
                  popup: 'animate__animated animate__fadeIn'
                },
              });
        }
    }
    
    window.addEventListener('beforeunload', promptConfirmationBeforeUnload);
    </script>
@endsection
