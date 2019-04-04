<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page">
            <a href="{!! route('backend.dashboard') !!}"><i class="fas fa-home"></i> Dashboard</a>
        </li>
        @if ($controller !== 'index')
            <li class="breadcrumb-item active">{{ ucfirst($controller) }}</li>
        @endif
    </ol>
</nav>
