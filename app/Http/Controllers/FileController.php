<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * 上傳檔案到暫存資料夾
     */
    public function temporary(FileRequest $request)
    {
        // 清除過期暫存,可以設定排程來做
        // $this->removeExpiredFiles();

        $storeFile = $request->file('file')->store('', 'minio-temporary');

        if ($storeFile) {
            return response()->json(['file' => basename($storeFile)], 200);
        } else {
            return response()->json(['message' => 'upload file failed'], 400);
        }
    }

    /**
     * 清除過期暫存,可以設定排程來做
     */
    private function removeExpiredFiles()
    {
        try {
            $storageDisk = Storage::disk('minio-temporary');

            $files = $storageDisk->files();

            foreach ($files as $file) {
                $lastModified = Carbon::createFromTimestamp($storageDisk->lastModified($file));

                $timeDifference = Carbon::now()->diffInHours($lastModified);

                if ($timeDifference > 24) {
                    $storageDisk->delete($file);
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
