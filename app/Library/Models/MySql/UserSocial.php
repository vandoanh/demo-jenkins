<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\BasicBehavior;
use Illuminate\Database\Eloquent\Model;
use App\Library\Models\Traits\TrackingActiveLog;

class UserSocial extends Model
{
    use BasicBehavior;
    use Singleton;
    use TrackingActiveLog;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'social_id',
        'provider',
    ];

    public function createUser($params)
    {
        return $this->updateOrCreate($params, $params);
    }
}
