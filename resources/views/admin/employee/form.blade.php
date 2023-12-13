<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group">
		    <label for="name">Emp ID</label>
		    {{ Form::text('emp_id', old('emp_id'), ['class' => 'form-control', 'placeholder' => 'Enter emp id', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('emp_id') }}</span>
		</div>
		<div class="form-group">
		    <label for="name">Name</label>
		    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter name', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('name') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Email address</label>
		    {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Enter email address', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('email') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputPassword1">Password</label>
		    <input type="password" class="form-control" name="password" placeholder="Password">
		    <span class='text-danger error'>{{ $errors->first('password') }}</span>
		</div>
		<div class="col-md-12">
			<div class="form-group col-md-6">
			    <label for="exampleInputEmail1">Photo</label><small> (Upload photo to register employee face.)</small>
			    {{ Form::file('image', old('image'), ['class' => 'form-control', 'required']) }}
			    <span class='text-danger error'>{{ $errors->first('image') }}</span>
			</div>
			<div class="form-group col-md-6">
			    <label for="exampleInputEmail1">Special Case</label>
			    <!-- {{ Form::checkbox('special_case', old('special_case'), ['class' => 'form-control', isset($data) && $data['special_case'] == 1 ? 'checked' : '']) }} -->
			    <input type="checkbox" name="special_case" {{isset($data) && $data['special_case'] ? 'checked' : ''}} class="form-control" />
			    <span class='text-danger error'>{{ $errors->first('special_case') }}</span>
			</div>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Salary</label><small> (Monthly)</small>
		    {{ Form::number('salary', old('salary'), ['class' => 'form-control', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('salary') }}</span>
		</div>

		<div class="form-group">
		    <label for="exampleInputEmail1">Phone</label></small>
		    {{ Form::text('phone', old('phone'), ['class' => 'form-control']) }}
		    <span class='text-danger error'>{{ $errors->first('phone') }}</span>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		    <label for="position">Position</label>
		    {{ Form::text('position', old('position'), ['class' => 'form-control', 'placeholder' => 'Enter position', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('position') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Reporting Manager</label>
		    {{ Form::text('reporting_manager', old('reporting_manager'), ['class' => 'form-control', 'placeholder' => 'Enter reporting manager', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('reporting_manager') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Joining Date</label>
		    {{ Form::text('joining_date', old('joining_date'), ['class' => 'form-control date_picker', "placeholder" => "dd/mm/yyyy", 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('joining_date') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Date of Birth</label>
		    {{ Form::text('dob', old('dob'), ['class' => 'form-control date_picker', "placeholder" => "dd/mm/yyyy", 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('dob') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Address</label>
		    {{ Form::textarea('address', old('address'), ['class' => 'form-control', "placeholder" => "Enter Address", 'rows' => 5]) }}
		    <span class='text-danger error'>{{ $errors->first('address') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Pan</label>
		    {{ Form::text('pan', old('pan'), ['class' => 'form-control', "placeholder" => "Enter pan",]) }}
		    <span class='text-danger error'>{{ $errors->first('pan') }}</span>
		</div>

	</div>
</div>
<br/>
<header class="panel-heading">
    Bank Details
</header>
<br/>
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group">
		    <label for="exampleInputEmail1">Bank Name</label>
		    {{ Form::text('bank_name', old('bank_name'), ['class' => 'form-control', "placeholder" => "Enter bank name",]) }}
		    <span class='text-danger error'>{{ $errors->first('bank_name') }}</span>
		</div>
		<div class="form-group">
		    <label for="exampleInputEmail1">Account Holder</label>
		    {{ Form::text('account_holder', old('account_holder'), ['class' => 'form-control', "placeholder" => "Enter account holder",]) }}
		    <span class='text-danger error'>{{ $errors->first('account_holder') }}</span>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		    <label for="exampleInputEmail1">Account Number</label>
		    {{ Form::text('account_number', old('account_number'), ['class' => 'form-control', "placeholder" => "Enter account number",]) }}
		    <span class='text-danger error'>{{ $errors->first('account_number') }}</span>
		</div>
	</div>
<div class="col-md-12">
<br/>
<a href="{{ route('admin.employees.index') }}" class="btn btn-warning">Cancel</a>
<button type="submit" class="btn btn-success">Submit</button>