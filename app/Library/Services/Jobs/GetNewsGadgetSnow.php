<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Services\Crawler\CrawlerService;
use App\Library\Models\Post;
use App\Library\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Library\Services\CommonService;

class GetNewsGadgetSnow extends Job implements ShouldQueue
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
            $crawData = $crawlerService->getData($this->params['link'], 'get', [], 'html');

            if (empty($crawData)) {
                return;
            }

            $contents = $this->getContent($crawData['crawler']);

            $modelPost = Post::instance();
            $category = Category::instance()->getDetailCategoryByCode('tin-tuc-tieng-anh');

            if ($contents) {
                $data['title'] = clean($this->params['title'], 'notags');
                $data['description'] = clean($this->params['description'], 'notags');
                $data['content'] = $contents['content'];
                $data['thumbnail_url'] = CommonService::saveImageFromUrl($contents['image'], config('constants.image.default.folder'), true);
                $data['code'] = str_slug($data['title']);
                $data['status'] = config('constants.status.active');
                $data['category_id'] = $category->id;
                $data['category_liston'] = [$category->parent_id, $category->id];
                $data['source_name'] = 'GadgetSnow';
                $data['source_link'] = $this->params['link'];

                $modelPost->createPostJob([
                    'code' => $data['code']
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

        if (strpos($this->params['link'], 'slideshows') !== false) {
            $crawler->filter('div.slides > div.slidebox > span.desc_more')->each(function ($crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $crawler->filter('div.slides > div.slidebox > div.related_topics')->each(function ($crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $crawler->filter('div.slides > div.slidebox > div.imagebox > img')->each(function ($crawler) {
                if (!empty($crawler->attr('data-src'))) {
                    $crawler->getNode(0)->setAttribute('src', $crawler->attr('data-src'));
                }
            });

            $results['content'] = $crawler->filter('.listviewcontainer > div.slides')->html();
            $results['image'] = $crawler->filter('div.slides > div.slidebox > div.imagebox > img')->attr('src');
        } else {
            $crawler->filter('.article_content > arttextxml > div.section1 > .Normal > div[class*=brdiv]')->each(function ($crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $crawler->filter('.article_content > arttextxml > div.section1 > .Normal iframe')->each(function ($crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $results['content'] = $crawler->filter('.article_content > arttextxml > div.section1')->html();
            $results['image'] = $crawler->filter('.highlight > div.highlight_img > img')->attr('src');
        }

        return $results;
    }
}
