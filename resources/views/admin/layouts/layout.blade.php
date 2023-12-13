<html lang="en">
@include('admin.layouts.head')
<body class="sticky-header">

<section>
    <!-- left side start-->
    @include('admin.layouts.sidebar')
    <!-- left side end-->

    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
       	@include('admin.layouts.header')
        <!-- header section end-->

        <!--body wrapper start-->
        <div class="wrapper">
            @yield('content')
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <footer style="position: fixed;">
            {{ date('Y') }} &copy; WveLabs
        </footer>
        <!--footer section end-->
    </div>
    <!-- main content end-->
</section>
@include('admin.layouts.footer')
</body>
</html>
