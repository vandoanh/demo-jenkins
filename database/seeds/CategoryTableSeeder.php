<?php

use Illuminate\Database\Seeder;
use App\Library\Models\MySql\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $modelCategory = Category::instance();
        $modelCategory->truncate();

        $cateInfo = $modelCategory->create([
            'title' => 'Tin tức',
            'code' => 'tin-tuc',
            'display_order' => 1,
            'parent_id' => 0,
            'show_fe' => config('constants.post.fe.show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Tin tức tiếng Việt',
            'code' => 'tin-tuc-tieng-viet',
            'display_order' => 1,
            'parent_id' => $cateInfo->id,
            'show_fe' => config('constants.post.fe.not_show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Tin tức tiếng Anh',
            'code' => 'tin-tuc-tieng-anh',
            'display_order' => 2,
            'parent_id' => $cateInfo->id,
            'show_fe' => config('constants.post.fe.not_show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Tin tức tiếng Nhật',
            'code' => 'tin-tuc-tieng-nhat',
            'display_order' => 3,
            'parent_id' => $cateInfo->id,
            'show_fe' => config('constants.post.fe.not_show'),
            'status' => config('constants.status.active'),
        ]);

        $cateInfo = $modelCategory->create([
            'title' => 'Lập trình',
            'code' => 'lap-trinh',
            'display_order' => 2,
            'parent_id' => 0,
            'show_fe' => config('constants.post.fe.show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Lập trình Ứng dụng',
            'code' => 'lap-trinh-ung-dung',
            'display_order' => 1,
            'parent_id' => $cateInfo->id,
            'show_fe' => config('constants.post.fe.show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Lập trình Web',
            'code' => 'lap-trinh-web',
            'display_order' => 2,
            'parent_id' => $cateInfo->id,
            'show_fe' => config('constants.post.fe.show'),
            'status' => config('constants.status.active'),
        ]);

        $modelCategory->create([
            'title' => 'Tools & Tips',
            'code' => 'tools-tip',
            'display_order' => 3,
            'parent_id' => 0,
            'show_fe' => config('constants.post.fe.show'),
            'status' => config('constants.status.active'),
        ]);
    }
}
