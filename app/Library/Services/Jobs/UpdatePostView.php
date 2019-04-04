<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use App\Library\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePostView extends Job implements ShouldQueue
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
        Post::instance()->updatePostView($this->params['id']);
    }
}
