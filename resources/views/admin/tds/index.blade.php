@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Tds List
    </h3>
</div>
<div class="row">
    <div class="col-sm-12">
    	<section class="panel">
		<div class="row panel-body">
			<form method="POST" id="getReport">
				@csrf
				<div class="form-group col-md-3">
				    <label for="name">Year</label>
				    <select class="form-control" name="year" id="year">
				    	@foreach($years as $key => $year)
				    		<option value="{{ $year }}" @if($year == $request['year']) selected @endif> {{ $year }} </option>
				    	@endforeach
				    </select>
				    <span class='text-danger error'>{{ $errors->first('year') }}</span>
				</div>

				<div class="form-group col-md-3">
				    <label for="name">Month</label>
				    <select class="form-control" name="month" id="month">
				    	@foreach($months as $key => $month)
				    		<option value="{{ $key }}" @if($key == $request['month']) selected @endif> {{ $month }} </option>
				    	@endforeach
				   </select>
				    <span class='text-danger error'>{{ $errors->first('month') }}</span>
				</div>

				<!-- <div class="form-group col-md-3">
				    <label for="name">Year</label>
				    <input type="text" class="form-control" id="searchUser" name="search" value="" placeholder="Search User">
				</div> -->

				<div class="form-group col-md-2" style="margin-top: 25px">
					<button type="submit" id="getReportButton" class="form-control btn btn-primary">Submit</button>
				</div>
				<div class="form-group col-md-2" style="margin-top: 25px">
					<!-- <button id="generateReportButton" class="form-control btn btn-primary">Download PDF</button> -->
					<a href="{{ route('admin.tds.salary.processing') }}" class="form-control btn btn-success">Processing</a>
				</div>
            </form>
		</div>
	</section>
	<div class="page-heading">
	    <h3>
	        Get User Salary
	    </h3>
	</div>
	<section class="panel">
		<div class="row panel-body">
			<form method="POST" id="getSalaryReport">
				@csrf
				<div class="form-group col-md-3">
				    <label for="name">Employees</label>
				    <select class="form-control" name="user" id="user">
				    	<option value="">Select Employee</option>
				    	@foreach($users as $key => $user)
				    		<option value="{{ $user['id'] }}"> {{ $user['name'] }} </option>
				    	@endforeach
				    </select>
				    <span class='text-danger error'>{{ $errors->first('user') }}</span>
				</div>

				<div class="form-group col-md-3">
				    <label for="name">Year</label>
				    <select class="form-control" name="year" id="year">
				    	@foreach($years as $key => $year)
				    		<option value="{{ $year }}" @if($year == $request['year']) selected @endif> {{ $year }} </option>
				    	@endforeach
				    </select>
				    <span class='text-danger error'>{{ $errors->first('year') }}</span>
				</div>
				<!-- <div class="form-group col-md-3">
				    <label for="name">Year</label>
				    <input type="text" class="form-control" id="searchUser" name="search" value="" placeholder="Search User">
				</div> -->

				<div class="form-group col-md-2" style="margin-top: 25px">
					<button type="submit" id="getReportButton" class="form-control btn btn-primary">Submit</button>
				</div>
				<div class="form-group col-md-2" style="margin-top: 25px">
					<!-- <button id="generateReportButton" class="form-control btn btn-primary">Download PDF</button> -->
					<a href="{{ route('admin.reports.generate') }}" class="form-control btn btn-success">Export PDF</a>
				</div>
            </form>
		</div>
	</section>
	<section class="panel reportData">
		<div class="panel-body">
		    <div class="adv-table">
		        <table  class="display table table-bordered table-striped" id="dataTable">
			        <thead>
				        <tr>
				            <th width="20%">Name</th>
				            <th width="15%">Tax. income</th>
				            <th width="10%">Tds</th>
				            <th width="15%">Monthly Tds</th>
				            <th width="15%">Pervoius Month Salary</th>
				            <th width="10%">Status</th>
				            <th width="15%">Salary Slip</th>
				        </tr>
			        </thead>
			        <tbody>
			        	@foreach($users as $key => $user)
			        	<tr>
				            <td>{{ $user['name'] }}</td>
				            <td>{{ number_format(round($user['tax_income']),2) }}</td>
				            <td>{{ number_format(round($user['tds']),2) }}</td>
				            <td>{{ number_format(round($user['monthly_tds']),2) }}</td>
				            <td>{{ number_format(round($user['net_salary']),2) }}</td>
				            <td style="color: {{ $user['status'] == 2 ? 'Red' : ($user['status'] == 0 ? 'Orange' : 'Green')}}">{{ $user['status'] == 2 ? 'To be Processing' : ($user['status'] == 0 ? 'Processing' : 'Processed') }}</td>
				            <td><a href="{{ route('admin.tds.salary.slip.view', [$user['id'], $request['month']]) }}" target="_blank" />View</td>
				        </tr>
				        @endforeach
			        </tbody>
		        </table>
		    </div>
	    </div>
	</section>
	@include('admin.layouts.overlay')
	</div>
