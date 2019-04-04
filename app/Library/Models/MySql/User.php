<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\TrackingActiveLog;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use BasicBehavior;
    use PreCache;
    use Singleton;
    use SoftDeletes;
    use TrackingActiveLog;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'fullname',
        'description',
        'avatar',
        'gender',
        'birthday',
        'receive_notification',
        'timezone',
        'user_type',
        'status',
        'remember_token',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Set the avatar.
     *
     * @param  array $value
     * @return void
     */
    public function setAvatarAttribute($value)
    {
        $this->attributes['avatar'] = !empty($value) ? $value : config('constants.image.avatar.name');
    }

    /**
     * Get the avatar.
     *
     * @param  string $value
     * @return array
     */
    public function getAvatarAttribute($value)
    {
        return !empty($value) ? $value : config('constants.image.avatar.name');
    }

    public function getTimeZone()
    {
        return $this->timezone;
    }

    /**
     * A user can have many posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Library\Models\MySql\Post');
    }

    /**
     * A user can have many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Library\Models\MySql\Comment');
    }

    public function createUser($params)
    {
        return $this->create($params);
    }

    public function updateUser($user_id, $params)
    {
        return $this->find($user_id)->update($params);
    }

    /**
     * Function get list user
     * @param string title
     */
    public function getListUserBE($params)
    {
        $query = $this->orderBy('id', config('constants.sort.asc'));

        if (!empty($params['fullname'])) {
            $query->where('fullname', 'LIKE', '%' . $params['fullname'] . '%');
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function getDetailUser($user_id)
    {
        return $this->find($user_id);
    }

    public function getDetailUserByEmail($email)
    {
        return $this->findByAttributes([
            'email' => $email,
        ]);
    }
}
