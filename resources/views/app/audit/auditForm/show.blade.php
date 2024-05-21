@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Audit Form' )

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <form action="{{ route('auditForm.update', $auditFormHeader) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @method('put')
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
                                    @if($request->user()->can('auditForm.saveandsubmit'))
                                    <button type="submit" class="btn btn-primary btn_save"><i data-feather="save"></i> Save & Submit</button>
                                    @endif
                                    @if(request()->user()->can('auditForm.saveandapprove') && $auditFormHeader->status != 'completed')
                                    <button type="button" class="btn btn-success approve" data-title="Are you sure to approve this form?"><i data-feather="check-circle"></i> Save & Approve</button>
                                    @endif
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title text-center">Audit Form Reviews</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <table class="datatables-basic table" id="audit_form_reviews">
                              <thead>
                                <tr>
                                  <th>Id</th>
                                  <th>Group Header</th>
                                  <th>Message</th>
                                  <th>Status</th>
                                  <th>Created</th>
                                  <th>Updated</th>
                                  <th class="noexport">Action</th>
                                </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="printThis">
            <div class="row mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <!-- <div class="card-header"> -->
                          <!-- <h4 class="card-title text-center">Template Preview</h4> -->
                        <!-- </div> -->
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Assign Name for Form Answers:</label>
                                            <input type="text" name="formName" class="form-control" value="{{ $auditFormHeader->name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2" id="template_preview">
                                    @include('app.template.spaf.preview', ['template' => $auditForm->template, 'answers' => $auditFormHeader->answers, 'disabled' => true])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-print">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                <div class="col-12 align-items-center justify-content-center text-center">
                                  <button type="button" class="btn btn-primary no-print btn_print"><i data-feather="printer"></i> Print </button>
                                </div>
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
  <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/jszip.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
  <script src="{{ asset('vendors/js/jquery/jquery-ui.js') }}"></script>
  <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
    <script type="text/javascript">

        var table_id = 'audit_form_reviews'
        var table_title = 'Audit Form Reviews';
        var table_route = {
              url: '{{ route('auditForm.review.index', $auditFormHeader) }}',
              data: function (data) {
                    // data.role = $("#role").val();
                }
            };
          var columnns = [
                { data: 'id', name: 'id'},
                { data: 'groupDisplay', name: 'group_id'},
                { data: 'message', name: 'message'},
                { data: 'statusDisplay', name: 'status'},
                { data: 'created_at', name: 'created_at'},
                { data: 'updated_at', name: 'updated_at'},
                { data: 'action', name: 'action', 'orderable' : false, 'printable' : false}
            ];
          @if(request()->user()->can('auditForm.review'))
            var buttons = [
                {
                    text: '<i data-feather="plus"></i> Create New',
                    className: 'btn btn-primary modal_button',
                    attr: {
                        'data-action': '{{ route('auditForm.review.create', $auditFormHeader) }}'
                    },
                    action: function ( e, dt, node, config ) {
                    }
                },
            ];
          @else
           var buttons = [];
          @endif
          var drawCallback = function( settings ) {
            $('[data-bs-toggle="tooltip"]').tooltip();
            feather.replace({
              width: 14,height: 14
            });
          };
          var order =  [[ 0, "desc" ]];


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

        $(document).ready(function(){
            $('.btn_print').click(function(){
                $('#printThis').printThis();
            });
        });
    </script>
    <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
@endsection
