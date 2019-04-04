<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use App\Library\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrecachePost extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Post::instance()->getBuildTop(config('constants.post.limit.build_top'), true);
        Post::instance()->getAllPost([
            'item' => 0,
            'page' => 1,
        ], true);
        Post::instance()->getListTopView(config('constants.post.limit.top_view'), true);

        $postInfo = Post::instance()->getDetailPost($this->params['id'], true);

        if ($postInfo) {
            foreach ($postInfo->category_liston as $category_id) {
                Post::instance()->getListPostByCateSetOn([
                    'category_id' => $category_id,
                    'item' => 0,
                    'page' => 1,
                ], true);

                Post::instance()->getListPostByCateListOn([
                    'category_id' => $category_id,
                    'item' => 0,
                    'page' => 1,
                ], true);
            }

            foreach ($postInfo->tags as $tag) {
                Post::instance()->getListPostByTag([
                    'tag_title' => $tag,
                    'item' => 0,
                    'page' => 1,
                ], true);
            }

            Post::instance()->getListPostByUser([
                'user_id' => $postInfo->user_id,
                'item' => 0,
                'page' => 1,
            ], true);

            Post::instance()->getListPostByUser([
                'user_id' => $postInfo->user_id,
                'status' => config('constants.status.active'),
                'item' => 0,
                'page' => 1,
            ], true);

            Post::instance()->getListPostByUser([
                'user_id' => $postInfo->user_id,
                'status' => config('constants.status.inactive'),
                'item' => 0,
                'page' => 1,
            ], true);

            if (env('USED_ELASTICSEARCH_FLAG', false) == true) {
                $postInfo->addToIndex();
            }

            Post::instance()->countPost($postInfo->category_liston, true);

            dispatch(new PrecacheImage([
                'images' => $postInfo->thumbnail_url,
            ]))->onQueue('image');
        }

        if ($this->params['type'] != config('constants.precache.type.create')) {
            $arrCategoryListOn = explode(',', $this->params['data']['category_liston']);
            $arrTag = explode(',', $this->params['data']['tags']);

            foreach ($arrCategoryListOn as $category_id) {
                Post::instance()->getListPostByCateSetOn([
                    'category_id' => $category_id,
                    'item' => 0,
                    'page' => 1,
                ], true);

                Post::instance()->getListPostByCateListOn([
                    'category_id' => $category_id,
                    'item' => 0,
                    'page' => 1,
                ], true);
            }

            foreach ($arrTag as $tag) {
                Post::instance()->getListPostByTag([
                    'tag_title' => $tag,
                    'item' => 0,
                    'page' => 1,
                ], true);
            }

            Post::instance()->getListPostByUser([
                'user_id' => $this->params['data']['user_id'],
                'item' => 0,
                'page' => 1,
            ], true);

            Post::instance()->getListPostByUser([
                'user_id' => $this->params['data']['user_id'],
                'status' => config('constants.status.active'),
                'item' => 0,
                'page' => 1,
            ], true);

            Post::instance()->getListPostByUser([
                'user_id' => $this->params['data']['user_id'],
                'status' => config('constants.status.inactive'),
                'item' => 0,
                'page' => 1,
            ], true);

            Post::instance()->countPost($arrCategoryListOn, true);
        }
    }
}
