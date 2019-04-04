<div class="box-chat" id="message-{!! $messageInfo['id'] !!}">
    <div class="float-left">
        <div class="img-avatar pr-1">
            <figure class="avatar-shape">
                @if ($messageInfo['user'])
                    <img src="{!! image_url($messageInfo['user']->avatar, '40x40') !!}" alt="{{ $messageInfo['user']->fullname }}" />
                @else
                    <img src="{!! url_static('images/avatar.jpg') !!}" alt="{{ $messageInfo['user_id'] }}" />
                @endif
            </figure>
        </div>
        <div class="content-chat">
            <p class="box-grey">{!! $messageInfo['message'] !!}</p>
        </div>
    </div>
</div>
