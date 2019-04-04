<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Models\Comment;

class PrecacheComment extends Job implements ShouldQueue
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
        $commentInfo = Comment::instance()->getDetailComment($this->params['id'], true);

        if ($commentInfo) {
            if ($commentInfo->parent_id != 0) {
                Comment::instance()->getDetailComment($commentInfo->parent_id, true);
            }

            Comment::instance()->getListCommentByPost([
                'post_id' => $commentInfo->post_id
            ], true);

            Comment::instance()->countComment($commentInfo->post_id, true);
        }

        if ($this->params['type'] !== config('constants.precache.type.create')) {
            if ($this->params['data']['parent_id'] != 0) {
                Comment::instance()->getDetailComment($this->params['data']['parent_id'], true);
            }

            Comment::instance()->getListCommentByPost([
                'post_id' => $this->params['data']['post_id']
            ], true);

            Comment::instance()->countComment($this->params['data']['post_id'], true);
        }
    }
}
