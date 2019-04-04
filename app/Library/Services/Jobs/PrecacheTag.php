<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Models\Tag;

class PrecacheTag extends Job implements ShouldQueue
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
        $tagInfo = Tag::instance()->getDetailTag($this->params['id'], true);

        if ($tagInfo) {
            Tag::instance()->getDetailTag($tagInfo->title, true);
        }
        Tag::instance()->getListTag(true);

        if ($this->params['type'] !== config('constants.precache.type.create')) {
            Tag::instance()->getDetailTag($this->params['data']['title'], true);
        }
    }
}
