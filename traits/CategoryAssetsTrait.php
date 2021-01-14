<?php namespace MG\PageBuilder\Traits;

trait CategoryAssetsTrait
{
    /**
     * @var integer
     */
    protected $category_number;
    /**
     * @var string
     */
    protected $category_dir;
    /**
     * @var string
     */
    protected $preview_ext = '.png';
    /**
     * @var string
     */
    protected $htmlfile;

    /**
     * Get all Categories and Category assets.
     *
     * @param string $theme
     * @param string $blocktype
     *
     * @return array
     */
    public function getCategoryAssets($theme, $blocktype)
    {
        $catdirs = glob(base_path().'/themes/'.$theme.'/partials/blocks/'.$blocktype.'/*' , GLOB_ONLYDIR);
        $categories = [];
        empty($this->category_number) && $this->category_number = 1;

        foreach ($catdirs as $catdir) {
            $categories[$this->category_number] = $this->populateCategory($catdir);
            $this->category_number++;
        }

        return $categories;
    }

    /**
     * Get all Categories and Category assets.
     *
     * @param string $fileid
     *
     * @return string
     */
    private function getFilePath($fileid)
    {
        $relative_path = str_replace(base_path(),'',$this->category_dir);
        $prevarr = explode('.',$this->htmlfile);

        switch ($fileid) {
            case 'css':
                $filepath = !empty($prevarr[0]) && file_exists($this->category_dir.'/css/'.$prevarr[0].'.css') ? $this->category_dir.'/css/'.$prevarr[0].'.css' : '';
                break;
            case 'relative_css':
                $cssfile = !empty($prevarr[0]) && file_exists($this->category_dir.'/css/'.$prevarr[0].'.css') ? $this->category_dir.'/css/'.$prevarr[0].'.css' : '';
                $filepath = $cssfile !== '' ? $relative_path.'/css/'.$prevarr[0].'.css' : '';
                break;
            case 'preview':
                $filepath = $relative_path.'/preview/'.$prevarr[0].$this->preview_ext;;
                break;
            default:
                $filepath = '';
        }
        return $filepath;
    }

    /**
     * Get content of HTML file.
     *
     * @return string
     */
    private function getContent()
    {
        return file_get_contents($this->category_dir.'/'.$this->htmlfile);
    }

    /**
     * Get Category Name.
     *
     * @return string
     */
    private function getCategoryName()
    {
        $catnamearr = explode('/',$this->category_dir);
        $catname = end($catnamearr);
        //Replace all underscores (_) with spaces for category names
        return str_replace('_',' ',$catname);
    }

    /**
     * Get current Category and all its properties.
     *
     * @return object
     */
    private function getCategory()
    {
        $catobj = (object)Array();
        $catobj->preview = $this->getFilePath('preview');
        $catobj->category = $this->category_number;
        $catobj->html = $this->getContent();
        $catobj->googleFonts = [];
        $catobj->contentCss = $this->getFilePath('relative_css');
        $catobj->contentClass = $this->getCSSClass($this->getFilePath('css'));
        return $catobj;
    }

    /**
     * Return all categories from category directories as array of objects
     *
     * @param  string $catdir
     * @return array
     */
    private function populateCategory($catdir)
    {
        $this->category_dir = $catdir;
        $catfiles = [];
        if ($handle = opendir($this->category_dir)) {
            while (false !== ($htmlfile = readdir($handle))) {
                if ($htmlfile != "." && $htmlfile != ".." && !is_dir($this->category_dir.'/'.$htmlfile)) {
                    $catfiles[] = $this->getCategoryContent($htmlfile);
                }
            }
            closedir($handle);
            return $catfiles;
        } else {
            return [];
        }
    }

    /**
     * Get Custom content class in CSS file
     *
     * @param string $file_path
     *
     * @return string
     */
    private function getCSSClass($file_path)
    {
        if ($file_path != '' && file_exists($file_path)) {
            $cssfile = fopen($file_path, 'r');
            $line = fgets($cssfile);
            fclose($cssfile);
            $asnmt = str_replace('/*', '', $line);
            $asnmt = str_replace('*/', '', $asnmt);
            $asnmt = str_replace("\n", '', $asnmt);
            $asnmt = str_replace(' ', '', $asnmt);
            $asnmtarr = explode('=', $asnmt);
            if (!empty($asnmtarr[0]) && !empty($asnmtarr[1]) && $asnmtarr[0] == 'content-class') {
                return $asnmtarr[1];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}
