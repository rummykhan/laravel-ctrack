<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RummyKhan | Mongomies</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.1/css/bulma.min.css"/>
    <style type="text/css">
        .content {
            margin-top: 40px;
        }

        .container {
            margin-top: 10px;
        }
    </style>
</head>

<body class="container">

@include('mongomies::includes.nav-bar')

<div class="content">
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

@yield('scripts')

</body>
</html>
