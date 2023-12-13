@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Change Password
    </h3>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Update
            </header>
            <div class="panel-body">
                {{ Form::open(['url' => route('admin.update.password'), 'method' => 'POST']) }}
                    <div class="col-md-12">
						<div class="form-group">
						    <label for="exampleInputPassword1">Old Password</label>
						    <input type="password" class="form-control" name="old_password" placeholder="Old Password">
						    <span class='text-danger error'>{{ $errors->first('old_password') }}</span>
						</div>
						<div class="form-group">
						    <label for="exampleInputPassword1">Password</label>
						    <input type="password" class="form-control" name="password" placeholder="Password">
						    <span class='text-danger error'>{{ $errors->first('password') }}</span>
						</div>
						<div class="form-group">
						    <label for="exampleInputPassword1">Confirm Password</label>
						    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
						    <span class='text-danger error'>{{ $errors->first('password_confirmation') }}</span>
						</div>
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop