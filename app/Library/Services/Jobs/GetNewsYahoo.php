<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use App\Library\Models\Category;
use App\Library\Models\Post;
use App\Library\Services\CommonService;
use App\Library\Services\Crawler\CrawlerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GetNewsYahoo extends Job implements ShouldQueue
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
        DB::beginTransaction();

        try {
            $crawlerService = new CrawlerService();
            $data = $crawlerService->getData($this->params['link'], 'get', [], 'html');

            if (empty($data)) {
                return;
            }

            $contents = $this->getContent($data['crawler']);

            $modelPost = Post::instance();
            $category = Category::instance()->getDetailCategoryByCode('tin-tuc-tieng-anh');

            if ($contents) {
                $data['title'] = clean($this->params['title'], 'notags');
                $data['code'] = str_slug($data['title']);
                $data['description'] = clean($this->params['description'], 'notags');
                $data['content'] = $contents['content'];
                $data['thumbnail_url'] = CommonService::saveImageFromUrl($this->params['image'], config('constants.image.default.folder'), true);
                $data['status'] = config('constants.status.active');
                $data['category_id'] = $category->id;
                $data['category_liston'] = [$category->parent_id, $category->id];
                $data['source_name'] = 'Yahoo Finance';
                $data['source_link'] = $this->params['link'];

                $modelPost->createPostJob([
                    'code' => $data['code'],
                ], $data);
            }

            DB::commit();

            //Send Log OK
            print "Add post successfully!\n";
        } catch (\Exception $ex) {
            DB::rollBack();

            dump($ex);
            //Send Log Error
            print "Add post failed!\n";
        }
    }

    private function getContent($crawler)
    {
        $results = [];

        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div.canvas-yahoovideo')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > figure.canvas-image')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div.read-more')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div > figure')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div > ul.canvas-list')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div > p > strong')->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $crawler->filter('article[itemprop="articleBody"] > div.canvas-body > div > p')->last()->each(function ($nodes) {
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $results['content'] = $crawler->filter('article[itemprop="articleBody"] > div.canvas-body')->html();

        return $results;
    }
}
