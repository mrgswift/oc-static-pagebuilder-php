<?php namespace MG\PageBuilder\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Config;
use System\Classes\MediaLibrary;
use MG\PageBuilder\Classes\ResizeImage;

trait ImageUploadTrait
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var string
     */
    protected $imagetype;
    /**
     * @var integer
     */
    protected $imagecount;
    /**
     * @var MediaLibrary
     */
    protected $medialibrary;
    /**
     * @var string
     */
    protected $tempfolder;
    /**
     * @var string
     */
    protected $pagefolder;
    /**
     * @var string
     */
    protected $uploadroot;
    /**
     * @var string
     */
    protected $pbuploaddir;
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var string
     */
    protected $imagefiletype;
    /**
     * @var string
     */
    protected $random;
    /**
     * Constructor.
     *
     * @param \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;

        $uri_segments = $this->request->segments();

        !empty($uri_segments[4]) && $this->imagetype = $uri_segments[4];
        !empty($uri_segments[5]) && $this->imagecount = $uri_segments[5];

        $this->pagefolder = $this->getConfig('plugin','media_library_subfolder');
        $this->tempfolder = $this->getConfig('plugin','uploads_temp_subfolder');
        $this->uploadroot = public_path() . Config::get('cms.storage.uploads.path');
        $this->pbuploaddir = $this->uploadroot.'/'.$this->tempfolder;
        $this->medialibrary = MediaLibrary::instance();
    }

    protected function isValidImageUpload()
    {
        $uploadOk = 0;
        $returnval = true;
        $this->imagefiletype = $this->request->fileCover->getClientOriginalExtension();

        // Check if image file is a actual image or fake image

        $check = getimagesize($this->request->fileCover->getFileInfo()->getRealPath());

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo $this->bodyOnload('alert(\'File is not an image.\')');
            $returnval = false;
        }

        // Allow certain file formats
        if ($this->imagefiletype != "jpg" && $this->imagefiletype != "png" && $this->imagefiletype != "jpeg" && $this->imagefiletype != "gif") {
            echo $this->bodyOnload('alert(\'Sorry, only JPG, JPEG, PNG & GIF files are allowed.\')');
            $returnval = false;
        }

        if ($uploadOk == 0) {
            echo $this->bodyOnload('alert(\'Sorry, your file was not uploaded.\'');
            $returnval = false;
        }
        return $returnval;
    }

    protected function processImageAndSave()
    {
        $input = $this->request->all();
        $b64str = $input['hidimg-' . $this->imagecount];
        $imgname = !empty($input['hidname-' . $this->imagecount]) ? $input['hidname-' . $this->imagecount] : 'img';
        $imgext = $input['hidtype-' . $this->imagecount];

        //Generate random file name here
        if($imgext == 'png'){
            $this->filename = $imgname . '-' . str_random(10) . '.png';
        } elseif($imgext == 'jpg') {
            $this->filename = $imgname . '-' . str_random(10) . '.jpg';
        } elseif($imgext == 'gif') {
            $this->filename = $imgname . '-' . str_random(10) . '.gif';
        }
        $this->saveImage($b64str);
    }

    protected function checkDirExistsOrCreate()
    {
        if (!$this->medialibrary->folderExists('/'.$this->pagefolder)){
            $this->medialibrary->makeFolder('/'.$this->pagefolder);
        }
        if (!Storage::exists($this->pbuploaddir)) {
            Storage::makeDirectory($this->pbuploaddir);
        }
    }

    protected function checkWrongJpgExtension($uploadedfile)
    {
        $uploadarr = explode('/',$uploadedfile);
        $filename = end($uploadarr);
        if (substr($filename, -5) === '.jpeg') {
            $newfilename = str_replace('.jpeg', '.jpg', $filename);
            Storage::move('uploads/'.$this->tempfolder.'/'.$filename, 'uploads/'.$this->tempfolder.'/'.$newfilename);
            $this->imagefiletype = 'jpg';
            return $this->tempfolder.'/'.$newfilename;
        }
        return $uploadedfile;
    }

    protected function saveResizedImage($uploadedfile)
    {
        $this->random = str_random(10);
        $this->filename = $this->random.'.'.$this->imagefiletype;
        if (true !== ($pic_error = @(new ResizeImage($this->uploadroot .'/'. $uploadedfile, $this->pbuploaddir .'/'. $this->filename, 1600, 1600))->resizeCover())) { //Resize image to max 1600x1600 to safe size
            echo $this->bodyOnload("alert('" . $pic_error . "')");
            exit;
        }
        //Put resized image into Media Library
        if ($this->medialibrary->put('/'.$this->pagefolder.'/'. $this->filename, file_get_contents($this->pbuploaddir .'/'.$this->filename))) {
            Storage::delete('uploads/'.$this->tempfolder.'/'.$this->filename);
        }
    }
    protected function saveImage($b64str)
    {
        //Save image
        $success = $this->medialibrary->put('/'.$this->pagefolder.'/'. $this->filename, base64_decode($b64str));

        if ($success === FALSE) {
            echo $this->bodyOnload("alert('Saving image to folder failed. Please check write permission on " .$this->pbuploaddir. "')");
        } else {
            //Replace image src with the new saved file
            echo $this->bodyOnload("parent.document.getElementById('img-".$this->imagecount."').setAttribute('src','".$this->medialibrary->url('/'.$this->pagefolder.'/'.$this->filename)."');  parent.document.getElementById('img-".$this->imagecount."').removeAttribute('id')");
        }
    }
    protected function applyBoxImage()
    {
        //Replace image src with the new saved file
        echo $this->bodyOnload("parent.applyBoxImage('".$this->medialibrary->url('/'.$this->pagefolder.'/'.$this->filename)."')");
    }

    protected function applyLargerImage()
    {
        //Replace image src with the new saved file
        echo $this->bodyOnload("parent.applyLargerImage('".$this->medialibrary->url('/'.$this->pagefolder.'/'.$this->filename)."')");
    }

    protected function applySliderImage()
    {
        //Replace image src with new saved file
        echo $this->bodyOnload("parent.sliderImageSaved('".$this->medialibrary->url('/'.$this->pagefolder.'/'.$this->filename)."')");
    }

    protected function bodyOnload($jsCallback)
    {
        return '<html><body onload="'.$jsCallback.'"></body></html>';
    }
}
