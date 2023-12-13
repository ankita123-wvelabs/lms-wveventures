<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<style>
		.loglist ul{margin: 0; padding: 0; list-style: none;}
		.loglist ul li{display: inline-block; background: #777; color: #fff; border-radius: 5px; padding: 10px; margin-right: 10px; margin-bottom: 5px; margin-top: 5px;}
	</style>
	@if(isset($temp))
		@foreach($temp as $user)
			<section class="panel">
			    <header class="panel-heading">{{ $user['user']['name'] }}</header>
			    <div class="panel-body" style="display: block;">
			        <section id="unseen">
			            <table class="table table-bordered table-striped table-condensed">
                    		@if(isset($user['attendence']))
                    			@foreach($user['attendence'] as $attendence)
			                <thead>
			                <tr>
			                    <th>Log {{$attendence['date']}}</th>
			                </tr>
			                </thead>
			                <tbody>
			                <tr>
			                    <td class="loglist">
			                    	<ul>
	                    				@foreach($attendence['timing'] as $time)
	                    					<li>{{ $time }}</li>
	                    				@endforeach
			                    	</ul>
			                    </td>
			                </tr>
			                <tr>
			                	<td><strong>Total : {{ $attendence['present_time'] }} HR</strong></td>
			                </tr>
			                </tbody>
                    			@endforeach
                    		@endif
			            </table>
			        </section>
			    </div>
			</section>
		@endforeach
	@endif
</body>
</html>