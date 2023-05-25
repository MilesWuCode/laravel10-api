<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * 上傳檔案到暫存資料夾
     */
    public function temporary(Request $request)
    {
        // 清除過期暫存,可以設定排程來做
        $this->removeExpiredFiles();

        $file = $request->file('file');

        $exension = $file->getClientOriginalExtension();

        $fileName = $exension ? Str::uuid().'.'.$exension : Str::uuid();

        $isSuccess = Storage::disk('minio')->put('temporary/'.$fileName, $file);

        if (! $isSuccess) {
            return response()->json(['file' => $fileName], 200);
        } else {
            return response()->json([
                'message' => 'file upload failed',
                'errors' => 'file upload failed',
            ], 400);
        }
    }

    /**
     * 清除過期暫存
     */
    private function removeExpiredFiles()
    {
        // $path = config('filesystems.disks.temporary.root');

        // if (is_dir($path)) {
        //     $dh = opendir($path);

        //     if ($dh) {
        //         while (false !== ($file = readdir($dh))) {
        //             if (is_file($path.'/'.$file)) {
        //                 $time = filemtime($path.'/'.$file);

        //                 if ((time() - $time) > 24 * 3600) {
        //                     // unlink($path.'/'.$file);
        //                     Storage::disk('temporary')->delete($file);
        //                 }
        //             }
        //         }

        //         closedir($dh);
        //     }
        // }
    }
}
