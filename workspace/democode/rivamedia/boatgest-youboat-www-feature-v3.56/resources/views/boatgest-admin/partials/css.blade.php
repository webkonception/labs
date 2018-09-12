@if (App::isLocal())
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/font-awesome/4.6.3/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/3.3.6/css/bootstrap.min.css') }}">
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/1.10.11/css/jquery.dataTables.min.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/1.10.12/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/4.0.2/css/select2.min.css') }}">
@else
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jqc-1.12.3/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.3/r-2.1.0/rr-1.1.2/se-1.2.0/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/styles.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor') }}/boatgest-admin/css/components.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor') }}/boatgest-admin/css/boatgest-admin-layout.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor') }}/boatgest-admin/css/boatgest-admin-theme-default.css">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/material-switch/material-switch.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/inbox/inbox.css') }}">

@if(isset($currentControllerAction) && ('adscaracts_edit' == $currentControllerAction || 'adscaracts_create' == $currentControllerAction))
    <!-- blueimp Gallery styles -->
{{--<link rel="stylesheet" type="text/css" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">--}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/blueimp-gallery.min.css') }}">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-ui.min.css') }}">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-noscript.min.css') }}"></noscript>
<noscript><link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/jquery-fileupload/css/jquery.fileupload-ui-noscript.min.css') }}"></noscript>
@endif

@if ($isAdmin)
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/circular-navigation/circular-navigation-bo.css') }}">
@endif