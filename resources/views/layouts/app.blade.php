<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" integrity="sha512-ryjtnwPDaox3otqxhS/cCqCQCE7/mfHbfbfu+87WdRnn5bHxtTqti5q+TWnNUI3MHwABP98M01mT+7Ocqwk55g==" crossorigin="anonymous" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="s003">
    <div class="site-banner">
        <h1>Have I Voted.org</h1>
        <h3>Quickly find out if your vote has been properly recorded in your state's voter rolls.</h3>
    </div>
    <div class="main">
        @yield('content')
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/all.js') }}"></script>
{{--<script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>--}}
{{--<link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">--}}
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" integrity="sha512-Yblu1vNh895IOJ7j81oA+g0K/Rjv09fbssEw/I7EhszcLxJRp59fe4SUBsBP/6sdJHEGSZAglqyhO1TeYHhyKw==" crossorigin="anonymous"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
