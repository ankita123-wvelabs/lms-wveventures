<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group">
		    <label for="name">Date</label>
		    {{ Form::text('date', old('date'), ['class' => 'form-control date_picker', 'placeholder' => 'dd/mm/yyyy', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('date') }}</span>
		</div>

		<div class="form-group">
		    <label for="name">Image</label>
		    {{ Form::file('image', old('image'), ['class' => 'form-control', 'placeholder' => 'Select Image', 'required']) }}
		    <span class='text-danger error'>{{ $errors->first('image') }}</span>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
		    <label for="exampleInputEmail1">Info</label>
		    {{ Form::textarea('info', old('info'), ['class' => 'form-control', "placeholder" => "Enter Description", 'required', 'rows' => 3]) }}
		    <span class='text-danger error'>{{ $errors->first('info') }}</span>
		</div>
	</div>
</div>
<a href="{{ route('admin.holidays.index') }}" class="btn btn-warning">Cancel</a>
<button type="submit" class="btn btn-success">Submit</button>