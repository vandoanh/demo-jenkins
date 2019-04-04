<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Models\Notice;

class PrecacheNotice extends Job implements ShouldQueue
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
        Notice::instance()->getDetailNotice($this->params['id'], true);
        Notice::instance()->getListNotice([
            'item' => 0,
            'page' => 1,
        ], true);
    }
}
