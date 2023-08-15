<!doctype html>
<html lang="en">

<head>

    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>{{ $title }} - Ayamku.</title>

    <!--favicon-->
    <link rel="icon" href="{{ asset('') }}ayamku.png" type="image/png" />

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
    {{-- sweet alert --}}
    <link rel="stylesheet" href="{{ asset('') }}assets/css/sweetalert2.min.css">

    @stack('style')
</head>

<body>
    @if (session()->has('success'))
        <div class="success-info" data-msg="{{ session('success') }}"></div>
    @endif
    @if (session()->has('error'))
        <div class="error-info" data-msg="{{ session('error') }}"></div>
    @endif

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
    <script src="{{ asset('') }}assets/sweetalert/sweetalert2.all.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            var success = $('.success-info').data('msg');
            var error = $('.error-info').data('msg');
            var errorPost = $('.error-post').data('msg');

            if (error) {
                if (errorPost) {
                    title = errorPost
                } else {
                    title = error
                }
                Toast.fire({
                    icon: 'error',

                    title: title
                })
            }
            if (success) {
                Toast.fire({
                    icon: 'success',
                    title: success
                })
            }
            if (errorPost) {
                $('#addModal').modal("show")
                console.log('error post');
            }
        })
    </script>

</body>

</html>
