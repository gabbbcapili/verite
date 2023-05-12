@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Cached Forms')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@endsection

@section('content')

<section id="basic-vertical-layouts">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row mb-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Cached Forms Instructions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <h5>To continue using these forms listed below here are the list of instructions:</h5>
                            <ol class="ms-1 mt-1">
                                <li>Do not clear cache or hard reload.</li>
                                <li>Do not use Incognito mode.</li>
                                <li>Do not logout.</li>
                                <li>Make sure to have internet connection when submitting forms.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title text-center">List of Cached Forms</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <table class="table" id="cache_list_table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Url</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('vendor-script')
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
  <script src="{{ asset('vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
    const PRECACHE = 'precache-v1';
    const urlStartsWith = '/auditForm';

    var table = $('#cache_list_table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });

    function queryCache(){
        var url = [];
        caches.open(PRECACHE).then(function (cache){
            cache.keys().then(function(keys){
                return Promise.all(
                        keys.map(function(k){url.push(k.url); return k.url} )
                    )
            }).then(function(u){ cacheList(url);})
        })
    }

    function cacheList(Items){
       for(var i = 0; i < Items.length; i++){
        var requestUrl = new URL(Items[i]);
            if (requestUrl.pathname.startsWith(urlStartsWith)) {
                table.row.add(['Audit Form', '<a target="_blank" href="'+ Items[i] +'">'+ Items[i] +'</a>']).draw(false);
            }
        }
    }
    $(document).ready(function() {
        queryCache();
    });

</script>

@endsection
