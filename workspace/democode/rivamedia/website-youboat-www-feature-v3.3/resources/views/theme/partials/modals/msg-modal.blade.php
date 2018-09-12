@if (!empty($message_modal))
<?php
$msgModalId = 'msgModal';
//$msgModalId .= !empty($message_type) && $message_type != 'success' ? ucwords($message_type) : '';
if(!empty($message_type) && preg_match('/error/', $message_type)) {
    $msgModalId .= ucwords($message_type);
} elseif(!empty($message_type) && !preg_match('/error/', $message_type)) {
    $msgModalId .= ('ajax' == $message_type) ? 'Ajax' : '';
    $msgModalId .= !empty($form_referrer) ? '_' . $form_referrer : '';
}
?>
<div class="modal fade {!! !empty($message_type) ? $message_type : '' !!}" data-referrer="{{ !empty($form_referrer) ? $form_referrer : 'message' }}" {{ !empty($modal_backdrop) ? 'data-backdrop=' . $modal_backdrop : '' }} id="{!! $msgModalId !!}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (!empty($title_modal))
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="title">{!! $title_modal !!}</h4>
            </div>
            @endif
            <div class="modal-body">
                @if (empty($title_modal))
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                @endif
                <div class="clearfix alert {!! !empty($message_type) ? 'alert-' . $message_type : 'alert-info' !!}">
                    {!! $message_modal !!}
                </div>
                {!! !empty($message_action) ? $message_action : '' !!}
            </div>
        </div>
    </div>
</div>
@endif
