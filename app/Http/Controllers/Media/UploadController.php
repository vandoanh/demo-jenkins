<?php
namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Library\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadController extends Controller
{
    public function uploadByForm(Request $request, $type)
    {
        try {
            $file = $request->file('file');
            $auto_save = $request->auto_save ?? 0;

            if (!$file->isValid()) {
                return response()->json(['error' => 1, 'message' => $file->getErrorMessage()]);
            }

            $arrExt = config('site.media.type.' . $type);
            $limitSize = config('site.media.size.' . $type);

            // Get file info
            $fileName = $file->getClientOriginalName();
            $fileExt = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();
            $maxFileSize = min($file->getMaxFilesize(), $limitSize);

            // Check extension valid or not
            if (!in_array($fileExt, $arrExt)) {
                return response()->json(['error' => 1, 'message' => 'The file <b>' . $fileName . '</b> is not supported, only supports the following: ' . implode(',', $arrExt)]);
            }

            // Check size
            if ($fileSize > $maxFileSize) {
                return response()->json(['error' => 1, 'message' => 'The size of the file <b>' . $fileName . '</b> exceeds the allowed size of ' . $maxFileSize]);
            }

            // Get file name exclude extension
            $fileName = str_replace('.' . $fileExt, '', $fileName);
            if ($type === 'image') {
                $fileExt = 'jpg';
            }
            $fileNameNew = str_slug($fileName) . '_' . rand(1111, 9999) . '-' . time() . '.' . $fileExt;

            // Get disk storage
            $disk = Storage::disk(config('site.media.storage'));

            // Copy file
            $disk->put(config('site.media.path') . '/tmp/' . $fileNameNew, file_get_contents($file->getRealPath()));
        } catch (FileException $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }

        if ($auto_save) {
            $fileNameNew = CommonService::saveImageFromTmp($fileNameNew, $type);
        }

        return response()->json(['error' => 0, 'message' => 'Upload succeed!', 'filename' => $fileNameNew]);
    }

    public function uploadByUrl(Request $request, $type)
    {
        try {
            $disk = Storage::disk(config('site.media.storage'));

            $url = $request->url;
            $auto_save = $request->auto_save ?? 0;

            $fullName = basename($url);
            $fileName = substr($fullName, 0, strrpos($fullName, '.'));
            $fileExt = strtolower(substr(strrchr($fullName, '.'), 1));
            if ($type === 'image') {
                $fileExt = 'jpg';
            }
            $fileNameNew = str_slug($fileName) . '_' . rand(1111, 9999) . '-' . time() . '.' . $fileExt;

            $disk->put(config('site.media.path') . '/tmp/' . $fileNameNew, CommonService::getContent($url));

            if ($auto_save) {
                $fileNameNew = CommonService::saveImageFromTmp($fileNameNew, $type);
            }

            return response()->json(['error' => 0, 'message' => 'Upload succeed!', 'filename' => $fileNameNew]);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => 'Error!']);
        }
    }

    public function saveUpload(Request $request, $type, $file)
    {
        try {
            $disk = Storage::disk(config('site.media.storage'));

            if ($disk->exists('tmp/' . $file)) {
                $fileName = Carbon::now()->format('Y/m/d') . '/' . $file;

                $disk->put(config('site.media.path') . '/' . $type . '/' . $fileName, $disk->get('tmp/' . $file));

                return response()->json(['error' => 0, 'message' => 'Upload succeed!', 'filename' => $fileName]);
            } else {
                return response()->json(['error' => 1, 'message' => 'File is not exists.']);
            }
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => 'Error!']);
        }
    }
}
