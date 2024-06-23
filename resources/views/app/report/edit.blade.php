@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Edit Report')

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
    <form action="{{ route('report.update', $report) }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
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
                                <input type="checkbox" name="save_submit" id="save_submit" hidden>
                                <input type="checkbox" name="save_approve" id="save_approve" hidden>
                                <input type="checkbox" name="save_close" id="save_close" hidden>
                                <button type="submit" class="btn btn-primary btn_save d-none"><i data-feather="save"></i> Save</button>
                                @if($request->user()->can('report.saveandcontinue'))
                                <button type="button" class="btn btn-warning save_finish_later"><i data-feather="pocket"></i> Save & Continue Later</button>
                                @endif
                                @if($request->user()->can('report.saveandsubmit') && $report->status == 0)
                                <button type="button" class="btn btn-primary submit" data-title="Are you sure to submit this form?"><i data-feather="check-circle"></i> Save & Submit</button>
                                @endif
                                @if(request()->user()->can('report.saveandapprove') && $report->status == 1)
                                <button type="button" class="btn btn-info approve" data-title="Are you sure to approve this form?"><i data-feather="check-circle"></i> Save & Approve</button>
                                @endif
                                @if(request()->user()->can('report.saveandclose') && $report->status == 2)
                                <button type="button" class="btn btn-success close" data-title="Are you sure to close this form?"><i data-feather="x-square"></i> Save & Close</button>
                                @endif
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                  <h4 class="card-title text-center">Report Reviews</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <table class="datatables-basic table" id="report_form_reviews">
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Target</th>
                              <th>File</th>
                              <th>Message</th>
                              <th>Resolved Notes</th>
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                  <h4 class="card-title text-center">{{  $report->title }} - {!! $report->status_display !!}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Report Title:</label>
                                    <input type="text" name="title" class="form-control" value="{{ $report->title }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{!! $report->google_drive_link ? '<a target="_blank" href="'. $report->google_drive_link .'"> ' : '' !!}Google Drive Link: {!! $report->google_drive_link ? '</a>' : '' !!}</label>
                                    <input type="text" name="google_drive_link" class="form-control" value="{{ $report->google_drive_link }}">
                                </div>
                            </div>
                        </div>
                        @if($report->status >= 2)
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Final PDF: {!! $report->FinalPdfDisplay !!}</label>
                                        <input type="file" accept="application/pdf" name="final_pdf" class="form-control" value="{{ $report->final_pdf }}">
                                    </div>
                                </div>
                            </div>
                        @endif
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
    <script type="text/javascript">
        var table_id = 'report_form_reviews'
        var table_title = 'Audit Form Reviews';
        var table_route = {
              url: '{{ route('report.review.index', $report) }}',
              data: function (data) {
                    // data.role = $("#role").val();
                }
            };
          var columnns = [
                { data: 'id', name: 'id'},
                { data: 'target_group', name: 'target_group'},
                { data: 'file', name: 'file'},
                { data: 'message', name: 'message'},
                { data: 'resolve_notes', name: 'resolve_notes'},
                { data: 'statusDisplay', name: 'status'},
                { data: 'created_at', name: 'created_at'},
                { data: 'updated_at', name: 'updated_at'},
                { data: 'action', name: 'action', 'orderable' : false, 'printable' : false}
            ];
          @if(request()->user()->can('report.review'))
            var buttons = [
                {
                    text: '<i data-feather="plus"></i> Create New',
                    className: 'btn btn-primary modal_button',
                    attr: {
                        'data-action': '{{ route('report.review.create', $report) }}'
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
                    $('#save_approve').prop('checked', true);
                    $('.btn_save').click();
                }
              });
        });

        $(document).on('click', '.close', function(){
            Swal.fire({
                title:$(this).data('title'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#save_close').prop('checked', true);
                    $('.btn_save').click();
                }
              });
        });

        $(document).on('click', '.submit', function(){
            Swal.fire({
                title:$(this).data('title'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
              }).then((result) => {
                if (result.isConfirmed) {
                    $('#save_submit').prop('checked', true);
                    $('.btn_save').click();
                }
              });
        });

        
    </script>
    <script src="{{ asset('js/scripts/tables/table-datatables-basic.js') }}"></script>
@endsection
