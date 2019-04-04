<?php

use Illuminate\Database\Seeder;
use App\Library\Models\MySql\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!User::instance()->getDetailUserByEmail('superadmin@blog.eashcm.dev')) {
            User::instance()->create([
                'email' => 'superadmin@blog.eashcm.dev',
                'fullname' => 'Super Admin',
                'description' => 'Super Administrator.',
                'avatar' => config('constants.image.avatar.name'),
                'gender' => config('constants.user.gender.male'),
                'receive_notification' => 0,
                'user_type' => config('constants.user.type.admin'),
                'status' => config('constants.status.active'),
                'password' => bcrypt(env('SUPERADMIN_PASSWORD', 'Aa123456@')),
            ]);
        }
    }
}
