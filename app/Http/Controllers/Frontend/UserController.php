<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ChangePasswordRequest;
use App\Http\Requests\Frontend\UpdateUserRequest;
use App\Library\Models\Category;
use App\Library\Models\Post;
use App\Library\Models\User;
use App\Library\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Frontend\UpdatePostRequest;
use App\Http\Requests\Frontend\CreatePostRequest;
use App\Library\Models\Tag;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $userInfo = auth()->user();

        return view('frontend.user.profile')->with([
            'userInfo' => $userInfo,
        ]);
    }

    public function listPost(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = 9;

        //search data
        $params = [
            'user_id' => auth()->user()->getAuthIdentifier(),
            'category_id' => $category_id,
            'status' => $status,
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];

        $listPost = Post::instance()->getListPostBE($params);

        if ($listPost->total() > 0) {
            $maxPage = ceil($listPost->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('user.post', ['page' => $maxPage]));
            }
        }
        $pagination = $listPost->withPath(route('user.post'))->links();

        $listCategory = Category::instance()->getListParentBE();

        return view('frontend.user.post.index')->with([
            'params' => $params,
            'listPost' => $listPost,
            'pagination' => $pagination,
            'listCategory' => $listCategory,
        ]);
    }

    public function createPost(Request $request)
    {
        $listCategory = Category::instance()->getListParentBE();

        return view('frontend.user.post.create')->with([
            'listCategory' => $listCategory,
        ]);
    }

    public function storePost(CreatePostRequest $request)
    {
        DB::beginTransaction();

        try {
            //process save image from content in other website
            $content = CommonService::processImageContent($request->content);

            $image = $request->thumbnail_url ?? null;
            if ($image) {
                $image = CommonService::saveImageFromTmp($image, 'image');
            } else {
                $image = config('constants.image.default.file');
            }

            $params = [
                'title' => clean($request->title, 'notags'),
                'code' => $request->code,
                'thumbnail_url' => $image,
                'description' => clean($request->description, 'notags'),
                'content' => $content,
                'priority' => config('constants.post.priority.normal'),
                'tags' => $request->tags,
                'status' => $request->status,
                'show_comment' => config('constants.post.comment.show'),
                'seo_title' => clean($request->seo_title ?? $request->title, 'notags'),
                'seo_keywords' => clean($request->seo_keywords ?? null, 'notags'),
                'seo_description' => clean($request->seo_description ?? $request->description, 'notags'),
                'category_id' => $request->category_id,
                'category_liston' => $request->category_liston,
                'user_id' => auth()->user()->getAuthIdentifier(),
            ];

            //make score if status is published
            if ($params['status'] == config('constants.status.active')) {
                $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $params['score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['priority'] . format_date($params['published_at'], 'His');
            }

            Post::instance()->createPost($params);
            Tag::instance()->createTags($request->tags);

            DB::commit();

            return redirect(route('user.post'))->withInput(['message' => ['Thêm mới thành công!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('user.post.create'))->withInput(['error', ['Lỗi hệ thống!']]);
        }
    }

    public function editPost($id)
    {
        $postInfo = Post::instance()->getDetailPostBE($id);
        $listCategory = Category::instance()->getListParentBE();

        if (!$postInfo || $postInfo->user_id != auth()->user()->getAuthIdentifier()) {
            abort(404);
        }

        return view('frontend.user.post.edit')->with([
            'postInfo' => $postInfo,
            'listCategory' => $listCategory
        ]);
    }

    public function updatePost(UpdatePostRequest $request, $id)
    {
        $postInfo = Post::instance()->getDetailPostBE($id);

        if (!$postInfo || $postInfo->user_id != auth()->user()->getAuthIdentifier()) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            //process save image from content in other website
            $content = CommonService::processImageContent($request->content);

            $image = $request->thumbnail_url ?? null;
            if ($image) {
                $image = CommonService::saveImageFromTmp($image, 'image');

                if (!$image) {
                    $image = $postInfo->thumbnail_url;
                }
            } else {
                $image = $postInfo->thumbnail_url;
            }

            $params = [
                'title' => clean($request->title, 'notags'),
                'code' => $request->code,
                'thumbnail_url' => $image,
                'description' => clean($request->description, 'notags'),
                'content' => $content,
                'priority' => $postInfo->priority,
                'tags' => $request->tags,
                'status' => $request->status,
                'show_comment' => config('constants.post.comment.show'),
                'seo_title' => clean($request->seo_title ?? $request->title, 'notags'),
                'seo_keywords' => clean($request->seo_keywords ?? null, 'notags'),
                'seo_description' => clean($request->seo_description ?? $request->description, 'notags'),
                'category_id' => $request->category_id,
                'category_liston' => $request->category_liston,
            ];

            //make score if status is published
            if ($postInfo->status != config('constants.status.active')) {
                if ($params['status'] == config('constants.status.active')) {
                    $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $params['score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['priority'] . format_date($params['published_at'], 'His');
                }
            } else {
                $params['score'] = format_date($postInfo->published_at, 'Ymd') . '0' . $params['priority'] . format_date($postInfo->published_at, 'His');
            }

            Post::instance()->updatePost($params, $id);
            Tag::instance()->createTags($request->tags);

            DB::commit();

            return redirect(route('user.post'))->withInput(['message' => ['Cập nhật thành công!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('user.post.edit', [$id]))->withInput(['error' => ['Lỗi hệ thống!']]);
        }
    }

    public function editProfile()
    {
        $userInfo = auth()->user();

        return view('frontend.user.edit-profile')->with([
            'userInfo' => $userInfo,
        ]);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user_id = auth()->user()->getAuthIdentifier();
        $request_user = $request->except('_token');

        DB::beginTransaction();

        try {
            $image = $request->avatar ?? null;

            if ($image) {
                $image = CommonService::saveImageFromTmp($image, config('constants.image.avatar.folder'));
            } else {
                $image = config('constants.image.default.file');
            }

            //update user
            $userInfo = User::instance()->updateUser($user_id, [
                'fullname' => $request_user['fullname'],
                'gender' => $request_user['gender'],
                'avatar' => $image,
                'birthday' => $request_user['birthday'] ? Carbon::createFromFormat('d/m/Y', $request_user['birthday']) : null,
            ]);

            DB::commit();

            return redirect(route('user.profile'))->withInput(['message' => ['Profile updated!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('user.profile'))->withInput(['error' => ['Profile update failed!']]);
        }
    }

    public function changePassword()
    {
        return view('frontend.user.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $request_user = $request->except('_token');
        $user_id = auth()->user()->getAuthIdentifier();

        DB::beginTransaction();

        try {
            //update user
            User::instance()->updateUser($user_id, [
                'password' => bcrypt($request_user['new_password']),
            ]);

            DB::commit();

            return redirect(route('user.profile'))->withInput(['message' => ['Changed password!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('user.profile'))->withInput(['error' => ['Changed password failed!']]);
        }
    }
}
