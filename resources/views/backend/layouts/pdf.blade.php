<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    {{ style('bower_components/bootstrap/dist/css/bootstrap.css') }}
    <style>

        table {
            display: table !important;
            width: 100%;
        }

        thead {
            display: table-header-group !important;
            page-break-after: avoid !important;
        }

        tfoot {
            display: table-footer-group !important;
            page-break-after: avoid !important;
        }

        tbody {
            display: table-row-group !important;
            page-break-after: auto !important;
        }

        tr, img {
            display: table-row !important;
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }

        td, th {
            display: table-cell !important;
            padding: 20px !important;
        }

        .table thead:before {
            display: block;
            width: 1px;
            height: 2px;
            content: '';
            border: none;
            background-color: white;
        }

        .table {
            position: relative;
        }

        .table tr {
            page-break-inside: avoid !important;
        }

    </style>

</head>

<body style="background-color: white">

<div class="app-body" id="app">
    <main class="main">
        @yield('content')
    </main><!--main-->
</div><!--app-body-->

</body>
</html>

