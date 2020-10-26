<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" integrity="sha512-ryjtnwPDaox3otqxhS/cCqCQCE7/mfHbfbfu+87WdRnn5bHxtTqti5q+TWnNUI3MHwABP98M01mT+7Ocqwk55g==" crossorigin="anonymous" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="@hopeseekr" />
    <meta name="twitter:title" content="Have I Voted.org – Let's get 100% voter turnout!" />
    <meta name="twitter:description" content="Quickly find out if your vote has been properly counted. Find out if your family and friends have already voted." />
    <meta name="twitter:image" content="https://www.haveivoted.org/images/haveivoted.jpg" />
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
<script async src="{{ asset('js/all.js') }}"></script>
{{--<script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>--}}
{{--<link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">--}}
<script async src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" integrity="sha512-Yblu1vNh895IOJ7j81oA+g0K/Rjv09fbssEw/I7EhszcLxJRp59fe4SUBsBP/6sdJHEGSZAglqyhO1TeYHhyKw==" crossorigin="anonymous"></script>
<!-- HaveIVoted.org -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BMVJ30H88L"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BMVJ30H88L');
</script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
