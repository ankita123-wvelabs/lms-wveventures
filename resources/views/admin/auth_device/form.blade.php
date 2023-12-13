<div class="col-md-6">
	<div class="form-group">
	    <label for="name">Device Id</label>
	    {{ Form::text('device_id', old('device_id'), ['class' => 'form-control', 'placeholder' => 'Enter Device Id', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_id') }}</span>
	</div>
	<div class="form-group">
	    <label for="name">Device Name</label>
	    {{ Form::text('device_name', old('device_name'), ['class' => 'form-control', 'placeholder' => 'Enter Device Name', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_name') }}</span>
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Device App Version Code</label>
	    {{ Form::text('device_app_version_code', old('device_app_version_code'), ['class' => 'form-control', 'placeholder' => 'Enter Device App Version Code address', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_app_version_code') }}</span>
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Device App Version Name</label>
	    {{ Form::text('device_app_version_name', old('device_app_version_name'), ['class' => 'form-control', 'placeholder' => 'Enter Device App Version Name', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_app_version_name') }}</span>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	    <label for="exampleInputEmail1">Device Wifi Mac Address</label>
	    {{ Form::text('device_wifi_mac_address', old('device_wifi_mac_address'), ['class' => 'form-control', 'placeholder' => 'Enter Device Wifi Mac Address', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_wifi_mac_address') }}</span>
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Device Bluetooth Mac Address</label>
	    {{ Form::text('device_bluetooth_mac_address', old('device_bluetooth_mac_address'), ['class' => 'form-control', 'placeholder' => 'Enter Device Bluetooth Mac Address', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('device_bluetooth_mac_address') }}</span>
	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">Location</label>
	    {{ Form::text('location', old('location'), ['class' => 'form-control', 'placeholder' => 'Enter Location', 'required']) }}
	    <span class='text-danger error'>{{ $errors->first('location') }}</span>
	</div>
</div>
<div class="clearfix"></div>
<button type="submit" class="btn btn-primary">Submit</button>