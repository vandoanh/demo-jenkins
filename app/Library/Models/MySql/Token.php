<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\BasicBehavior;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Library\Models\Traits\TrackingActiveLog;

class Token extends Model
{
    use BasicBehavior;
    use Singleton;
    use TrackingActiveLog;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tokens';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'key',
        'user_id',
        'expired_at',
    ];

    public function insertTokenKey($params)
    {
        return $this->create($params);
    }

    public function getTokenKey($params)
    {
        return $this->findByAttributes([
            'type' => $params['type'],
            'key' => $params['key'],
            'expired_at' => ['>=', Carbon::now()]
        ]);
    }

    public function deleteTokenKey($params)
    {
        return $this->deleteByAttributes([
            'type' => $params['type'],
            'user_id' => $params['user_id'],
        ]);
    }
}
