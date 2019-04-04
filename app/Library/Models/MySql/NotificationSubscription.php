<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\TrackingActiveLog;
use Illuminate\Database\Eloquent\Model;

class NotificationSubscription extends Model
{
    use BasicBehavior;
    use Singleton;
    use TrackingActiveLog;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Library\Models\MySql\User', 'user_id', 'id');
    }

    public function insertSubscription($params)
    {
        return $this->updateOrCreate([
            'endpoint' => $params['endpoint'],
        ], [
            'endpoint' => $params['endpoint'],
            'public_key' => $params['public_key'],
            'auth_token' => $params['auth_token'],
            'content_encoding' => $params['content_encoding'],
            'user_id' => $params['user_id'],
        ]);
    }

    public function deleteByEndpoint($endpoint)
    {
        return $this->deleteByAttributes([
            'endpoint' => $endpoint,
        ]);
    }

    public function getSubscriptions($user_id = null)
    {
        $query = $this->orderBy('user_id', config('constants.sort.asc'))
            ->when(!empty($user_id), function ($query) use ($user_id) {
                $query->where('user_id', '=', $user_id);
            })
            ->with('user');

        return $query->get();
    }
}
