<?php 

namespace app\index\controller;

class Upload 
{
	public function index()
	{
		return '<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="upload" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="file" type="file" />
    <input type="submit" value="Send File" />
</form>';
	}

	public function upload()
    {
        $upload = new \Delight\FileUpload\FileUpload();
        $upload->withTargetDirectory(UPLOAD_PATH . date('Y-m-d'));
        $upload->from('file');

        try {
            $uploadedFile = $upload->save();

            // success

            // $uploadedFile->getFilenameWithExtension()
            // $uploadedFile->getFilename()
            // $uploadedFile->getExtension()
            // $uploadedFile->getDirectory()
            // $uploadedFile->getPath()
            // $uploadedFile->getCanonicalPath()
        } catch (\Delight\FileUpload\Throwable\InputNotFoundException $e) {
            // input not found
        } catch (\Delight\FileUpload\Throwable\InvalidFilenameException $e) {
            // invalid filename
        } catch (\Delight\FileUpload\Throwable\InvalidExtensionException $e) {
            // invalid extension
        } catch (\Delight\FileUpload\Throwable\FileTooLargeException $e) {
            // file too large
        } catch (\Delight\FileUpload\Throwable\UploadCancelledException $e) {
            // upload cancelled
        }
    }
}
