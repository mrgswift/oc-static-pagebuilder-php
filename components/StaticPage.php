<?php namespace MG\PageBuilder\Components;

use Cms\Classes\Theme;
use RainLab\Pages\Classes\Router;

class StaticPage extends \RainLab\Pages\Components\StaticPage
{

    /**
     * @var \RainLab\Pages\Classes\Page A reference to the static page object
     */
    public $pageObject;

    /**
     * Constructor.
     */
    public function __construct($pageuri)
    {
        parent::__construct();
        $url = $pageuri;

        if (!strlen($url)) {
            $url = '/';
        }

        $router = new Router(Theme::getActiveTheme());
        $this->pageObject = $this->page['page'] = $router->findByUrl($url);

        if ($this->pageObject) {
            $this->title = $this->page['title'] = array_get($this->pageObject->viewBag, 'title');
            $this->extraData = $this->page['extraData'] = $this->defineExtraData();
        }
    }

    /**
     * Return Page Content from Rainlab StaticPage instance
     *
     * @return string
     */
    public function getContent()
    {
        if ($this->contentCached !== false) {
            return $this->contentCached;
        }

        if ($this->pageObject) {
            return $this->contentCached = $this->pageObject->getProcessedMarkup();
        }

        $this->contentCached = '';
    }

    /**
     * Return viewBag from Rainlab StaticPage instance
     *
     * @return array
     */
    public function getViewBag()
    {
        return !empty($this->pageObject->viewBag) ? $this->pageObject->viewBag : null;
    }

    /**
     * Return Theme Directory name from Theme class
     *
     * @return string
     */
    public function getTheme()
    {
        return Theme::getActiveTheme()->getDirName();
    }
}
