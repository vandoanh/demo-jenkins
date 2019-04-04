<button type="button" class="sidebar-toggle">
    <span class="toggle-icon"></span>
</button>
<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="{{ auth()->user()->fullname }}">
                <img src="{{ image_url(auth()->user()->avatar, '40x40') }}" class="rounded-circle" />
                <span>{{ auth()->user()->fullname }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="{!! route('backend.user.profile',[auth()->user()->id]) !!}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <!--<li class="divider" role="separator"></li>//-->
                <li>
                    <a href="{!! route('backend.auth.logout') !!}">
                        <i class="fas fa-power-off"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
