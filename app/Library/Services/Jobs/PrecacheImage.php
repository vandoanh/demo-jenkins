<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use App\Library\Services\CommonService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrecacheImage extends Job implements ShouldQueue
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
        if (!config('site.cache.image.enable')) {
            return;
        }

        $arrImage = array_wrap($this->params['images']);

        foreach ($arrImage as $image) {
            foreach (config('site.cache.image.sizes') as $size) {
                $imageUrl = image_url($image, $size);
                CommonService::getContent($imageUrl);
            }
        }
    }
}
