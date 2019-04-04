<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\ChangePasswordRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Http\Requests\Backend\CreateUserRequest;
use App\Library\Models\User;
use App\Library\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BackendController
{
    public function index(Request $request)
    {
        $fullname = $request->fullname ?? '';
        $item = check_paging($request->item ?? '');
        $page = $request->page ?? 1;

        //search data
        $params = [
            'fullname' => $fullname,
            'item' => $item,
            'page' => $page,
        ];
        $arrListUser = User::instance()->getListUserBE($params);

        if ($arrListUser->total() > 0) {
            $maxPage = ceil($arrListUser->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.user.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListUser->onEachSide(config('site.general.pagination.each_side'))->appends($params)->links();

        return view('backend.user.index')->with([
            'params' => $params,
            'users' => $arrListUser,
            'pagination' => $pagination,
        ]);
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(CreateUserRequest $request)
    {
        DB::beginTransaction();

        $request_user = $request->except('_token');

        try {
            // create user
            User::instance()->createUser([
                'email' => $request_user['email'],
                'password' => bcrypt(config('site.general.default_password')),
                'fullname' => $request_user['fullname'],
                'gender' => $request_user['gender'],
                'birthday' => $request->birthday ? Carbon::createFromFormat('d/m/Y', $request_user['birthday']) : null,
                'user_type' => $request_user['user_type'],
                'avatar' => config('constants.image.avatar.name'),
                'status' => config('constants.status.active'),
            ]);

            DB::commit();

            return redirect(route('backend.user.index'))->withInput(['message' => ['User added!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('backend.user.index'))->withInput(['error' => ['User failed!']]);
        }
    }

    public function edit($id)
    {
        //redirect when current user
        if ($id == auth()->user()->getAuthIdentifier()) {
            return redirect(route('backend.user.profile'));
        }

        $userInfo = User::instance()->getDetailUserBE($id);
        if (!$userInfo) {
            abort(404);
        }

        return view('backend.user.edit')->with([
            'userInfo' => $userInfo,
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        //redirect when current user
        if ($id == auth()->user()->getAuthIdentifier()) {
            return redirect(route('backend.user.profile'));
        }

        $userInfo = User::instance()->getDetailUserBE($id);
        if (!$userInfo) {
            abort(404);
        }

        DB::beginTransaction();

        $request_user = $request->except('_token');

        try {
            //update user
            User::instance()->updateUser($id, [
                'fullname' => $request_user['fullname'],
                'gender' => $request_user['gender'],
                'birthday' => $request_user['birthday'] ? Carbon::createFromFormat('d/m/Y', $request_user['birthday']) : null,
                'user_type' => $request_user['user_type'],
                'status' => $request_user['status'],
            ]);

            DB::commit();

            return redirect(route('backend.user.index'))->withInput(['message' => ['User updated!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('backend.user.index'))->withInput(['error' => ['User update failed!']]);
        }
    }

    public function editProfile()
    {
        $userInfo = auth()->user();

        return view('backend.user.edit-profile')->with([
            'userInfo' => $userInfo,
        ]);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        DB::beginTransaction();

        $request_user = $request->except('_token');

        try {
            $image = $request->avatar ?? null;

            if ($image) {
                $image = CommonService::saveImageFromTmp($image, config('constants.image.avatar.folder'));
            } else {
                $image = config('constants.image.default.file');
            }

            //update user
            User::instance()->updateUser(auth()->user()->getAuthIdentifier(), [
                'fullname' => $request_user['fullname'],
                'gender' => $request_user['gender'],
                'avatar' => $image,
                'birthday' => $request_user['birthday'] ? Carbon::createFromFormat('d/m/Y', $request_user['birthday']) : null,
            ]);

            DB::commit();

            return redirect(route('backend.user.profile'))->withInput(['message' => ['User profile updated!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('backend.user.profile'))->withInput(['error' => ['User profile update failed!']]);
        }
    }

    public function changePassword()
    {
        return view('backend.user.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        DB::beginTransaction();

        $request_user = $request->except('_token');

        try {
            //update user
            User::instance()->updateUser(auth()->user()->getAuthIdentifier(), [
                'password' => bcrypt($request_user['new_password']),
            ]);

            DB::commit();

            return redirect(route('backend.user.profile'))->withInput(['message' => ['User changed password!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('backend.user.profile'))->withInput(['error' => ['User changed password failed!']]);
        }
    }

    public function detail($id)
    {
        $userInfo = User::instance()->getDetailUserBE($id);

        return view('backend.user.detail')->with([
            'userInfo' => $userInfo,
        ]);
    }

    public function profile()
    {
        $userInfo = auth()->user();

        return view('backend.user.profile')->with([
            'userInfo' => $userInfo,
        ]);
    }
}
