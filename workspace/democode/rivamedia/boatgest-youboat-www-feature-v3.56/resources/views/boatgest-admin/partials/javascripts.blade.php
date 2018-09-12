<?php
    $currentControllerAction = str_replace(array(config('quickadmin.homeRoute') . '.', '.'), array('', '_'), $currentRoute);
?>
@if (App::isLocal())
<script src="{{ asset('assets/vendor/jquery/jquery-1.12.1.min.js') }}"></script>
@if(isset($currentAction) && 'index' == $currentAction)
{{--<script src="{{ asset('assets/vendor/datatables/1.10.11/js/jquery.dataTables.min.js') }}"></script>--}}
<script src="{{ asset('assets/vendor/datatables/1.10.12/datatables.min.js') }}"></script>
@endif
{{--<script src="{{ asset('assets/vendor/jquery-ui/1.11.4/jquery-ui.min.js') }}"></script>--}}
<script src="{{ asset('assets/vendor/ckeditor/4.6.2/standard/ckeditor.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/3.3.6/js/bootstrap.min.js') }}"></script>

{{--<script src="{{ asset('assets/vendor/jquery.timepicker/1.8.9/jquery.timepicker.min.js') }}"></script>--}}
{{--<script src="{{ asset('assets/vendor/jquery-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js') }}"></script>--}}

<script src="{{ asset('assets/vendor/moment/2.11.2/min/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/4.0.2/js/select2.min.js') }}"></script>
@else
<script src="//code.jquery.com/jquery-1.12.1.min.js"></script>
@if(isset($currentAction) && 'index' == $currentAction)
{{--<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>--}}
<script type="text/javascript" src="//cdn.datatables.net/v/bs/jqc-1.12.3/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.3/r-2.1.0/rr-1.1.2/se-1.2.0/datatables.min.js"></script>
{{--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>--}}
@endif
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.9/jquery.timepicker.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js"></script>--}}

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
@endif

@if(isset($currentControllerAction) && ('adscaracts_edit' == $currentControllerAction || 'adscaracts_create' == $currentControllerAction))
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="{{ asset('assets/vendor/jquery-ui/1.12.1/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-fileupload/js/vendor/jquery.ui.widget.min.js') }}"></script>

<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/vendor/load-image.all.min.js') }}"></script>

<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/vendor/canvas-to-blob.min.js') }}"></script>

<!-- blueimp Gallery script -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/vendor/jquery.blueimp-gallery.min.js') }}"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.iframe-transport.min.js') }}"></script>
<!-- The basic File Upload plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload.min.js') }}"></script>
<!-- The File Upload processing plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-process.min.js') }}"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-image.min.js') }}"></script>
<!-- The File Upload audio preview plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-audio.min.js') }}"></script>
<!-- The File Upload video preview plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-video.min.js') }}"></script>
<!-- The File Upload validation plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-validate.min.js') }}"></script>

<!-- The File Upload user interface plugin -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/jquery.fileupload-ui.min.js') }}"></script>

<!-- The main application script -->
<script src="{{ asset('assets/vendor/jquery-fileupload/js/main.js') }}"></script>

<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="{{ asset('assets/vendor/jquery-fileupload/js/cors/jquery.xdr-transport.min.js') }}"></script>
<![endif]-->
@endif

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

<script src="{{ asset('assets/vendor/boatgest-admin/js/main.js') }}"></script>
<script src="{{ asset('assets/vendor/youboat/js/youboat.js') }}"></script>




