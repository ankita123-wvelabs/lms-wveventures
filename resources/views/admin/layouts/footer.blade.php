{{ Html::script('admin/js/jquery-1.10.2.min.js') }}
{{ Html::script('admin/js/jquery-ui-1.9.2.custom.min.js') }}
{{ Html::script('admin/js/jquery-migrate-1.2.1.min.js') }}
{{ Html::script('admin/js/bootstrap.min.js') }}
{{ Html::script('admin/js/modernizr.min.js') }}
{{ Html::script('admin/js/jquery.nicescroll.js') }}

<!--easy pie chart-->
{{ Html::script('admin/js/easypiechart/jquery.easypiechart.js') }}
{{ Html::script('admin/js/easypiechart/easypiechart-init.js') }}

<!--Sparkline Chart-->
{{ Html::script('admin/js/sparkline/jquery.sparkline.js') }}
{{ Html::script('admin/js/sparkline/sparkline-init.js') }}

<!--icheck -->
{{ Html::script('admin/js/iCheck/jquery.icheck.js') }}
{{ Html::script('admin/js/icheck-init.js') }}

<!-- jQuery Flot Chart-->
{{-- {{ Html::script('admin/js/flot-chart/jquery.flot.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.tooltip.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.resize.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.pie.resize.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.selection.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.stack.js') }}
{{ Html::script('admin/js/flot-chart/jquery.flot.time.js') }}
{{ Html::script('admin/js/main-chart.js') }} --}}
{{ Html::script('admin/js/gritter/js/jquery.gritter.js') }}
{{ Html::script('admin/js/toster/toster.min.js') }}
@yield('js')
@include('admin.layouts.alert')
<!--common scripts for all pages-->
{{ Html::script('admin/js/scripts.js') }}
{{ Html::script('js/timeout.js') }}

<script type="text/javascript">

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    var root = '{{url("/")}}';

    $(document).ready(function(){

    $('#success').delay(3000).fadeOut('slow');
    $('#danger').delay(3000).fadeOut('slow');
    $('#warning').delay(3000).fadeOut('slow');

    var logout_url = "<?=URL::route('admin.logout')?>";
    var redirUrl = "<?=URL::route('admin.locked')?>";

    $.sessionTimeout({
        logoutUrl       : logout_url,
        redirUrl        : redirUrl,
        warnAfter       : 18885000,
        redirAfter      : 7000,
        keepAlive       : false,
        countdownMessage: 'Otherwise You will be redirected to login page in {timer} seconds.',
        ignoreUserActivity: false
    });
    });
    $.ajaxSetup({
    statusCode: {
        401: function() {
            swal({
               title: "Session Timeout",
               type:"error",
               timer: 500000,
               showConfirmButton: false
            });
        }
    }
    });
</script>