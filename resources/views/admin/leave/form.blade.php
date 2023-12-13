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

<button type="submit" class="btn btn-primary">Submit</button>