</div>
@stop
@section('css')
{{ Html::style('admin/js/advanced-datatable/css/demo_page.css') }}
{{ Html::style('admin/js/advanced-datatable/css/demo_table.css') }}
{{ Html::style('admin/js/data-tables/DT_bootstrap.css') }}
{{ Html::style('admin/js/sweetalert/sweetalert.css') }}
@stop
@section('js')
	{{ Html::script('admin/js/advanced-datatable/js/jquery.dataTables.js') }}
	{{ Html::script('admin/js/data-tables/DT_bootstrap.js') }}
<!--dynamic table initialization -->
	{{ Html::script('admin/js/dynamic_table_init.js') }}
	{{ Html::script('admin/js/sweetalert/sweetalert.min.js') }}
	{{ Html::script('js/fnStandingRedraw.js') }}
	{{ Html::script('js/delete_script.js') }}
	{{ Html::script('js/approve_reject_leave.js') }}

	<script type="text/javascript">
		$(document).ready(function() {
		  	$('.overlay').hide();

		  	$('#dataTable').dataTable({});

			$( '#getReport' ).on('submit', function(e) {
			        e.preventDefault();

		        var year = $(this).find('select[name=year]').val();
		        var month = $(this).find('select[name=month]').val();
		        var keyword = $(this).find('input[name=search]').val();
			       
				$.ajax({
				  type: "post",
				  url: '{{ route('admin.tds.salary') }}',
				  data: { year: year, month: month, keyword: keyword,  _token: '{{ csrf_token() }}'},
				  beforeSend: function() {
				  	$('.overlay').show();
				    $('#getReportButton').attr('disabled', 'disabled');
				  },
				  success: function (resp) {
				    if(resp.code == 200) {
				        $('.reportData').html(resp.data);
				        $('#dataTable').dataTable({});
				    }
				  },
				  error: function (data, textStatus, errorThrown) {
				      console.log(data);
				  },
				  complete: function() {
				  	$('.overlay').hide();
				    $('#getReportButton').removeAttr('disabled');
				  },	
		        });
		    });

		    $( '#getSalaryReport' ).on('submit', function(e) {
			        e.preventDefault();

		        var year = $(this).find('select[name=year]').val();
		        var user = $(this).find('select[name=user]').val();
		        var keyword = $(this).find('input[name=search]').val();
			       
				$.ajax({
				  type: "post",
				  url: '{{ route('admin.tds.salary.user') }}',
				  data: { year: year, user: user, keyword: keyword,  _token: '{{ csrf_token() }}'},
				  beforeSend: function() {
				  	$('.overlay').show();
				    $('#getReportButton').attr('disabled', 'disabled');
				  },
				  success: function (resp) {
				    if(resp.code == 200) {
				        $('.reportData').html(resp.data);
				        $('#dataTable').dataTable({});
				    }
				  },
				  error: function (data, textStatus, errorThrown) {
				      console.log(data);
				  },
				  complete: function() {
				  	$('.overlay').hide();
				    $('#getReportButton').removeAttr('disabled');
				  },	
		        });
		    });
		});
	</script>
@stop