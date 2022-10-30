<!doctype html>
<html lang="en">

<head>

    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>{{ $title }} - Tamakoci</title>

    <!--favicon-->
    <link rel="icon" href="{{ asset('') }}logo.png" type="image/png" />

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/bootstrap.min.css">

    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/all.css">

    <!--====== nice select css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/nice-select.css">

    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/slick.css">

    <!--====== Swiper css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/swiper.min.css">

    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/default.css">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ asset('') }}/land/css/style.css">

    @stack('style')
</head>

<body>

    @yield('content')
    <!--====== scroll_up PART START ======-->

    <a id="scroll_up"><i class="far fa-angle-up"></i></a>

    <!--====== scroll_up PART ENDS ======-->






    <!--====== jquery js ======-->
    <script src="{{ asset('') }}/land/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="{{ asset('') }}/land/js/vendor/jquery-1.12.4.min.js"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{ asset('') }}/land/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}/land/js/popper.min.js"></script>

    <!--====== Slick js ======-->
    <script src="{{ asset('') }}/land/js/slick.min.js"></script>

    <!--====== Swiper js ======-->
    <script src="{{ asset('') }}/land/js/swiper.min.js"></script>

    <!--====== nice select js ======-->
    <script src="{{ asset('') }}/land/js/jquery.nice-select.min.js"></script>

    <!--====== circle progress js ======-->
    <script src="{{ asset('') }}/land/js/circle-progress.min.js"></script>

    <!--====== Images Loaded js ======-->
    <script src="{{ asset('') }}/land/js/jquery.syotimer.min.js"></script>

    <!--====== Main js ======-->
    <script src="{{ asset('') }}/land/js/main.js"></script>

</body>

</html>
