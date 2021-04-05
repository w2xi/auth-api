<?php 

namespace app\api\controller\v1;

use think\Request;
use think\File;

class UploadFile 
{
    public function test()
    {
        echo md5_file(UPLOAD_PATH . 'test/07/ea335b77ad23bb411cdcdbc82f7c55.png');
    }

    public function upload()
    {

        $upload = new \Delight\FileUpload\FileUpload();
        $upload->withTargetDirectory(UPLOAD_PATH.'test/');
        $upload->from('file');
        $upload->withMaximumSizeInMegabytes(10);

        $uploadedFile = $upload->save();
        
        // success
        $data = [];
        $data['filenameWithExtention'] = $uploadedFile->getFilenameWithExtension();
        $data['filename'] = $uploadedFile->getFilename();
        $data['extension'] = $uploadedFile->getExtension();
        $data['directory'] = $uploadedFile->getDirectory();
        $data['path'] = $uploadedFile->getPath();
        $data['CanonicalPath'] = $uploadedFile->getCanonicalPath();

        return json($data);
    }

    public function uploadWithTp(Request $request)
    {
        // $file = $_FILES['file'];
        // echo '<pre>';
        // print_r($file);

        // multiple file upload

        $file = $request->file('file');

        foreach ($file as $value) {
            $this->singleUpload($value);
        }

        // $this->singleUpload($file);
    }

    public function singleUpload(File $file = null)
    {
        $file = $file ?? request()->file('file');

        if ( $file ){
            $info = $file->validate(['size'=>1024*1024*3, 'ext'=>'png,jpg,jpeg'])->rule('md5')->move(UPLOAD_PATH . 'test/');
            if ( $info ){
                echo 'extension: ' . $info->getExtension() . '<br />';
                echo 'filename: ' . $info->getFilename() . '<br />';
                echo 'saveName: ' . $info->getSaveName() . '<br />';
                echo $info->md5() . '<br />';
            }else{
                return _error($file->getError());
            }
        }else{
            return _error('请选择要是上传的文件');
        }
    }

    public function submit()
    {
        // nowdoc
        $form = <<<'EOD'
<form enctype="multipart/form-data" action="uploadWithTp" method="POST">
<!-- MAX_FILE_SIZE must precede the file input field -->
<input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
<!-- Name of input element determines name in $_FILES array -->
Send this file: 
<input name="file[]" type="file" multiple />
<input type="submit" value="Send File" />
</form>
EOD;
        echo $form;
    }
}