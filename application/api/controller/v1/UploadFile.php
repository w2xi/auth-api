<?php 

namespace app\api\controller\v1;



class UploadFile 
{
    public function index()
    {
        return UPLOAD_PATH;
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

    public function submit()
    {
        echo '<form enctype="multipart/form-data" action="upload" method="POST">
                <!-- MAX_FILE_SIZE must precede the file input field -->
                <input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
                <!-- Name of input element determines name in $_FILES array -->
                Send this file: <input name="file" type="file" />
                <input type="submit" value="Send File" />
            </form>';
    }
}