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
    <div class="col-lg-6">
        <section class="panel">
            <header class="panel-heading">
                Create
            </header>
            <div class="panel-body">
                {{ Form::open(['url' => route('admin.employees.store'), 'method' => 'POST']) }}
                    @include('admin.employee.form')
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop