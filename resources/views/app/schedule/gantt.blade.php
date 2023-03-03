@extends('layouts/contentLayoutMaster')

@section('title', 'Gantt Chart')

@section('vendor-style')
<link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
@endsection

@section('page-style')
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
    gantt.init("gantt_here");
    gantt.load("/schedule/ganttChart/");
    gantt.attachEvent("onBeforeLightbox", function (id, mode, event) {
        gantt.config.buttons_right = [];
    });

    gantt.templates.task_class  = function(start, end, task){
        return task.backgroundColor;
    };
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
