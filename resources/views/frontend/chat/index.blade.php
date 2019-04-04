@extends('frontend.layouts.layout')

@section('content')
<article class="container c-mt-3">
    <h2 class="mb-4">Chat</h2>
    <div class="row">
        <div class="col-12">
            <div class="card mb10 bg-light">
                <div id="messagePanel" class="card-body message-panel box-content-chat">
                    @if ($listMessage->isNotEmpty())
                        @foreach ($listMessage as $message)
                            <div class="box-chat" id="message-{!! $message->id !!}">
                                <div class="float-left">
                                    <div class="img-avatar pr-1">
                                        <figure class="avatar-shape">
                                            @if ($message->user)
                                                <img src="{!! image_url($message->user->avatar, '40x40') !!}" alt="{{ $message->user->fullname }}" />
                                            @else
                                                <img src="{!! url_static('images/avatar.jpg') !!}"alt="{{ $message->user }}" />
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
                                <div class="content-chat">
                                    <p class="box-grey">There is no message yet.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <form id="frmMessage" action="{!! route('chat.send') !!}" method="post" data-sending="0">
                {{ csrf_field() }}
                <textarea class="form-control" placeholder="Type message..." name="message"></textarea>
            </form>
        </div>
    </div>
</article>
@stop
