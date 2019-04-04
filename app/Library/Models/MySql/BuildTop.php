<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\TrackingActiveLog;

class BuildTop extends Model
{
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
    protected $table = 'buildtops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'category_id',
        'display_order',
    ];
}
