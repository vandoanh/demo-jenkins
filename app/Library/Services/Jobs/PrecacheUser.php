<?php
namespace App\Library\Services\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Library\Models\User;

class PrecacheUser extends Job implements ShouldQueue
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
        $userInfo = User::instance()->getDetailUser($this->params['id'], true);

        if ($userInfo) {
            User::instance()->getDetailUserByEmail($userInfo->email, true);
        }

        if ($this->params['type'] !== config('constants.precache.type.create')) {
            User::instance()->getDetailUserByEmail($this->params['data']['email'], true);
        }
    }
}
