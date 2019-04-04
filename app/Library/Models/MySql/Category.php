<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\TrackingActiveLog;

class Category extends Model
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
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'display_order',
        'parent_id',
        'show_fe',
        'status',
    ];

    /**
     * A post can have many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Library\Models\MySql\Post');
    }

    public function user()
    {
        return $this->belongsTo('App\Library\Models\MySql\User', 'user_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Library\Models\MySql\Category', 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany('App\Library\Models\MySql\Category', 'parent_id');
    }

    public function createCategory($params)
    {
        return $this->create($params);
    }

    public function updateCategory($params, $id)
    {
        return $this->find($id)->update($params);
    }

    public function deleteCategory($id)
    {
        return $this->find($id)->delete();
    }

    public function getDetailCategoryBE($id)
    {
        return $this->findByAttributes([
            'id' => $id,
            'with' => ['parent', 'childs', 'user', 'posts']
        ]);
    }

    /**
     * Function get list category
     * @param string title
     */
    public function getListCategoryBE($params)
    {
        $query = $this->orderBy('display_order', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.asc'))
            ->with('parent')
            ->with('childs')
            ->with('user')
            ->with('posts')
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', '=', $params['status']);
            })
            ->when(!empty($params['title']), function ($query) use ($params) {
                return $query->where('title', 'like', '%' . $params['title'] . '%');
            });

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function getListParentBE($parent_id)
    {
        return $this->getByAttributes([
            'parent_id' => $parent_id,
            'with' => ['parent', 'childs', 'user', 'posts']
        ], 'display_order', config('constants.sort.asc'));
    }

    public function getLastOrder($parent_id = 0)
    {
        $result = $this->where('parent_id', '=', $parent_id)->count();

        return $result + 1;
    }

    public function getFullChildId($parent_id = 0)
    {
        $arrData = $this->where('parent_id', '=', $parent_id)
            ->get(['id'])
            ->toArray();

        if (!empty($arrData)) {
            foreach ($arrData as $id) {
                $arrData = array_merge($arrData, $this->getFullChildId($id));
            }
        }

        return $arrData;
    }

    public function getFullParentId($category_id)
    {
        $arrData = [];
        $categoryInfo = $this->getDetailCategoryBE($category_id);

        if ($categoryInfo && $categoryInfo->parent_id != 0) {
            $arrData[] = $categoryInfo->parent_id;
            $arrData = array_merge($arrData, $this->getFullParentId($categoryInfo->parent_id));
        }

        return $arrData;
    }

    /**
     * Function get category info by id
     * @param int $id
     */
    public function getDetailCategory($id)
    {
        $query = $this->where('id', '=', $id)
            ->where('status', '=', config('constants.status.active'))
            ->with([
                'posts' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'parent' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'childs' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'))
                        ->where('show_fe', '=', config('constants.post.fe.show'));
                },
            ]);

        return $query->first();
    }

    public function getDetailCategoryByCode($code)
    {
        $query = $this->where('code', '=', $code)
            ->where('status', '=', config('constants.status.active'))
            ->with([
                'posts' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'parent' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'childs' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'))
                        ->where('show_fe', '=', config('constants.post.fe.show'));
                },
            ]);

        return $query->first();
    }

    /**
     * Function get list parent
     * @param string title
     */
    public function getListParent($parent_id)
    {
        $query = $this->orderBy('display_order', config('constants.sort.asc'))
            ->orderBy('id', config('constants.sort.asc'))
            ->where('parent_id', '=', $parent_id)
            ->where('status', '=', config('constants.status.active'))
            ->with([
                'posts' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'parent' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
                'childs' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'))
                        ->where('show_fe', '=', config('constants.post.fe.show'));
                },
            ]);

        return $query->get();
    }
}
