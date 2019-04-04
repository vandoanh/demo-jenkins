<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Models\Category;

class PrecacheCategory extends Job implements ShouldQueue
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
        $categoryInfo = Category::instance()->getDetailCategory($this->params['id'], true);

        if ($categoryInfo) {
            Category::instance()->getDetailCategoryByCode($categoryInfo->code, true);
            Category::instance()->getListParent($categoryInfo->id, true);
            Category::instance()->getListParent($categoryInfo->parent_id, true);
        }

        if ($this->params['type'] !== config('constants.precache.type.create')) {
            Category::instance()->getDetailCategoryByCode($this->params['data']['code'], true);
            Category::instance()->getListParent($this->params['id'], true);
            Category::instance()->getListParent($this->params['data']['parent_id'], true);
        }
    }
}
