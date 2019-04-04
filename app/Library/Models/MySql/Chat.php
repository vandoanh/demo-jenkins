<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use BasicBehavior;
    use Singleton;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message',
        'ip_address',
        'user_agent',
        'cookie_val',
    ];

    public function user()
    {
        return $this->belongsTo('App\Library\Models\MySql\User', 'user_id', 'id');
    }

    public function getListMessage($params)
    {
        return $this->orderBy('created_at', config('constants.sort.asc'))
            ->when(!empty($params['date_from']), function ($query) use ($params) {
                return $query->where('created_at', '>=', $params['date_from']);
            })
            ->when(!empty($params['date_to']), function ($query) use ($params) {
                return $query->where('created_at', '<=', $params['date_to']);
            })
            ->get();
    }

    public function createMessage($params)
    {
        return $this->create($params);
    }
}
