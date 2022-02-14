<?php

namespace App\Helpers;

trait UseHelper{
    
    // Common File Uplode
    public function fileUplode($file, string $path, $fileName = "") {
        $fileName = time() . "_$fileName." . $file->getClientOriginalExtension();
        $file->move("storage/app/media/$path", $fileName);
        return $fileName;
    }


    // remove Media File With Param Path & FileName
    public function rmvMediaFile(string $path, $fileName){
        try {
            unlink("storage/app/media/$path/$fileName");
        } catch (\Throwable $th) {
        }
    } 

    // Json Success Message
    public function jsonSuccess($result, $message = null){
        $res['success'] = 1;
        $res['result'] = $result;
        if(isset($message)){
            $res['msg'] = $message;
        }

        return response()->json($res);
    } 

    // Return Validation Error
    public function jsonError($error){
        return response()->json([
            'success' => 0,
            'msg' => $error,
        ]);
    }
}