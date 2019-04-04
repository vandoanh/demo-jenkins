<?php
namespace App\Library\Services\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Library\Services\Jobs\PrecacheImage as JobPrecacheImage;

class PrecacheImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:pre-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre cache all image in storage.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!config('site.cache.image.enable')) {
            return;
        }

        $disk = Storage::disk(config('site.media.storage'));
        $arrFiles = [];

        // find file
        foreach (config('site.cache.image.paths') as $path) {
            $arrFiles = array_merge($arrFiles, $disk->allFiles(config('site.media.path') . '/' . $path));
        }

        foreach ($arrFiles as $file) {
            $file = preg_replace('/^([a-z0-9]+\/)/', '', $file);
            dispatch(new JobPrecacheImage([
                'images' => $file
            ]))->onQueue('image');
        }
    }
}
