<?php namespace MG\PageBuilder\Controllers;

use Backend\Facades\BackendMenu;
use Illuminate\Http\Request;
use App;

/**
 * Page Builder primary controller
 *
 * @package mg\pagebuilder
 * @author Matthew Guillot
 */
class Index extends PageBuilder
{
    public $requiredPermissions = ['rainlab.pages.*'];

    /**
     * Constructor.
     *
     * @param \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        BackendMenu::setContext('RainLab.Pages', 'pages', 'pages');
    }

    public function index()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }

        /*
         * Optional flag to retrieve third-party JS & CSS assets using remote CDN OR from local vendor directory
         * remote = remote cdn
         * local = local vendor directory
         */
        $this->setAssetSource('remote');

        //Populate frontend assets into controller
        $this->populateAssets();

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'mg.pagebuilder::lang.plugin.name';
        $this->pageTitleTemplate = '%s Static Page Builder';
    }
}
