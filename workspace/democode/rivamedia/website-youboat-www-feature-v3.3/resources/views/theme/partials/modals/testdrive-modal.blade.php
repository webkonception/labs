<div class="modal fade" id="testdriveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Book a test drive</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus. Donec facilisis fermentum sem, ac viverra ante luctus vel. Donec vel mauris quam.</p>
                <form>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="Full Name">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input type="text" class="form-control" placeholder="Phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="datepicker" class="form-control" placeholder="Preferred Date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-append bootstrap-timepicker">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                <input type="text" id="timepicker" class="form-control" placeholder="Preferred time">
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary pull-right" value="Schedule Now">
                    <label class="btn-block">Preferred Contact</label>
                    <label class="checkbox-inline"><input type="checkbox"> Email</label>
                    <label class="checkbox-inline"><input type="checkbox"> Phone</label>
                </form>
            </div>
        </div>
    </div>
</div>
@section('javascript')
    <script type="text/javascript">
        if ('function' === typeof $.timepicker) {
            $('#timepicker').timepicker({defaultTime: false});
        }
        if ('function' === typeof $.datepicker) {
            $('#datepicker').datepicker();
        }
    </script>
@endsection
