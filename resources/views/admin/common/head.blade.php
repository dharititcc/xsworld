<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'XS World') }}</title>

<!-- Favicon-->
<link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ioc') }}" />
    <!--  Datatable --->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Core theme CSS (includes Bootstrap)-->
<link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<link href="{{ asset('css/fontello.css') }}" rel="stylesheet" />