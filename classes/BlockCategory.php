<?php namespace MG\PageBuilder\Classes;
use MG\PageBuilder\Traits\CategoryAssetsTrait;

/**
 * Class BlockCategory
 * @package MG\PageBuilder\Classes
 * Class for BlockCategory retrieval
 */
class BlockCategory
{
    use CategoryAssetsTrait;

    /**
     * Get current Category number
     *
     * @return integer
     */
    public function getCategoryNumber()
    {
        return $this->category_number;
    }

    /**
     * Get current Category Content Object
     *
     * @param string $htmlfile
     *
     * @return object
     */
    public function getCategoryContent($htmlfile)
    {
        $this->htmlfile = $htmlfile;
        $outputobj = (object)Array();
        $outputobj->name = $this->getCategoryName();
        $outputobj->assets = $this->getCategory();

        return $outputobj;
    }
}
