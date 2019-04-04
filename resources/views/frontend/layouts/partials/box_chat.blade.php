<div class="chat-popup" id="frmChat">
    <div class="chat-head">
        <button type="button" class="btn_close"><i class="ic-sprite ic-sprite--close"></i></button>
        <div class="box-chat">
            <div class="float-left">
                <div class="img-avatar pr-1">
                    <figure class="avatar-shape">
                        @if (auth()->check())
                            <img src="{!! image_url(auth()->user()->avatar, '40x40') !!}" alt="{{ auth()->user()->fullname }}" />
                        @else
                            <img src="{!! url_static('images/avatar.jpg') !!}" alt="Incognito" />
                        @endif
                    </figure>
                </div>
                <div class="title">{{ auth()->check() ? auth()->user()->fullname : 'User ' . $user_id }}</div>
            </div>
        </div>
        <div class="bot-border"></div>
    </div>
    <div id="messagePanel" class="box-content-chat">
        @if ($listMessage->isNotEmpty())
            @foreach ($listMessage as $message)
                <div class="box-chat" id="message-{!! $message->id !!}">
                    <div class="float-left">
                        <div class="img-avatar pr-1">
                            <figure class="avatar-shape">
                                @if ($message->user)
                                    <img src="{!! image_url($message->user->avatar, '40x40') !!}" alt="{{ $message->user->fullname }}" />
                                @else
                                    <img src="{!! url_static('images/avatar.jpg') !!}" alt="{{ $message->user }}" />
                                @endif
                            </figure>
                        </div>
                        <div class="content-chat">
                            <p class="box-grey">{!! $message->message !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="box-chat no-message">
                <div class="float-left">
                    <div class="content-chat">There is no message yet.</div>
                </div>
            </div>
        @endif
    </div>
    <div class="footer-chat">
        <div class="bot-border mb-2"></div>
        <form id="frmMessage" action="{!! route('chat.send') !!}" method="post" class="form-container" data-sending="0">
            {{ csrf_field() }}
            <!--<div class="float-right mr-2">
                <a href="#"><i class="far fa-smile grey mr-2"></i></a>
                <a href="#"><i class="fas fa-paperclip grey"></i></a>
            </div>-->
            <textarea placeholder="Type message..." name="message"></textarea>
        </form>
    </div>
</div>
