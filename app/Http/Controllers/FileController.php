<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * 上傳檔案到暫存資料夾
     */
    public function temporary(Request $request)
    {
        try {
            // 清除過期暫存,可以設定排程來做
            $this->removeExpiredFiles();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        $file = $request->file('file');

        $exension = $file->getClientOriginalExtension();

        $fileName = $exension ? Str::uuid().'.'.$exension : Str::uuid();

        $isSuccess = Storage::disk('minio')->put('temporary/'.$fileName, $file);

        if ($isSuccess) {
            return response()->json(['file' => $fileName], 200);
        } else {
            return response()->json([
                'message' => 'file upload failed',
            ], 400);
        }
    }

    /**
     * 清除過期暫存
     */
    private function removeExpiredFiles()
    {
        try {
            $storageDisk = Storage::disk('minio');

            $files = $storageDisk->files('temporary');

            foreach ($files as $file) {
                $lastModified = Carbon::createFromTimestamp($storageDisk->lastModified($file));

                $timeDifference = Carbon::now()->diffInHours($lastModified);

                if ($timeDifference > 24) {
                    $storageDisk->delete($file);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
