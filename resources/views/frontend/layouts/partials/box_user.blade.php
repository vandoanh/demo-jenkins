<div class="sidebar-box">
    <div class="bio text-center">
        <img src="{!! image_url($userInfo->avatar, '90x90') !!}" alt="Image Placeholder" class="img-fluid">
        <div class="bio-body">
            <h2>{{ $userInfo->fullname }}</h2>
            <div class="text-left"><strong>Email:</strong> {{ $userInfo->email }}</div>
            <div class="text-left"><strong>Birthday:</strong> {{ format_date($userInfo->birthday, 'd/m/Y') }}</div>
            <div class="text-left"><strong>Gender:</strong> {{ $userInfo->gender == \config('constants.user.gender.male') ? 'Male' : 'Female' }}</div>
        </div>
    </div>
</div>
