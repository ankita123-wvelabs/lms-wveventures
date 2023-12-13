@extends('admin.layouts.layout')
@section('content')
<!-- <div class="row states-info">
    <div class="col-md-3">
        <div class="panel red-bg">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="col-xs-8">
                        <span class="state-title"> Dollar Profit Today </span>
                        <h4>$ 23,232</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel blue-bg">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-tag"></i>
                    </div>
                    <div class="col-xs-8">
                        <span class="state-title">  Copy Sold Today  </span>
                        <h4>2,980</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel green-bg">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-gavel"></i>
                    </div>
                    <div class="col-xs-8">
                        <span class="state-title">  New Order  </span>
                        <h4>5980</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel yellow-bg">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">
                        <i class="fa fa-eye"></i>
                    </div>
                    <div class="col-xs-8">
                        <span class="state-title">  Unique Visitors  </span>
                        <h4>10,000</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-lg-6">
        <section class="panel">
            <header class="panel-heading">
                Leave Balance
            </header>
            <div class="panel-body">
                {{ Form::model($config, ['url' => route('admin.leave.balance'), 'method' => 'POST']) }}
                    <div class="form-group">
                        <label for="leave_balance">Balance</label>
                        {{ Form::text('leave_balance', old('leave_balance'), ['class' => 'form-control', 'placeholder' => 'Enter Leave Balance', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('leave_balance') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="prof_tax">Prof. Tax</label>
                        {{ Form::text('prof_tax', old('prof_tax'), ['class' => 'form-control', 'placeholder' => 'Enter Prof. tax', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('prof_tax') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="std_deduction">Standard Deduction</label>
                        {{ Form::text('std_deduction', old('std_deduction'), ['class' => 'form-control', 'placeholder' => 'Enter Standard Deduction', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('std_deduction') }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Submit</button>
                    <button type="submit" class="btn btn-success" value="credit_balance" name="submit">Credit Yearly Balance</button>
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <section class="panel">
            <header class="panel-heading">
                Update User Mis Punch
            </header>
            <div class="panel-body">
                {{ Form::open(['url' => route('admin.user.mispunch'), 'method' => 'POST']) }}
                     <div class="form-group">
                        <label for="leave_balance">Balance</label>
                        {{ Form::select('user_id',$users, old('user_id'), ['class' => 'form-control', 'id' => 'userId', 'onchange' => 'getLog(this.value)', 'placeholder' => 'Select User', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('user_id') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="name">Date</label>
                        {{ Form::date('date', old('date'), ['class' => 'form-control', 'id' => 'date', 'onchange' => 'getLog(this.value)', 'placeholder' => 'Enter date', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('date') }}</span>
                    </div>
                    <div class="form-group hidden" id="time">
                        <label for="leave_balance">Time</label>
                        {{ Form::text('time', old('time'), ['class' => 'form-control', 'id' => 'log', 'placeholder' => 'Enter Time', 'required']) }}
                        <span class='text-danger error'>{{ $errors->first('time') }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Submit</button>
                    <button type="submit" class="btn btn-success" value="credit_balance" name="submit">Update</button>
                {{ Form::close() }}
            </div>
        </section>
    </div>
</div>
@stop
@section('js')
<script type="text/javascript">
    function getLog() {
        var userId = $('#userId').val();
        var date = $('#date').val();
        if(userId && date) {
            $.ajax({
                type: "post",
                url: '{{ route('admin.get.user.mispunch') }}',
                data: { userId: userId, date: date, _token: '{{ csrf_token() }}',},
                success: function (resp) {
                    $('#time').removeClass('hidden');
                    if(resp.data) {
                        $('#log').val(resp.data);
                    } else {
                        $('#log').val('');
                    }
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        }
    }
</script>
@stop