<!-- Header Right V3 -->
<div class="header-right">
    @include('theme.partials.header.user-login-panel', ['login_version'=>'v3'])
    <form class="search-form-minimal hidden-xs hidden-sm">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" size="40" placeholder="Enter model, make, zipcode etc. to search">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">{!! ucfirst(trans('navigation.auth.register')) !!}!</button>
            </span>
        </div>
    </form>
</div>
<!-- End Header Right V3 -->
