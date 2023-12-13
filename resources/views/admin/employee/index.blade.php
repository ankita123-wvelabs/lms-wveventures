@extends('admin.layouts.layout')
@section('content')
<div class="page-heading">
    <h3>
        Employees List
    </h3>
</div>
<div class="row">
    <div class="col-sm-12">
	<section class="panel">
	        <header class="panel-heading">
	            Dynamic Table
	            <span class="tools pull-right">
	                <a href="javascript:;" class="fa fa-chevron-down"></a>
	                <a href="javascript:;" class="fa fa-times"></a>
	             </span>
	        </header>
	    <div class="panel-body">
		    <div class="adv-table">
		        <table  class="display table table-bordered table-striped" id="dataTable">
			        <thead>
				        <tr>
				        	<th class="text-center">
		                        <div class="checkbox">
		                            <input type="checkbox" id="selectall" class="select_check_box">
		                            <label for="selectall"></label>
		                        </div>
		                    </th>
				            <th>Name</th>
				            <th>Email</th>
				            <th>Leave Balance</th>
				            <th>LWP</th>
				            <th>Action</th>
				        </tr>
			        </thead>
			        <tbody></tbody>
			       {{--  <tfoot>
				        <tr>
				        	<th class="text-center">
		                        <div class="checkbox">
		                            <input type="checkbox" id="selectall" class="select_check_box">
		                            <label for="selectall"></label>
		                        </div>
		                    </th>
				            <th>Name</th>
				            <th>Email</th>
				            <th>Action</th>
				        </tr>
			        </tfoot> --}}
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

	<script type="text/javascript">

		var title = "Are you sure to delete this record?";
		var text = "You will not be able to recover this record";
		var type = "warning";
		var delete_path = "{{ URL::route('admin.employees.delete') }}";
		var token = "{{ csrf_token() }}";

		$('.delete_record').click(function(){
		    var delete_id = $('#dataTable tbody input[type=checkbox]:checked');
		    checkLength(delete_id);
		});

		// $(function()
		// {
		    var dataTable = $('#dataTable').dataTable({
		        "bProcessing": false,
		        "bServerSide": true,
		        "autoWidth": false,
		        "bSearching": true,
		        "aaSorting": [
		            [1, "asc"]
		        ],
		        "sAjaxSource": "{{ URL::route('admin.employees.index') }}",
		        "fnServerParams": function ( aoData ) {
		            aoData.push({ "name": "act", "value": "fetch" });
		            server_params = aoData;
		        },
		        "aoColumns": [
		        {
		            mData: "id",
		            bSortable: false,
		            bVisible: true,
		            sWidth: "5%",
		            sClass: 'text-center',
		            mRender: function(v, t, o)
		            {
		                return '<div class="checkbox">'
		                            +' <input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'"/>'
		                            +'<span class="label-text"></span></div>';
		            }
		        },
		        { "mData": "name",sWidth: "20%",bSortable: true,},
		        { "mData": "email",sWidth: "20%",bSortable: true,},
		        { "mData": "leave_balance",sWidth: "20%",bSortable: true,},
		        { "mData": "count",sWidth: "20%",bSortable: true,},
		        {
		            mData: null,
		            bSortable: false,
		            sWidth: "25%",
		            sClass: "text-center",
		            mRender: function(v, t, o) {

		                var editurl = '{{ route("admin.employees.edit", ":id") }}';
	            		editurl = editurl.replace(':id',o['id']);

		                var act_html = "<div>"
		                                +"<a class='btn btn-info' href='"+editurl+"' data-toggle='tooltip' title='Edit' data-placement='top' class='p-5'><i class='fa fa-pencil'></i></a> "
		                                +"<button class='btn btn-danger' onclick=\"deleteRecord('"+delete_path+"','"+title+"','"+text+"','"+token+"','"+type+"',"+o['id']+")\" data-toggle='tooltip' title='Delete' data-placement='top'><i class='fa fa-trash-o'></i></button>"
		                                +"</div>"
		                return act_html;
		            }
		        },
		        ],
		        fnPreDrawCallback : function() {
		            $(".overlay").show();
		        },
		        fnDrawCallback : function (oSettings) {
		           $('.overlay').hide();
		        }
		    });
		    dataTable.fnSetFilteringDelay(1000);
		// });

		$(".select_check_box").on('click', function(){
		    var is_checked = $(this).is(':checked');
		    $(this).closest('table').find('tbody tr td:first-child input[type=checkbox]').prop('checked',is_checked);
		    $(".select_check_box").prop('checked',is_checked);
		});
	</script>
@stop