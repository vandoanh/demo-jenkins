<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\BasicBehavior;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use BasicBehavior;
    use Singleton;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_request',
        'method',
        'data_request',
        'data_response',
        'ip_address',
        'user_agent',
    ];

    public function createLog($params)
    {
        return $this->create($params);
    }
}
