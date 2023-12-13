<div class="col-md-12">
	<div class="form-group">
	    <label for="name">Title</label>
	    {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter title', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('title') }}</span>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			    <label for="exampleInputEmail1">Logo</label>
			    {{ Form::file('logo', old('logo'), ['class' => 'form-control', 'required']) }}
		      	<span class='text-danger error'>{{ $errors->first('logo') }}</span>
			</div>
		</div>
		@if(isset($data))
		<div class="col-md-6">
			<img src="{{ env('APP_URL').$data['logo'] }}" style="height: 100px; width: 100px">
		</div>
		@endif
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Platform</label>
	    {{ Form::text('platform', old('platform'), ['class' => 'form-control', 'placeholder' => 'Add Platform','required']) }}
      	<span class='text-danger error'>{{ $errors->first('platform') }}</span>
	</div>
	<div class="form-group">
	    <label for="name">Deadline</label>
	    {{ Form::text('deadline', old('deadline'), ['class' => 'form-control date_picker', "placeholder" => "dd/mm/yyyy", 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('deadline') }}</span>
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Status</label>
	    {{ Form::select('status', ['Design' => 'Design', 'Development' => 'Development', 'Integration' => 'Integration', 'Complete' => 'Complete', 'On Hold' => 'On Hold', 'QA' => 'QA'], old('status'),  ['class' => 'form-control', 'placeholder' => 'Select Status', 'required']) }}
	    <!-- <select class="form-control" name="status">
              <option value="Design">Design</option>
              <option value="Development">Development</option>
              <option value="Integration">Integration</option>
              <option value="Complete">Complete</option>
              <option value="On Hold">On Hold</option>
              <option value="QA">QA</option>
      	</select> -->
      	<span class='text-danger error'>{{ $errors->first('status') }}</span>
	</div>

	<div class="form-group">
	    <label for="exampleInputEmail1">User</label>
	    <select class="form-control" name="user_ids[]" id="userIds" multiple>
            @foreach($employees as $key => $employee)
              <option value="{{ $key }}" @if(isset($data) && in_array($key,$data['user_ids'])) ? selected="selected' : ''@endif">{{ $employee }}</option>
            @endforeach
      	</select>
      	<span class='text-danger error'>{{ $errors->first('user_ids') }}</span>
	</div>
</div>

<button type="submit" class="btn btn-primary">Submit</button>