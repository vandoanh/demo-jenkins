<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\TrackingActiveLog;

class Tag extends Model
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
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
    ];

    /**
     * Function create tag
     * @param array param
     */
    public function createTag($params)
    {
        return $this->updateOrCreate([
            'title' => $params['title']
        ], $params);
    }

    /**
     * Function get tag info by id
     * @param int $id
     */
    public function getDetailTag($id)
    {
        $query = $this->where('status', '=', config('constants.status.active'));

        if (is_numeric($id)) {
            $query->where('id', '=', $id);
        } else {
            $query->where('title', '=', $id);
        }

        return $query->first();
    }

    public function getDetailTagByCodes($tag)
    {
        $query = $this->where('title', '=', $tag)
            ->where('status', '=', config('constants.status.active'));

        return $query->first();
    }

    public function getListTag()
    {
        $query = $this->select('id')
            ->where('status', '=', config('constants.status.active'));

        return $query->get();
    }
}
