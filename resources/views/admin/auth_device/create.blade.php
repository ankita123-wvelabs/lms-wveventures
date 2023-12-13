@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Auth Device
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
                {{ Form::open(['url' => route('admin.auth-devices.store'), 'method' => 'POST']) }}
                    @include('admin.auth_device.form')
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop