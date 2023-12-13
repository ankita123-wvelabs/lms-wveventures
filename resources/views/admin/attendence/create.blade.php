@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
	<h3>
		Attendence List
	</h3>
</div>
<div class="row">
	<div class="col-sm-12">
	<section class="panel">
		<div class="row panel-body">
			<form method="POST" id="getAttendenceReport">
				@csrf
				<div class="form-group col-md-3">
					<label for="name">Employees</label>
					<select class="form-control" name="user" id="user">
						<option value="">Select Employee</option>
						<option value="all">All</option>
						@foreach($users as $key => $user)
							<option value="{{ $user['user']['id'] }}"> {{ $user['user']['name'] }} </option>
						@endforeach
					</select>
					<span class='text-danger error'>{{ $errors->first('user') }}</span>
				</div>

				<div class="form-group col-md-3">
					<label for="name">Type</label>
					<select class="form-control" name="type" id="attendenceType">
						<option value="">Select Attendence Type</option>
						<option value="today">Today</option>
						<option value="previous_week">Previous Week</option>
						<option value="current_week">Current Week</option>
						<option value="previous_month">Prevoius Month</option>
						<option value="current_month">Current Month</option>
					</select>
					<span class='text-danger error'>{{ $errors->first('type') }}</span>
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
					{{-- <a href="{{ route('admin.reports.generate') }}" class="form-control btn btn-success">Export PDF</a> --}}
				</div>
			</form>
			<span>10:36</span>
		</div>
	</section>
	<section class="panel attendenceReport">
		<div class="panel-body">
			<div class="adv-table">
				<table  class="display table table-bordered table-striped" id="dataTable">
					<thead>
						<tr>
							<th width="20%">Name</th>
							<th width="20%">Date</th>
							<th width="40%">Logs</th>
							<th width="20%">Total Hour</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
							@foreach($user['attendence'] as $attendence)
								<tr>
									<td>{{ isset($user['user']['name'])?$user['user']['name']:"" }}</td>
									<td>{{ isset($attendence['date']) ? $attendence['date'] : '-' }}</td>
									<td>{{ isset($attendence['timing']) ? implode(', ', $attendence['timing']) : '-' }}</td>
									<td>{{ isset($attendence['present_time']) ? $attendence['present_time'] : '-' }}</td>
								</tr>
							@endforeach
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">

{{ Html::style('admin/js/data-tables/DT_bootstrap.css') }}
{{ Html::style('admin/js/sweetalert/sweetalert.css') }}
@stop
@section('js')
	{{-- {{ Html::script('admin/js/advanced-datatable/js/jquery.dataTables.js') }} --}}
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
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

			//$('#dataTable').dataTable({});
			$('#dataTable').DataTable( {
				"pageLength": 25,
				"searching": false,
				"lengthChange": true,
				//dom: 'Bfrtip', // Remove page Length
				dom: 'lBfrtip',
				buttons: [
					{ 
						extend: 'copyHtml5',
						className: 'form-control btn btn-success',
						titleAttr: 'Copy Report',
						text: 'Copy Report',
						init: function(api, node, config) {
						   $(node).removeClass('dt-button buttons-copy buttons-html5')
						}
					},
					{ extend:
						'excelHtml5',
						className: 'form-control btn btn-success',
						titleAttr: 'Export in Excel',
						text: 'Export Excel',
						init: function(api, node, config) {
						   $(node).removeClass('dt-button buttons-copy buttons-html5')
						}
					},
					{ 
						extend: 'pdfHtml5',
						className: 'form-control btn btn-success',
						titleAttr: 'Export in PDF',
						text: 'Export PDF',
						orientation: 'portrait', // portrait, landscape
						pageSize: 'LEGAL',
						//alignment: 'center',
						customize: function(doc) {
							// doc.defaultStyle.alignment = 'center';
							// doc.styles.tableHeader.alignment = 'center';
							doc.content[1].margin = [ 100, 0, 100, 0 ] //left, top, right, bottom
						},
						init: function(api, node, config) {
						   $(node).removeClass('dt-button buttons-copy buttons-html5')
						}
					}
				]
			} );

			$( '#getAttendenceReport' ).on('submit', function(e) {
					e.preventDefault();

				var user = $(this).find('select[name=user]').val();
				var type = $(this).find('select[name=type]').val();
				// var keyword = $(this).find('input[name=search]').val();
				   
				$.ajax({
				  type: "post",
				  url: '{{ route('admin.attendence.post') }}',
				  data: { user: user, type: type,  _token: '{{ csrf_token() }}'},
				  beforeSend: function() {
					$('.overlay').show();
					$('#getReportButton').attr('disabled', 'disabled');
				  },
				  success: function (resp) {
					if(resp.code == 200) {
						$('.attendenceReport').html(resp.data);
						$('#dataTable').DataTable( {
							"pageLength": 25,
							"searching": false,
							"lengthChange": true,
							//dom: 'Bfrtip', // Remove page Length
							dom: 'lBfrtip',
							buttons: [
								{ 
									extend: 'copyHtml5',
									className: 'form-control btn btn-success',
									titleAttr: 'Copy Report',
									text: 'Copy Report',
									init: function(api, node, config) {
									   $(node).removeClass('dt-button buttons-copy buttons-html5')
									}
								},
								{ extend:
									'excelHtml5',
									className: 'form-control btn btn-success',
									titleAttr: 'Export in Excel',
									text: 'Export Excel',
									init: function(api, node, config) {
									   $(node).removeClass('dt-button buttons-copy buttons-html5')
									}
								},
								{ 
									extend: 'pdfHtml5',
									className: 'form-control btn btn-success',
									titleAttr: 'Export in PDF',
									text: 'Export PDF',
									orientation: 'portrait', // portrait, landscape
									pageSize: 'LEGAL',
									//alignment: 'center',
									customize: function(doc) {
										// doc.defaultStyle.alignment = 'center';
										// doc.styles.tableHeader.alignment = 'center';
										doc.content[1].margin = [ 100, 0, 100, 0 ] //left, top, right, bottom
									},
									init: function(api, node, config) {
									   $(node).removeClass('dt-button buttons-copy buttons-html5')
									}
								}
							]
						} );
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