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

class GetNewsCafebiz extends Job implements ShouldQueue
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
            $getData = $crawlerService->getData($this->params['link'], 'get', [], 'html');

            if (empty($getData)) {
                return;
            }

            $crawler = $getData['crawler'];

            if ($crawler->filter('.detail-content')->count()) {
                preg_match('/src\s*=\s*"(.+?)"/', $this->params['description'], $matches);
                if (!empty($matches)) {
                    $thumbnail = preg_replace('/zoom\/110_69\//', '', $matches[1]);
                    $thumbnail = CommonService::saveImageFromUrl($thumbnail, config('constants.image.default.folder'), true);
                } else {
                    $thumbnail = config('constants.image.default.file');
                }

                $dataCategory = Category::instance()->getDetailCategoryByCode('tin-tuc-tieng-viet');

                $content = $this->getContent($crawler);

                $data['title'] = clean($this->params['title'], 'notags');
                $data['description'] = clean($this->params['description'], 'notags');
                $data['content'] = $content;
                $data['thumbnail_url'] = $thumbnail;
                $data['code'] = str_slug($data['title']);
                $data['status'] = config('constants.status.active');
                $data['category_id'] = $dataCategory->id;
                $data['category_liston'] = [$dataCategory->parent_id, $dataCategory->id];
                $data['source_name'] = 'Cafebiz';
                $data['source_link'] = $this->params['link'];

                Post::instance()->createPostJob([
                    'code' => $data['code']
                ], $data);

                DB::commit();

                //Send Log OK
                print "Add post successfully!\n";
            } else {
                DB::rollBack();

                print "Add post failed!\n";
            }
        } catch (\Exception $ex) {
            DB::rollBack();

            dump($ex);
            //Send Log Error
            print "Add post failed!\n";
        }
    }

    private function getContent($crawler)
    {
        $crawler->filter('.detail-content div.VCSortableInPreviewMode')->each(function ($crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        return $crawler->filter('.detail-content')->html();
    }
}
