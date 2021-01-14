<?php namespace MG\PageBuilder\Controllers;
use Backend\Classes\Controller;
use Illuminate\Http\Request;
use MG\PageBuilder\Traits\ConfigFileTrait;
use MG\PageBuilder\Traits\PageBuilderAssetsTrait;

/**
 * PageBuilder Controller
 * @package MG\PageBuilder\Controllers
 */

class PageBuilder extends Controller
{
    use PageBuilderAssetsTrait;
    use ConfigFileTrait;

    /**
     * PageBuilder constructor
     *
     * @param  \Illuminate\Http\Request $request
     *
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->initPageBuilder($request);
    }

    /**
     * Setter for Asset Source
     *
     * @param string $source.
     *
     * @return void
     */
    public function setAssetSource($source)
    {
        ($source === 'remote' || $source === 'local') && $this->assetSource = $source;
    }

    /**
     * Populate all frontend assets.
     *
     * @return void
     */
    public function populateAssets()
    {
        $this->theme !== null && $this->getAssetsFromConfig('theme');
        $this->getAssetsFromConfig();
    }
}
