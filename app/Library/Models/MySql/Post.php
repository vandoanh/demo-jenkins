<?php

namespace App\Library\Models\MySql;

use App\Library\Models\Category;
use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\PreCache;
use App\Library\Models\Traits\Singleton;
use App\Library\Models\Traits\TrackingActiveLog;
use Carbon\Carbon;
use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use BasicBehavior;
    use PreCache;
    use Singleton;
    use SoftDeletes;
    use TrackingActiveLog;
    use ElasticquentTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'thumbnail_url',
        'description',
        'content',
        'score',
        'priority',
        'tags',
        'status',
        'total_view',
        'show_comment',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'category_id',
        'category_liston',
        'source_name',
        'source_link',
        'is_crawler',
        'user_id',
        'published_at',
    ];

    /**
     * The elasticsearch settings.
     *
     * @var array
     */
    protected $indexSettings = [
        'analysis' => [
            'char_filter' => [
                'replace' => [
                    'type' => 'mapping',
                    'mappings' => [
                        '&=> and ',
                    ],
                ],
            ],
            'filter' => [
                'word_delimiter' => [
                    'type' => 'word_delimiter',
                    'split_on_numerics' => false,
                    'split_on_case_change' => true,
                    'generate_word_parts' => true,
                    'generate_number_parts' => true,
                    'catenate_all' => true,
                    'preserve_original' => true,
                    'catenate_numbers' => true,
                ],
            ],
            'analyzer' => [
                'default' => [
                    'type' => 'custom',
                    'char_filter' => [
                        'html_strip',
                        'replace',
                    ],
                    'tokenizer' => 'whitespace',
                    'filter' => [
                        'lowercase',
                        'word_delimiter',
                    ],
                ],
            ],
        ],
    ];

    protected $mappingProperties = array(
        'title' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'code' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'thumbnail_url' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'description' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'content' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'score' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'tags' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'status' => array(
            'type' => 'integer',
        ),
        'show_comment' => array(
            'type' => 'integer',
        ),
        'seo_title' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'seo_keywords' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'seo_description' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'category_id' => array(
            'type' => 'integer',
        ),
        'category_liston' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'source_name' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'source_link' => array(
            'type' => 'text',
            'analyzer' => 'standard',
        ),
        'user_id' => array(
            'type' => 'integer',
        ),
        'published_at' => array(
            'type' => 'date',
            "format" => "strict_date_optional_time||epoch_millis||yyyy-MM-dd HH:mm:ss",
        ),
        'created_at' => array(
            'type' => 'date',
            "format" => "strict_date_optional_time||epoch_millis||yyyy-MM-dd HH:mm:ss",
        ),
        'updated_at' => array(
            'type' => 'date',
            "format" => "strict_date_optional_time||epoch_millis||yyyy-MM-dd HH:mm:ss",
        ),
        'deleted_at' => array(
            'type' => 'date',
            "format" => "strict_date_optional_time||epoch_millis||yyyy-MM-dd HH:mm:ss",
        ),
    );

    /**
     * Set the tags.
     *
     * @param  array $value
     * @return void
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = !empty($value) ? implode(',', $value) : '';
    }

    /**
     * Get the tags.
     *
     * @param  string $value
     * @return array
     */
    public function getTagsAttribute($value)
    {
        return !empty($value) ? explode(',', $value) : [];
    }

    /**
     * Set the category_liston.
     *
     * @param  array $value
     * @return void
     */
    public function setCategoryListonAttribute($value)
    {
        $this->attributes['category_liston'] = !empty($value) ? implode(',', $value) : '';
    }

    /**
     * Get the category_liston.
     *
     * @param  string $value
     * @return array
     */
    public function getCategoryListonAttribute($value)
    {
        return !empty($value) ? explode(',', $value) : [];
    }

    /**
     * A post can have many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Library\Models\MySql\Comment');
    }

    public function category()
    {
        return $this->belongsTo('App\Library\Models\MySql\Category', 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Library\Models\MySql\User', 'user_id', 'id');
    }

    /**
     * Code for BE
     */
    public function createPost($params)
    {
        return $this->create($params);
    }

    public function createPostJob($attributes, $params)
    {
        return $this->updateOrCreate($attributes, $params);
    }

    public function updatePost($params, $id)
    {
        return $this->find($id)->update($params);
    }

    public function updatePostView($id)
    {
        return $this->find($id)->increment('total_view', 1);
    }

    public function deletePost($id)
    {
        return $this->find($id)->delete();
    }

    public function changeStatus($id)
    {
        $query = $this->find($id);

        return $query->update(['status' => $query->status == config('constants.status.active') ? config('constants.status.inactive') : config('constants.status.active')]);
    }

    public function getDetailPostBE($id)
    {
        $data = $this->findByAttributes([
            'id' => $id,
            'with' => ['category', 'user', 'comments'],
        ]);

        return $data;
    }

    public function getListPostBE($params)
    {
        $query = $this->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->with('category')
            ->with('comments')
            ->with('user')
            ->when(!empty($params['category_id']), function ($query) use ($params) {
                $arrCategoryId = Category::instance()->getFullChildId($params['category_id']);
                $arrCategoryId[] = $params['category_id'];

                return $query->whereIn('category_id', $arrCategoryId);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('status', '=', $params['status']);
            })
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                return $query->where('user_id', '=', $params['user_id']);
            })
            ->when(!empty($params['date_from']), function ($query) use ($params) {
                return $query->where('published_at', '>=', $params['date_from']);
            })
            ->when(!empty($params['date_to']), function ($query) use ($params) {
                return $query->where('published_at', '<=', $params['date_to']);
            })
            ->when(!empty($params['title']), function ($query) use ($params) {
                return $query->where('title', 'like', '%' . $params['title'] . '%');
            });

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    /**
     * Code for FE
     */
    public function getDetailPost($id)
    {
        $query = $this->where('id', '=', $id)
            ->where('status', '=', config('constants.status.active'))
            ->with([
                'user' => function ($query) {
                    $query->where('status', '=', config('constants.user.status.active'));
                },
                'category' => function ($query) {
                    $query->where('status', '=', config('constants.status.active'));
                },
            ]);

        return $query->first();
    }

    public function getListPostByCateSetOn($cate_id, $limit)
    {
        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('category_id', '=', $cate_id)
            ->where('status', '=', config('constants.status.active'))
            ->limit($limit);

        return $query->get();
    }

    public function getListPostByCateListOn($cate_id, $limit)
    {
        $arrCategoryId = Category::instance()->getFullChildId($cate_id);
        $arrCategoryId[] = $cate_id;

        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->whereIn('category_id', $arrCategoryId)
            ->where('status', '=', config('constants.status.active'))
            ->limit($limit);

        return $query->get();
    }

    public function getBuildTop($limit)
    {
        $query = $this->select('id', 'category_id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'))
            ->limit($limit);

        return $query->get();
    }

    public function getAllPost($limit)
    {
        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'))
            ->limit($limit);

        return $query->get();
    }

    public function getListPostByUser($user_id, $limit, $status = null)
    {
        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('user_id', '=', $user_id)
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', '=', $status);
            })
            ->limit($limit);

        return $query->get();
    }

    public function getListPostByTag($tag_title, $limit)
    {
        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'))
            ->whereRaw('FIND_IN_SET(?, tags)', [$tag_title])
            ->limit($limit);

        return $query->get();
    }

    public function getListTopView($limit)
    {
        $date_from = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $date_to = Carbon::now()->format('Y-m-d H:i:s');

        $query = $this->select('id')
            ->orderBy('total_view', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'))
            ->where('is_crawler', '=', config('constants.post.crawler.no'))
            ->where('published_at', '>=', $date_from)
            ->where('published_at', '<=', $date_to)
            ->limit($limit);

        return $query->get();
    }

    public function searchPosts($title)
    {
        $query = $this->select('id')
            ->orderBy('score', config('constants.sort.desc'))
            ->orderBy('id', config('constants.sort.desc'))
            ->where('status', '=', config('constants.status.active'))
            ->where(function ($query) use ($title) {
                $query->where('title', 'like', '%' .$title . '%')
                    ->orWhere('description', 'like', '%' . $title . '%')
                    ->orWhere('content', 'like', '%' . $title . '%');
            });

        return $query->get();
    }

    public function elasticSearchPosts($params = [])
    {
        $offset = ($params['page'] - 1) * $params['item'];

        $query = array("multi_match" => array("query" => $params['title'], "fields" => ["title", "content", "description"]));

        return $this->searchByQuery($query, null, null, $params['item'], $offset, null);
    }

    public function countPost($category_id)
    {
        $data = $this->select(DB::raw('count(*) as quantity'))
            ->where('status', '=', config('constants.status.active'))
            ->where('category_id', '=', $category_id)
            ->first();

        return $data->quantity;
    }
}
