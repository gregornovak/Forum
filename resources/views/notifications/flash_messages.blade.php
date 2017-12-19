@if(session('error'))
    <div class="flash-container flash-error-messages">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12 flash-parent">
            <p class="error-text">{{ session('error') }}</p>
            <span class="glyphicon glyphicon-remove error-text-close flash-close"></span>
        </div>
    </div>
@endif
@if(session('success'))
   <div class="flash-container flash-success-messages">
        <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12 flash-parent">
            <p class="success-text">{{ session('success') }}</p>
            <span class="glyphicon glyphicon-remove success-text-close flash-close"></span>
        </div>
    </div>
@endif