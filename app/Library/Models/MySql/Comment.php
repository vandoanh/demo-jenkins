<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\TrackingActiveLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
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
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'parent_id',
        'user_id',
        'post_id',
        'total_like',
        'total_dislike',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\Library\Models\MySql\User', 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\Library\Models\MySql\Post', 'post_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Library\Models\MySql\Comment', 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany('App\Library\Models\MySql\Comment', 'parent_id');
    }

    /**
     * Code for BE
     */
    public function createComment($params)
    {
        return $this->create($params);
    }

    public function updateComment($id, $params)
    {
        return $this->find($id)->update($params);
    }

    public function updateCommentLike($id)
    {
        return $this->find($id)->increment('total_like', 1);
    }

    public function deleteComment($id)
    {
        return $this->find($id)->delete();
    }

    public function changeStatus($id)
    {
        $query = $this->find($id);

        return $query->update(['status' => $query->status === config('constants.status.active') ? config('constants.status.inactive') : config('constants.status.active')]);
    }

    public function getDetailCommentBE($id)
    {
        return $this->findByAttributes([
            'id' => $id,
            'with' => ['post', 'user', 'parent', 'childs'],
        ]);
    }

    public function getListCommentBE($params)
    {
        $query = $this->orderBy('created_at', config('constants.sort.desc'))
            ->with('post')
            ->with('user')
            ->with('parent')
            ->with('childs')
            ->when(!empty($params['content']), function ($query) use ($params) {
                return $query->where('content', 'like', '%' . $params['content'] . '%');
            });

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    /**
     * Code for FE
     */
    public function getDetailComment($id)
    {
        $query = $this->where('id', '=', $id)
            ->where('status', '=', config('constants.status.active'))
            ->with([
                'user' => function ($query) {
                    $query->where('status', '=', config('constants.user.status.active'));
                },
                'childs' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
            ]);

        return $query->first();
    }

    public function getListCommentByPost($post_id, $limit)
    {
        $query = $this->select('id')
            ->orderBy('parent_id', config('constants.sort.asc'))
            ->orderBy('created_at', config('constants.sort.desc'))
            ->where('parent_id', '=', 0)
            ->where('post_id', '=', $post_id)
            ->where('status', '=', config('constants.status.active'))
            ->limit($limit);

        return $query->get();
    }

    public function countComment($post_id)
    {
        $data = $this->select(DB::raw('count(*) as quantity'))
            ->where('status', '=', config('constants.status.active'))
            ->where('post_id', '=', $post_id)
            ->first();

        return $data->quantity;
    }
}
