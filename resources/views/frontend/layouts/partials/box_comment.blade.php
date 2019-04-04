<div class="comments mt-5">
    <h2 class="heading-secondary mb-4">Ý kiến bạn đọc ({{ $total }})</h2>
    @if ($listComments->isNotEmpty())
        <div class="comments__list">
            @foreach ($listComments as $comment)
                <div class="comments__item" id="comment_{{ $comment->id }}">
                    <div class="comments__avatar">
                        <figure class="comments__avatar__shape">
                            <img src="{{ image_url($comment->user->avatar, '40x40') }}" alt="Image placeholder">
                        </figure>
                    </div>
                    <div class="comments__detail">
                        <p class="comments__user">{{ $comment->user->fullname }}</p>
                        <p class="comments__content">{{ $comment->content }}</p>
                        <div class="comments__interaction">
                            <ul class="comments__actions">
                                <li class="comments__like" data-id="{{ $comment->id }}">
                                    <i class="ic-sprite ic-sprite--heart ic-sprite--heart--empty mr-1"></i>
                                    <span class="comments__count">{{ $comment->total_like }}</span>
                                </li>
                                @auth
                                    <li class="comments__reply"><a href="#" class="reply rounded" data-reply="{{ $comment->id }}" data-parentid="{{ $comment->parent_id }}">Trả lời</a></li>
                                @endauth
                            </ul>
                            <span class="comments__time">{{ format_date_chat($comment->created_at) }}</span>
                        </div>
                    </div>
                    @if ($comment->childs->count() > 0)
                        <ul class="comments__child">
                            @foreach ($comment->childs as $child)
                                <li>
                                    <div class="comments__item" id="comment_{{ $child->id }}">
                                        <div class="comments__avatar">
                                            <figure class="comments__avatar__shape">
                                                <img src="{{ image_url($child->user->avatar, '40x40') }}" alt="Image placeholder">
                                            </figure>
                                        </div>
                                        <div class="comments__detail">
                                            <p class="comments__user">{{ $child->user->fullname }}</p>
                                            <p class="comments__content">{{ $child->content }}</p>
                                            <div class="comments__interaction">
                                                <ul class="comments__actions">
                                                    <li class="comments__like" data-id="{{ $child->id }}">
                                                        <i class="ic-sprite ic-sprite--heart ic-sprite--heart--empty mr-1"></i>
                                                        <span class="comments__count">{{ $child->total_like }}</span>
                                                    </li>
                                                    @auth
                                                        <li class="comments__reply"><a href="#" class="reply rounded" data-reply="{{ $child->parent_id }}" data-parentid="{{ $child->parent_id }}">Trả lời</a></li>
                                                    @endauth
                                                </ul>
                                                <span class="comments__time">{{ format_date_chat($child->created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
            <div class="mt-5">{{ $pagination }}</div>
        </div>
    @endif
</div>
@auth
    <div class="comments__editor text-right mt-5">
        <form id="comment_form" action="#">
            <textarea name="content" cols="30" rows="5" placeholder="Ý kiến của bạn"></textarea>
            <button type="submit" class="btn btn--red mt-3">Gửi bình luận</button>
        </form>
    </div>
    <div class="comments__editor text-right mt-2" id="comment_reply_wrapper" style="display: none;">
        <form id="comment_reply_form" action="#">
            <textarea name="content" cols="30" rows="5" placeholder="Ý kiến của bạn"></textarea>
            <button type="submit" class="btn btn--red mt-3">Gửi bình luận</button>
        </form>
    </div>
@endauth
