@extends('master.landing.index')
@push('style')
    <style>
        .gradient {
            color: blueviolet;
        }

        .text-style {
            font-size: 16px;
            font-weight: 500;
            color: #ada2c3;
        }


        .text2 {
            font-size: 100px;
            font-weight: 800;
            color: #ada2c3;
            font-style: italic;
            font-family: Arial, Helvetica, sans-serif;
        }


        @media (min-width: 481px) and (max-width: 767px) {

            .text2 {
                font-size: 50px;
                font-weight: 600;
                color: #ada2c3;
                font-style: italic;
                font-family: Arial, Helvetica, sans-serif;
            }

        }

        @media (min-width: 320px) and (max-width: 480px) {

            .text2 {
                font-size: 50px;
                font-weight: 600;
                color: #ada2c3;
                font-style: italic;
                font-family: Arial, Helvetica, sans-serif;
            }

        }

        .text3 {
            font-size: 16px;
            color: #ada2c3;
            font-family: Arial, Helvetica, sans-serif;
        }

        .text-Logo {
            font-size: 25px;
            font-weight: 500;
            color: #ada2c3;
            font-style: italic;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endpush
@section('content')
    @include('landing.navbar')

    <!--====== BANNER PART START ======-->
    @include('landing.banner')
    <!--====== BANNER PART ENDS ======-->


    <!--====== DIAMON PART START ======-->

    @include('landing.diamon')

    <!--====== DIAMON PART ENDS ======-->

    <!--====== BENEFITS PART START ======-->

    @include('landing.banefit')

    <!--====== BENEFITS PART ENDS ======-->



    <!--====== DOCUMENTS PART ENDS ======-->

    <!--====== FUTURE ROADMAP PART START ======-->

    {{-- @include('landing.roadmap') --}}
    <!--====== FUTURE ROADMAP PART END ======-->

    <!--====== TEAM PART START ======-->

    @include('landing.ternak')

    <!--====== TEAM PART ENDS ======-->

    <!--====== FAQ PART START ======-->
    {{-- @include('landing.faq') --}}
    <!--====== FAQ PART ENDS ======-->

    <!--====== BISNIS PART START ======-->

    @include('landing.bisnis')

    <!--====== BISNIS PART ENDS ======-->

    <!--====== BRAND PART START ======-->
    {{-- @include('landing.brand') --}}
    <!--====== BRAND PART ENDS ======-->

    <!--====== CONTACT PART START ======-->

    {{-- @include('landing.contact') --}}

    <!--====== CONTACT PART ENDS ======-->
    @include('landing.footer')
@endsection
