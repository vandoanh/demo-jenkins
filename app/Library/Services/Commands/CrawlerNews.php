<?php
namespace App\Library\Services\Commands;

use App\Library\Services\Crawler\CrawlerService;
use App\Library\Services\Jobs\GetNewsCafebiz;
use App\Library\Services\Jobs\GetNewsGadgetSnow;
use App\Library\Services\Jobs\GetNewsNHK;
use App\Library\Services\Jobs\GetNewsYahoo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrawlerNews extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get news from sites.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crawlerService = new CrawlerService();

        $arrLink = [
            'cafebiz' => ['xml', 'http://cafebiz.vn/cong-nghe.rss'],
            'gadgetsnow' => ['html', 'https://www.gadgetsnow.com/tech-news'],
            'nhk' => ['json', 'https://www3.nhk.or.jp/news/easy/top-list.json'],
            'yahoo' => ['html', 'https://finance.yahoo.com/tech'],
        ];

        foreach ($arrLink as $type => $value) {
            $data = $crawlerService->getData($value[1], 'get', [], $value[0]);

            if (empty($data)) {
                continue;
            }

            switch ($type) {
                case 'cafebiz':
                    $this->getNewsCafebiz($data);
                    break;
                case 'yahoo':
                    $this->getNewsYahoo($data);
                    break;
                case 'gadgetsnow':
                    $this->getNewsGadgetSnow($data);
                    break;
                case 'nhk':
                    $this->getNewsNHK($data);
                    break;
                default:
                    break;
            }
        }
    }

    private function getNewsCafebiz($data)
    {
        foreach ($data['rss']['channel']['item'] as $i => $item) {
            print ($i + 1) . " - " . $item['link']['cdata'] . "\n";

            dispatch(new GetNewsCafebiz([
                'title' => $item['title']['cdata'],
                'description' => $item['description']['cdata'],
                'link' => $item['link']['cdata'],
            ]))->onQueue('crawler');
        }
    }

    private function getNewsYahoo($data)
    {
        $crawler = $data['crawler'];

        $crawler->filter('li.js-stream-content')->each(function ($node, $i) {
            $anchor = $node->filter('a')->first();
            $img = $node->filter('img')->first();
            $title = $anchor->text();
            $description = $node->filter('p')->first()->text();
            $link = 'https://finance.yahoo.com' . $anchor->attr('href');
            $image = Str::after($img->attr('src'), '-/');

            print ($i + 1) . " - " . $link . "\n";

            dispatch(new GetNewsYahoo([
                'title' => $title,
                'description' => $description,
                'link' => $link,
                'image' => $image,
            ]))->onQueue('crawler');
        });
    }

    private function getNewsGadgetSnow($data)
    {
        $crawler = $data['crawler'];

        $crawler->filter('.tech_list > ul.cvs_wdt > li')->each(function ($node, $i) {
            $title = $node->filter('.w_tle')->first()->text();
            $description = $node->filter('.w_desc')->html();
            $link = $node->selectLink($title)->link()->getUri();

            print ($i + 1) . " - " . $link . "\n";

            dispatch(new GetNewsGadgetSnow([
                'title' => $title,
                'description' => $description,
                'link' => $link,
            ]))->onQueue('crawler');
        });
    }

    private function getNewsNHK($data)
    {
        foreach ($data as $i => $value) {
            $id_post = $value['news_id'];
            $link = 'https://www3.nhk.or.jp/news/easy/' . $id_post . '/' . $id_post . '.html';
            $image = $value['news_web_image_uri'];
            $title = $value['title'];

            print($i + 1) . " - " . $link . "\n";

            dispatch(new GetNewsNHK([
                'id_post' => $id_post,
                'link' => $link,
                'image' => $image,
                'title' => $title
            ]))->onQueue('crawler');
        }
    }
}
