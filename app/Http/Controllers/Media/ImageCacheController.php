<?php
namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageCacheController extends Controller
{
    /**
     * Get HTTP response of either original image file or
     * template applied file.
     *
     * @param  string $template
     * @param  string $filename
     *
     * @return Illuminate\Http\Response
     */
    public function getResponse($template, $filename)
    {
        switch (strtolower($template)) {
            case 'original':
                return $this->getOriginal($filename);
            case 'download':
                return $this->getDownload($filename);
            case 'no-image':
                return $this->buildResponse(config('constants.image.default.file'), true);
            case 'no-avatar':
                return $this->buildResponse(config('constants.image.avatar.file'), true);
            default:
                return $this->getImage($template, $filename);
        }
    }

    /**
     * Get HTTP response of template applied image file
     *
     * @param  string $template
     * @param  string $filename
     *
     * @return Illuminate\Http\Response
     */
    public function getImage($template, $filename)
    {
        $size = $this->getSize($template);
        $path = $this->getImagePath($filename);

        // get image from cache first
        $content = null;
        if (config('site.cache.image.enable')) {
            $keyCache = CommonService::makeCacheKey(config('site.cache.image.key'), [$template, md5($filename)]);
            $content = CachingService::getInstance(config('site.cache.image.storage'))->getCache($keyCache);
        }

        if (empty($content)) {
            // image manipulation based on callback
            try {
                $manager = new ImageManager();
                $image = $manager->make($path)->fit($size[0], $size[1]);
                $content = $image->encode();
            } catch (\Exception $ex) {
                return $this->buildResponse(config('constants.image.default.file'), true);
            }

            if (config('site.cache.image.enable')) {
                CachingService::getInstance(config('site.cache.image.storage'))->writeCache($keyCache, $content, config('site.cache.image.lifetime'));
            }
        }

        return $this->buildResponse($content);
    }

    /**
     * Get HTTP response of original image file
     *
     * @param  string $filename
     *
     * @return Illuminate\Http\Response
     */
    public function getOriginal($filename)
    {
        $path = $this->getImagePath($filename);

        return $this->buildResponse(file_get_contents($path));
    }

    /**
     * Get HTTP response of original image as download
     *
     * @param  string $filename
     *
     * @return Illuminate\Http\Response
     */
    public function getDownload($filename)
    {
        $response = $this->getOriginal($filename);

        return $response->header(
            'Content-Disposition',
            'attachment; filename=' . $filename
        );
    }

    /**
     * Returns corresponding template object from given template name
     *
     * @param  string $template
     *
     * @return mixed
     */
    private function getSize($template)
    {
        $arrSize = config('site.cache.image.sizes');

        if (in_array($template, $arrSize)) {
            return explode('x', $template);
        }

        abort(404);
    }

    /**
     * Returns full image path from given filename
     *
     * @param  string $filename
     *
     * @return string
     */
    private function getImagePath($filename)
    {
        $disk = Storage::disk(config('site.media.storage'));

        // find file
        foreach (config('site.cache.image.paths') as $path) {
            // don't allow '..' in filename
            if ($disk->exists(config('site.media.path') . '/' . $path . '/' . $filename)) {
                return str_replace('//', '/', $disk->getDriver()->getAdapter()->getPathPrefix() . config('site.media.path') . '/' . $path . '/' . $filename);
            }
        }

        // file not found
        abort(404);
    }

    /**
     * Builds HTTP response from given image data
     *
     * @param  string $content
     * @param  boolean $base64
     *
     * @return Illuminate\Http\Response
     */
    private function buildResponse($content, $base64 = false)
    {
        // define mime type
        if (!$base64) {
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);
        } else {
            $image_parts = explode(';base64,', $content);
            $mime = explode(':', $image_parts[0]);
            $content = base64_decode($image_parts[1]);
            $mime = $mime[1];
        }

        // return http response
        return response($content, 200, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=' . (config('site.cache.image.lifetime') * 60) . ', public',
            'Etag' => md5($content)
        ));
    }
}
