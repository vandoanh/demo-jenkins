<header class="container">
    <div class="row mt-4">
        <div class="col-sm-2 show_pc">
            <a href="#"><i class="fa fa-facebook mr-3 mt-4 font-24"></i></a>
            <a href="#"><i class="fa fa-instagram mt-4 font-24"></i></a>
        </div>
        <div class="col-sm-8 c-center">
            <img class="img-fluid" src="images/logo-blog.png" alt="logo EAS HCM blog">
        </div>
        <div class="col-sm-2 c-right show_pc">
            <img class="img-fluid" src="images/icon_chat.png" alt="chat">
        </div>
        <div class="col-sm-4 c-center show_sp">
            <a href="#"><i class="fa fa-facebook mr-3 font-24"></i></a>
            <a href="#"><i class="fa fa-instagram mr-3 font-24"></i></a>
            <img src="images/icon_chat.png" alt="chat">
        </div>
    </div>
</header>
<header role="banner">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-9 user-bar">
                    @guest
                        <a href="{!! route('auth.login') !!}" title="Login"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="{!! route('auth.register') !!}" title="Register"><i class="fas fa-registered"></i> Register</a>
                    @else
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{!! image_url(auth()->user()->avatar, '30x30') !!}" alt="User avatar" class="rounded-circle" />
                            <span>{{ auth()->user()->fullname }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <li>
                                <a href="{!! route('user.profile') !!}"title="View profile"><i class="fas fa-user"></i><span>Profile</span></a>
                            </li>
                            <li>
                                <a href="{!! route('user.post') !!}"title="View all posts"><i class="fas fa-blog"></i><span>Posts</span></a>
                            </li>
                            <li class="divider" role="separator"></li>
                            <li>
                                <a href="{!! route('auth.logout') !!}" title="Log out"><i class="fas fa-power-off"></i><span>Logout</span></a>
                            </li>
                        </ul>
                    @endguest
                </div>
                <div class="col-3 search-top">
                    <form action="{!! route('post.search') !!}" method="get" class="search-top-form">
                        <span class="icon fa fa-search"></span>
                        <input type="text" name="key" placeholder="Type keyword to search...">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container logo-wrap">
        <div class="row pt-5">
            <div class="col-12 text-center">
                <a class="absolute-toggle d-block d-md-none" data-toggle="collapse" href="#navbarMenu" role="button" aria-expanded="false" aria-controls="navbarMenu"><span class="burger-lines"></span></a>
                <h1 class="site-logo"><a href="{!! route('dashboard') !!}">{{ strtoupper(config('app.name')) }}</a></h1>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-md  navbar-light bg-light">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{!! route('dashboard') !!}">Home</a>
                    </li>
                    @foreach ($listCategoryParent as $category)
                        <li class="nav-item{!! $category->childs->count() > 0 ? ' dropdown' : '' !!}">
                            <a class="nav-link" href="{!! route('post.category', [$category->code]) !!}">{{ $category->title }}</a>
                            @if ($category->childs->count() > 0)
                                <div class="dropdown-menu" aria-labelledby="dropdown04">
                                    @foreach ($category->childs as $child)
                                        <a class="dropdown-item" href="{!! route('post.category', [$child->code]) !!}">{{ $child->title }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>
