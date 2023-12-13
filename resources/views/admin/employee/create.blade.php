@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Employees
    </h3>
    {{-- <ul class="breadcrumb">
        <li>
            <a href="#">Forms</a>
        </li>
        <li class="active"> Forms Layouts </li>
    </ul> --}}
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Create
            </header>
            <div class="panel-body">
                {{ Form::open(['url' => route('admin.employees.store'), 'method' => 'POST', 'files' => true]) }}
                    @include('admin.employee.form')
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop
@section('css')
    {{ Html::style('admin/js/bootstrap-datepicker/css/datepicker-custom.css') }}
@stop
@section('js')
    {{ Html::script('admin/js/bootstrap-inputmask/bootstrap-inputmask.min.js') }}
    {{ Html::script('admin/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}

    <script>
    $( ".date_picker" ).datepicker({
        format: "dd-mm-yyyy",
        weekStart: 0,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        rtl: true,
        orientation: "auto"
    });
    </script>
@stop