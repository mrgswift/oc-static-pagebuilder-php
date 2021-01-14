<?php namespace MG\PageBuilder\Controllers;

use Backend\Models\User;
use Illuminate\Http\JsonResponse;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Storage;
use MG\PageBuilder\Traits\ConfigFileTrait;
use MG\PageBuilder\Traits\ImageUploadTrait;
use BackendAuth;
use Config;
/**
 *
 * Save uploaded image files
 * to Media Manager
 *
 */
class SaveImage extends Controller
{
    use ConfigFileTrait;
    use ImageUploadTrait;

    /**
     * Attempts to save modified image to media manager folder, then outputs html response
     * based on whether image save was successful
     */
    public function index()
    {

        if (!BackendAuth::getUser() instanceof User) {
            return JsonResponse::create(['message' => 'Access to this resource is denied'], 401);
        }
        header('Cache-Control: no-cache, must-revalidate');

        if (!empty($this->imagetype)) {

            $this->checkDirExistsOrCreate();

            if ($this->imagetype == 'normal') {
                $this->processImageAndSave();
            } else {
                $uploadname = $this->imagetype == 'cover' ? 'fileCover' : 'fileImage';
                $uploadedfile = $this->request->$uploadname->store('uploads/'.$this->tempfolder);

                //Fix wrong extension for some jpg images
                $uploadedfile = $this->checkWrongJpgExtension($uploadedfile);

                //Save Resized image
                $this->saveResizedImage($uploadedfile);

                //Delete original image
                Storage::delete('uploads/'.$uploadedfile);

                $this->imagetype == 'cover' && $this->applyBoxImage();
                $this->imagetype == 'large' && $this->applyLargerImage();
                $this->imagetype == 'module' && $this->applySliderImage();
            }
        } else {
            //Invalid request URL
            echo $this->bodyOnload("alert('Request URL was not valid!')");
        }
    }
}

