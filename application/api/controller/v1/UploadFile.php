<?php 

namespace app\api\controller\v1;

use think\Request;
use think\File;
use traits\controller\Jump;

class UploadFile 
{
    public function test()
    {
        $upload = new \Delight\FileUpload\FileUpload();
        $upload->withTargetDirectory(UPLOAD_PATH.'test/');
        $upload->from('file');
        $upload->withMaximumSizeInMegabytes(10);

        $uploadedFile = $upload->save();
        
        // success
        $data = [];
        $data['filenameWithExtension'] = $uploadedFile->getFilenameWithExtension();
        $data['filename'] = $uploadedFile->getFilename();
        $data['extension'] = $uploadedFile->getExtension();
        $data['directory'] = $uploadedFile->getDirectory();
        $data['path'] = $uploadedFile->getPath();
        $data['CanonicalPath'] = $uploadedFile->getCanonicalPath();

        return json($data);
    }

    public function nativeMultiUploadFile()
    {
        $files = $_FILES['file'];

        if ( $files ){
            if ( is_array($files) ){
                $arr = [];
                $keys = array_keys($files);

                for ( $i = 0; $i < count($files['name']); $i++ )
                {
                    foreach ( $keys as $key )
                    {
                        $arr[$i][$key] = $files[$key][$i];
                    }
                }
                if ( $arr ){
                    foreach ( $arr as $file )
                    {
                        $this->nativeSingleUpload($file);
                    }
                }
            }
        }
    }

    public function nativeSingleUpload($file = null)
    {
        $file = $file ?: $_FILES['file'];

        if ( $file ){
            if ( is_uploaded_file($file['tmp_name'])) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = md5(microtime(true)) . '.' . $ext;
                $saveDir = UPLOAD_PATH . 'test';

                if ( !file_exists($saveDir) ){
                    mkdir($saveDir, 0755, true);
                }
                if ( !move_uploaded_file($file['tmp_name'], UPLOAD_PATH . 'test/' . $filename) ){
                    return _error('上传失败');
                }
            }else{
                return _error('非法文件');
            }
        }

        pp($file);

        return true;
    }

    public function multiUploadFileWithTp(Request $request)
    {
        $files = $request->file('file');

        if ( $files ){
            $arr = [];
            if ( is_array($files) ){
                foreach ( $files as $file ){
                    $info = $file->validate(['size'=>1024*1024*5, 'ext'=>'jpg,png'])->move(UPLOAD_PATH . 'test');
                    if ( $info ){
                        $arr[] = str_replace('\\', '/', UPLOAD_PATH . 'test/' . $info->getSaveName());
                    }
                }
                return _success($arr);
            }
        }else{
            return _success('请选择上传的文件');
        }
    }

    public function singleUploadWithTp(File $file = null)
    {
//        $file = new File($_FILES['file']['tmp_name']);
//        $file = $file->setUploadInfo($_FILES['file']);

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
<form enctype="multipart/form-data" action="nativeMultiUploadFile" method="POST">
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