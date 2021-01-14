<?php namespace MG\PageBuilder\Traits;

use Illuminate\Http\Request;
use MG\PageBuilder\Classes\BlockCategory;

trait ContentDataTrait
{
    /**
     * @var object
     */
    protected $output;
    protected $theme;


    /**
     * Constructor.
     *
     * @param \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        $params = $request->all();
        $this->theme = !empty($params['theme']) ? $params['theme'] : null;
        $this->output = (object)Array();
        $this->output->categories = [];
        $this->output->more = [];
        $this->output->category_index = [];
        $this->output->more_index = [];
        $this->output->snippets = [];
        $this->output->snippets_index = [];
    }

    /**
     * Get Content Block Categories and all of their assets
     *
     * @return array
     */
    protected function getBlockCategories()
    {
        $blockcategory = new BlockCategory;
        $categories = $blockcategory->getCategoryAssets($this->theme, 'categories');
        $catendnum = $blockcategory->getCategoryNumber() - 1;
        $more = $blockcategory->getCategoryAssets($this->theme, 'more');

        $categoryarr = array_merge($categories, $more);

        return ['categories' => $categoryarr, 'catend' => $catendnum];
    }

    /**
     * Get Content Snippet Categories and all of their assets
     *
     * @return array
     */
    protected function getSnippetCategories()
    {
        $blockcategory = new BlockCategory;
        $snippets = $blockcategory->getCategoryAssets($this->theme, 'snippets');

        return ['snippets' => $snippets];
    }
}
