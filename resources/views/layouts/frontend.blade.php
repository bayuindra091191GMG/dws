<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <!-- Document Meta
        ============================================= -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--IE Compatibility Meta-->
    <meta name="author" content="zytheme"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Digital Waste Solution">
    <link href="{{ asset('css/frontend/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .img-banner-responsive{
            height: 150px;
        }
        .img-banner-responsive2{
            height:700px;
        }
        .bold{
            font-weight: bold;
        }
        .subfont{
            font-size: 20px;
        }
        .pt-5-custom{
            margin-top: 3em!important;
        }
        .pt-5{
            margin-top: 5em!important;
        }
        @media (min-width: 768px) {
            .img-banner-responsive{
                height: 565px;
            }
        }
    </style>
    <![endif]-->

    <!-- Document Title
        ============================================= -->
    <title>DWS - Landing Page</title>
</head>
<body>
    {{--@include('partials.frontend._header')--}}

    @yield('content')
    <!-- Footer #1
            ============================================= -->
    @include('partials.frontend._footer')
    <!-- Footer Scripts
    ============================================= -->
    <script src="{{ asset('js/bootstrap.min.css') }}" charset="utf-8"></script>
</body>
</html>