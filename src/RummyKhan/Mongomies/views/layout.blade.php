<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="token" content="{{ csrf_token() }}">

    <title>RummyKhan | Mongomies</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.1/css/bulma.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.css" />
    <style type="text/css">
        .margin-top-40 {
            margin-top: 40px;
        }

        .margin-top-10 {
            margin-top: 10px;
        }

        #primary-stats, #foreign-stats {
            height: 500px;
            overflow-y:scroll;
        }
    </style>
</head>

<body class="container margin-top-10">

@include('mongomies::includes.nav-bar')

<div class="content margin-top-40">
    <div class="columns">
        <div class="column is-2">
            @yield('sidebar')
        </div>
        <div class="column is-10">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.min.js"></script>

@yield('scripts')

</body>
</html>
