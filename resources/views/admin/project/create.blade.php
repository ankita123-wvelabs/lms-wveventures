@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Project
    </h3>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Create
            </header>
            <div class="panel-body">
                {{ Form::open(['url' => route('admin.projects.store'), 'method' => 'POST', 'files' => true]) }}
                    @include('admin.project.form')
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop

@section('css')
    {{ Html::style('admin/select2/dist/css/select2.min.css') }}
    {{ Html::style('admin/js/bootstrap-datepicker/css/datepicker-custom.css') }}
@stop

@section('js')
<!-- jquery.inputmask -->
    {{ Html::script('admin/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('admin/js/bootstrap-inputmask/bootstrap-inputmask.min.js') }}
    {{ Html::script('admin/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    
    <script type="text/javascript">
      $('#userIds').select2();
    </script>

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