<?php
if ( ! function_exists('renderDataAttributes')) {
    function renderDataAttributes($attributes)
    {
        $mapped = [ ];
        foreach ($attributes as $key => $value) {
            $mapped[] = 'data-' . $key . '="' . $value . '"';
        };

        return implode(' ', $mapped);
    }
}
?>
@if(!empty($options))
    <script type="text/javascript">
        var RecaptchaOptions = <?=json_encode($options) ?>;
    </script>
@endif
<script>
    if ('function' !== typeof recaptchaCallback) {
        function recaptchaCallback(response) {
            if (response.length > 0) {
                var $This = $('#g-recaptcha-response');
                $This.parents('.form-group')
                    .removeClass('has-error')
                    .find('.help-block').remove();
                //var $Form = $('form[role="form"]');
                var $Form = $This.parents('form');
                if ($Form.attr('id') != 'form_bod') {
                    if ('function' === typeof checkInputsFilled) {
                        checkInputsFilled($Form);
                    }
                }
            }
        }
    }
</script>
<script src="//www.google.com/recaptcha/api.js?render=onload{{ (isset($lang) ? '&hl='.$lang : '') }}" async defer></script>
<div class="g-recaptcha" data-sitekey="{{ $public_key }}" <?=renderDataAttributes($dataParams)?>></div>
<noscript>
    <div class="noscript_g-recaptcha">
        <div class="g-recaptcha_content">
            <div class="iframe_g-recaptcha_p">
                <iframe src="https://www.google.com/recaptcha/api/fallback?k={{ $public_key }}" id="iframe_g-recaptchaframeborder"></iframe>
            </div>
            <div class="g-recaptcha-response-container">
                <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response"></textarea>
            </div>
        </div>
    </div>
</noscript>
