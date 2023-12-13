@php 
$data = \Session::get('report');
@endphp
@if(sizeof($users) > 0)
<div class="panel-body">
    <div class="adv-table">
        <table  class="display table table-bordered table-striped" id="dataTable">
	        <thead>
		        <tr>
		        	@if(0)
			            <th width="20%">Name</th>
			            <th width="40%">Month</th>
			            <th width="20%">Total Hour</th>
		            @else
		            	<th width="20%">Name</th>
			            <th width="20%">Date</th>
			            <th width="40%">Logs</th>
			            <th width="20%">Total Hour</th>
		            @endif
		        </tr>
	        </thead>
	        <tbody>
	        	@foreach($users as $user)
	        		@if(0)
			        	<tr>
				            <td>{{ $user['user']['name'] }}</td>
				            <td>{{ isset($user['month']) ? $user['month'] : '-' }}</td>
				            <td>{{ isset($user['present_time']) ? $user['present_time'] : '-' }}</td>
				        </tr>
	        		@else
		        		@foreach($user['attendence'] as $attendence)
				        	<tr>
					            <td>{{ isset($user['user']['name'])?$user['user']['name']:"" }}</td>
						        <td>{{ isset($attendence['date']) ? $attendence['date'] : '-' }}</td>
					            <td>{{ isset($attendence['timing']) ? implode(', ', $attendence['timing']) : '-' }}</td>
					            <td>{{ isset($attendence['present_time']) ? $attendence['present_time'] : '-' }}</td>
					        </tr>
			        	@endforeach
	        		@endif
		        @endforeach
	        </tbody>
        </table>
    </div>
</div>
@else
<div class="panel-body">
    <div class="adv-table">
    No data available
    </div>
</div>
@endif