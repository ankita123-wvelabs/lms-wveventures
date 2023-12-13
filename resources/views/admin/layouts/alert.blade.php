@if(Session::has('message'))
	@if(Session::get('type'))
		<script>
	        $(document).ready(function(){
		        toastr.{{ Session::get('type') }}
		        ('{{ Session::get('message') }}');
	        });
	    </script>
    @endif
@endif