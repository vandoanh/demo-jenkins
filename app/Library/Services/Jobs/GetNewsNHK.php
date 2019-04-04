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

class GetNewsNHK extends Job implements ShouldQueue
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

            $content = $this->getContent($crawData['crawler']);

            $modelPost = Post::instance();

            $category = Category::instance()->getDetailCategoryByCode('tin-tuc-tieng-nhat');

            if ($content) {
                $data['title'] = clean($this->params['title'], 'notags');
                $data['description'] = clean($content['description'], 'notags');
                $data['content'] = $content['content'];
                $data['thumbnail_url'] = CommonService::saveImageFromUrl($this->params['image'], config('constants.image.default.folder'), true);
                $data['code'] = $this->params['id_post'];
                $data['status'] = config('constants.status.active');
                $data['category_id'] = $category->id;
                $data['category_liston'] = [$category->parent_id, $category->id];
                $data['source_name'] = 'NHK';
                $data['source_link'] = $this->params['link'];

                $modelPost->createPostJob([
                    'code' => $this->params['id_post']
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
        $data = [];

        $data['description'] = $crawler->filterXpath("//meta[@name='description']")->attr('content');

        $content = $crawler->filter('div.article-main__body')->html();

        $data['content'] = str_replace('href="javascript:void(0)"', "", $content);

        return $data;
    }
}
