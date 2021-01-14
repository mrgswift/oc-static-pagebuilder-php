<?php namespace MG\PageBuilder\Classes;

/**
 * Class ResizeImage
 * @package MG\PageBuilder\Classes
 * Resizes Images (requires php gd extension)
 */
class ResizeImage
{
    protected $sourcepath;
    protected $savepath;
    protected $width;
    protected $height;
    protected $crop;

    /**
     * ResizeImage constructor.
     * @param string $sourcepath
     * @param string$savepath
     * @param integer $width
     * @param integer $height
     */
    public function __construct($sourcepath, $savepath, $width, $height, $crop=0)
    {
        $this->sourcepath = $sourcepath;
        $this->savepath = $savepath;
        $this->width = $width;
        $this->height = $height;
        $this->crop = $crop;
    }
    public function resize()
    {
        $required_props = ['sourcepath', 'savepath', 'width', 'height'];
        foreach ($required_props as $prop) {
            if (empty($this->$prop)) { return false; }
        }
        list($w, $h) = getimagesize($this->sourcepath);

        $type = strtolower(substr(strrchr($this->sourcepath,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($this->sourcepath); break;
            case 'gif': $img = imagecreatefromgif($this->sourcepath); break;
            case 'jpg': $img = imagecreatefromjpeg($this->sourcepath); break;
            case 'png': $img = imagecreatefrompng($this->sourcepath); break;
            default : return "Unsupported picture type!";
        }

        $ratio = min($this->width/$w, $this->height/$h);
        $this->width = $w * $ratio;
        $this->height = $h * $ratio;
        $x = 0;

        $new = imagecreatetruecolor($this->width, $this->height);

        // preserve transparency
        if($type == "gif" || $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $this->width, $this->height, $w, $h);

        switch($type){
            case 'bmp': imagewbmp($new, $this->savepath); break;
            case 'gif': imagegif($new, $this->savepath); break;
            case 'jpg': imagejpeg($new, $this->savepath); break;
            case 'png': imagepng($new, $this->savepath); break;
        }
        return true;
    }
    public function resizeCover()
    {
        $required_props = ['sourcepath', 'savepath', 'width', 'height'];
        foreach ($required_props as $prop) {
            if (empty($this->$prop)) { return false; }
        }
        if(!list($w, $h) = getimagesize($this->sourcepath)) return "Unsupported picture type!";

        $type = strtolower(substr(strrchr($this->sourcepath,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($this->sourcepath); break;
            case 'gif': $img = imagecreatefromgif($this->sourcepath); break;
            case 'jpg': $img = imagecreatefromjpeg($this->sourcepath); break;
            case 'png': $img = imagecreatefrompng($this->sourcepath); break;
            default : return "Unsupported picture type!";
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1629;
            $this->height = 850;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1533;
            $this->height = 800;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1438;
            $this->height = 750;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1380;
            $this->height = 720;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1342;
            $this->height = 700;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1246;
            $this->height = 650;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1150;
            $this->height = 600;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 1054;
            $this->height = 550;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 958;
            $this->height = 500;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 863;
            $this->height = 450;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 767;
            $this->height = 400;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 671;
            $this->height = 350;
        }
        if($w < $this->width || $h < $this->height) {
            $this->width = 575;
            $this->height = 300;
        }
        if($w < $this->width || $h < $this->height) {
            return "Picture is too small. Minimum dimension: 575 x 350 pixels.";
        }

        // resize
        if($this->crop){
            $ratio = max($this->width/$w, $this->height/$h);
            $h = $this->height / $ratio;
            $x = ($w - $this->width / $ratio) / 2;
            $w = $this->width / $ratio;
        }
        else{
            $ratio = min($this->width/$w, $this->height/$h);
            $this->width = $w * $ratio;
            $this->height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($this->width, $this->height);

        // preserve transparency
        if($type == "gif" || $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $this->width, $this->height, $w, $h);

        switch($type){
            case 'bmp': imagewbmp($new, $this->savepath); break;
            case 'gif': imagegif($new, $this->savepath); break;
            case 'jpg': imagejpeg($new, $this->savepath); break;
            case 'png': imagepng($new, $this->savepath); break;
        }
        return true;
    }
}
