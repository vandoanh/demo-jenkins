<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\TrackingActiveLog;
use Carbon\Carbon;

class Notice extends Model
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
    protected $table = 'notices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'content_chatwork',
        'push_notification',
        'push_chatwork',
        'status',
        'published_at',
    ];

    /**
     * Code for BE
     */
    public function createNotice($params)
    {
        return $this->create($params);
    }

    public function createNoticeJob($attributes, $params)
    {
        return $this->updateOrCreate($attributes, $params);
    }

    public function updateNotice($params, $id)
    {
        return $this->find($id)->update($params);
    }

    public function deleteNotice($id)
    {
        return $this->find($id)->delete();
    }

    public function changeStatus($id)
    {
        $query = $this->find($id);

        return $query->update(['status' => $query->status == config('constants.status.active') ? config('constants.status.inactive') : config('constants.status.active')]);
    }

    public function getDetailNoticeBE($id)
    {
        $data = $this->findByAttributes([
            'id' => $id
        ]);

        return $data;
    }

    public function getListNoticeBE($params)
    {
        $query = $this->orderBy('id', config('constants.sort.desc'))
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', '=', $params['status']);
            })
            ->when(!empty($params['date_from']), function ($query) use ($params) {
                return $query->where('published_at', '>=', $params['date_from']);
            })
            ->when(!empty($params['date_to']), function ($query) use ($params) {
                return $query->where('published_at', '<=', $params['date_to']);
            });

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    /**
    * Code for FE
    */
    public function getDetailNotice($id)
    {
        $query = $this->where('id', '=', $id)
            ->where('status', '=', config('constants.status.active'))
            ->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'));

        return $query->first();
    }

    public function getListNotice()
    {
        $query = $this->select('id', 'published_at')
            ->orderBy('published_at', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'));

        return $query->get();
    }
}
