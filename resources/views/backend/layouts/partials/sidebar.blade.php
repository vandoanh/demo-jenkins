<div class="sidebar-logo">
    <a href="{!! route('backend.dashboard') !!}" class="logo"></a>
</div>
<ul class="sidebar-menu">
    <li class="menu-item{!! 'backend_index_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.dashboard') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-home"></i>
            </span>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>
    <li class="menu-item{!! 'backend_category_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.category.index') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="menu-title">Manage Category</span>
        </a>
    </li>
    <li class="menu-item{!! 'backend_post_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.post.index') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-blog"></i>
            </span>
            <span class="menu-title">Manage Post</span>
        </a>
    </li>
    <li class="menu-item{!! 'backend_comment_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.comment.index') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-comments"></i>
            </span>
            <span class="menu-title">Manage Comment</span>
        </a>
    </li>
    <li class="menu-item{!! 'backend_user_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.user.index') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-users"></i>
            </span>
            <span class="menu-title">Manage User</span>
        </a>
    </li>
    <li class="menu-item{!! 'backend_notice_index' == $menu_code ? ' active' : '' !!}">
        <a href="{!! route('backend.notice.index') !!}" class="menu-link">
            <span class="menu-icon">
                <i class="fas fa-exclamation"></i>
            </span>
            <span class="menu-title">Manage Notice</span>
        </a>
    </li>
</ul>
<div class="content-mask"></div>
