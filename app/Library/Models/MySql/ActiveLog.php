<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\BasicBehavior;
use Illuminate\Database\Eloquent\Model;

class ActiveLog extends Model
{
    use BasicBehavior;
    use Singleton;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'active_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module',
        'type',
        'content',
        'ip_address',
        'user_agent',
        'cookie_val',
        'user_id',
    ];

    /**
     * Set the log content.
     *
     * @param  array $value
     * @return void
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = !empty($value) ? json_encode($value) : json_encode([]);
    }

    /**
     * Get the log content.
     *
     * @param  string $value
     * @return array
     */
    public function getContentAttribute($value)
    {
        return !empty($value) ? json_decode($value, true) : [];
    }

    public function createLog($params)
    {
        return $this->create($params);
    }
}
