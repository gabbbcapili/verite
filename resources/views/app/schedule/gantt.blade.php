@extends('layouts/contentLayoutMaster')

@section('title', 'Gantt Chart')



@section('vendor-style')
  <!-- Vendor css files -->
  <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <style type="text/css">
    html, body{
        height:100%;
        padding:0px;
        margin:0px;
        overflow: hidden;
    }
    .gantt_task_line.primary {
        background-color: rgb(115, 103, 240);
    }
    .gantt_task_line.secondary {
        background-color: rgb(130, 134, 139);
    }
    .gantt_task_line.success {
        background-color: rgb(40, 199, 111);
    }
    .gantt_task_line.info {
        background-color: rgb(0, 207, 232);
    }
    .gantt_task_line.warning {
        background-color: rgb(255, 159, 67);
    }
    .gantt_task_line.danger {
        background-color: rgb(234, 84, 85);
    }
    .gantt_grid_head_add, .gantt_add{
        visibility: hidden;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row g-0">
            <div class="col-12">
                <div id="gantt_here" style='width:100%; height:75vh;'></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
  <script src="{{ asset(mix('vendors/js/calendar/fullcalendar.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">

    gantt.config.date_format = "%Y-%m-%d";
    gantt.config.readonly = true;
    gantt.plugins({
        tooltip: true
    });
    gantt.templates.tooltip_text = function(start,end,task){
        return "<b>Title:</b> "+task.text;
    };
    var data_url = "/schedule/ganttChart/";
    gantt.init("gantt_here");
    gantt.load(data_url);
    gantt.attachEvent("onBeforeLightbox", function (id, mode, event) {
        gantt.config.buttons_right = [];
    });
    gantt.attachEvent("onTaskClick", function(id,e){
        var url ="{{ route('schedule.edit', ':id') }}";
            url = url.replace(':id', id);
        $.ajax({
          url: url,
          method: "GET",
          success:function(result)
          {
            $('#view_modal').html(result);
              $('#view_modal').modal({backdrop: 'static', keyboard: false}).modal('toggle');
              if (feather) {
                feather.replace({
                  width: 14, height: 14
                });
              }
          }
      });

        return true;
    });

    gantt.templates.task_class  = function(start, end, task){
        return task.backgroundColor;
    };
    $(document).on('hidden.bs.modal', '#view_modal', function () {
        console.log('hidden');
          gantt.clearAll(); gantt.load(data_url);
    });
</script>
@endsection


<!--
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Gantt Chart - {{ env('APP_NAME') }}</title>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">

    <style type="text/css">
        html, body{
            height:100%;
            padding:0px;
            margin:0px;
            overflow: hidden;
        }
        .gantt_task_line.primary {
            background-color: rgb(115, 103, 240);
        }
        .gantt_task_line.secondary {
            background-color: rgb(130, 134, 139);
        }
        .gantt_task_line.success {
            background-color: rgb(40, 199, 111);
        }
        .gantt_task_line.info {
            background-color: rgb(0, 207, 232);
        }
        .gantt_task_line.warning {
            background-color: rgb(255, 159, 67);
        }
        .gantt_task_line.danger {
            background-color: rgb(234, 84, 85);
        }
        .gantt_grid_head_add, .gantt_add{
            visibility: hidden;
        }

    </style>
</head>
<body>
<div id="gantt_here" style='width:100%; height:100%;'></div>
<script type="text/javascript">
    gantt.config.date_format = "%Y-%m-%d";
    gantt.config.readonly = true;
    gantt.plugins({
        tooltip: true
    });
    gantt.templates.tooltip_text = function(start,end,task){
        return "<b>Title:</b> "+task.text;
    };
    gantt.init("gantt_here");
    gantt.load("/schedule/ganttChart/");
    gantt.attachEvent("onBeforeLightbox", function (id, mode, event) {
        gantt.config.buttons_right = [];
    });

    gantt.templates.task_class  = function(start, end, task){
        return task.backgroundColor;
    };
</script>
</body> -->
