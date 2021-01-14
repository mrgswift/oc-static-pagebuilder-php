<?php namespace MG\PageBuilder\Traits;

use Illuminate\Http\Request;
use MG\PageBuilder\Components\StaticPage;
use MG\PageBuilder\Exceptions\PluginAssetsMissing;
use MG\PageBuilder\Exceptions\PluginAssetsInvalid;
use MG\PageBuilder\Exceptions\ThemeAssetsMissing;
use MG\PageBuilder\Exceptions\ThemeAssetsInvalid;

trait PageBuilderAssetsTrait
{
    /**
     * @var string
     */
    protected $theme;
    /**
     * @var string
     */
    protected $enable_typography;
    /**
     * @var string
     * local || remote
     */
    protected $assetSource;

    /**
     * @var string
     */
    protected $pluginPath;
    /**
     * @var \MG\PageBuilder\Components\StaticPage
     */
    protected $page;
    /**
     * @var string
     */
    protected $sessionkey;
    /**
     * @var string
     */
    protected $token;
    /**
     * @var string
     */
    protected $formwidget;
    /**
     * @var string
     */
    protected $pageuri;
    /**
     * @var string
     */
    protected $referrer;
    /**
     * @var array
     */
    protected $customAssets = [
        'pagebuildercss_local' => '/plugins/mg/pagebuilder/assets/css/pagebuilder.min.css',
        'pagebuilderjs_local' =>  '/plugins/mg/pagebuilder/assets/js/pagebuilder.min.js'
    ];

    /**
     * Populate Session properties.
     *
     * @return void
     */
    protected function initPageBuilder(Request $request)
    {
        $request = request()->all();
        empty($request['page']) && $request['page'] = '/';
        $this->pluginPath = base_path().'/plugins/mg/pagebuilder/';
        $this->referrer = request()->headers->get('referer');
        $this->pageuri = $request['page'];
        $this->page = new StaticPage($this->pageuri);
        $this->theme = $this->page->getTheme();
    }

    /**
     * Populate all needed variables.
     *
     * @return void
     */
    protected function prepareVars()
    {
        $this->vars['pagetheme'] = $this->theme;
        $this->vars['pagetitle'] = $this->page->title;
        $this->vars['content'] = $this->page->getContent();
        $this->vars['viewBag'] = $this->page->getViewBag();
        $this->vars['objectmtime'] = $this->page->pageObject->attributes['mtime'];
        $this->enable_typography = $this->getConfig('plugin','enable_typography_menu');
        $this->vars['JsSessionVars'] = $this->getJsSessionVars();
    }

    /**
     * Read JSON file, decode, return
     *
     * @param string $path
     * @return mixed|null
     */
    protected function getConfigFromJSON($path)
    {
        if (file_exists($path)) {
            return json_decode(file_get_contents($path));
        }
        return null;
    }

    /**
     * Get asset config JSON string from config file.
     *
     * @return string
     */
    protected function getAssetConfig($config_source = 'plugin')
    {
        if ($config_source === 'plugin') {
            if (!file_exists($this->pluginPath . 'config/assets.json'))
                throw new PluginAssetsMissing;

            $assetconfig = $this->getConfigFromJSON($this->pluginPath . 'config/assets.json');

        } elseif ($config_source === 'theme') {
            if (!file_exists(base_path().'/themes/' . $this->theme . '/config/assets.json'))
                throw new ThemeAssetsMissing($this->theme);

            $assetconfig = $this->getConfigFromJSON(base_path().'/themes/' . $this->theme . '/config/assets.json');
        }

        if (empty($assetconfig)) {
            if ($config_source === 'theme') { throw new ThemeAssetsInvalid($this->theme); }
            else { throw new PluginAssetsInvalid; }
        }

        return $assetconfig;
    }

    protected function getAssetsFromConfig($source='plugin')
    {
        $assets = $this->getAssetConfig($source);

        if ($this->assetSource === 'remote' || $this->assetSource === 'local') {
            $sourceAssets = $source === 'theme' ? 'localAssets' : $this->assetSource . 'Assets';

            $this->addAssets($assets, 'css', $sourceAssets);

            $this->addAssets($assets, 'js', $sourceAssets);

            $source !== 'theme' && $this->prepareVars();
        }
    }

    protected function addAssets($assets, $type, $source)
    {
        $addCall = 'add'.ucfirst($type);
        foreach ($assets->$source->$type as $asset) {
            (strpos($asset, '!') !== false && $customkey = str_replace('!', '', $asset))
                ? $this->$addCall($this->customAssets[$customkey]) :
                    ($this->assetSource === 'local' ? file_exists($asset) &&
                        $this->$addCall($asset) : $this->$addCall($asset));
        }
    }
    /**
     * Return necessary JS session vars
     *
     * @ return string
     */
    protected function getJsSessionVars()
    {
        $vars = $this->vars;
        $typostyling = $this->enable_typography ? 'true' : 'false';

        return 'viewBag = \''.json_encode($vars['viewBag']).'\',pagetitle= \''.$vars['pagetitle'].'\',pagetheme = \''.$this->theme.'\',pageReferrer = \''.$this->referrer.'\';
        let objectmtime = \''.$vars['objectmtime'].'\';
        localStorage.setItem(\'mg.pagebuilder.theme\', pagetheme);
        localStorage.setItem(\'mg.pagebuilder.typographystyles\', \''.$typostyling.'\')';
    }
}
