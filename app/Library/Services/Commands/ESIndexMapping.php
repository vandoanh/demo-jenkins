<?php
namespace App\Library\Services\Commands;

use App\Library\Models\MySql\Post;
use Illuminate\Console\Command;

class ESIndexMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:index-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index mapping elasticsearch.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('USED_ELASTICSEARCH_FLAG', false) == true) {
            if (Post::instance()->createIndex(env('ELASTICSEARCH_SHARDS'), env('ELASTICSEARCH_REPLICAS'))) {
                Post::instance()->putMapping();
            }
        }
    }
}
