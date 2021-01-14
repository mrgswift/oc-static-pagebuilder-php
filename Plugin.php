<?php namespace MG\PageBuilder;

use System\Classes\PluginBase;
use App;
use Event;
use Cms\Classes\Theme;
use RainLab\Pages\Classes\Menu as PagesMenu;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'mg.pagebuilder::lang.plugin.name',
            'description' => 'mg.pagebuilder::lang.plugin.description',
            'author'      => 'Matthew Guillot',
            'icon'        => 'icon-files-o'
        ];
    }
    public function boot()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }

        //Add Builder button to RainLab/Pages plugin toolbar
        Event::listen('backend.form.extendFields', function ($widget, $fields) {
            $controller = $widget->getController();
            if ($controller instanceof \RainLab\Pages\Controllers\Index && $widget->model instanceof \RainLab\Pages\Classes\Page) {
                $controller->addJs('/plugins/mg/pagebuilder/assets/js/october/toolbarbtn.js');
                !empty($fields['toolbar']) && $fields['toolbar']->path = 'plugins/mg/pagebuilder/controllers/index/page_toolbar';

                $widget->addTabFields([
                    'viewBag[banner_image]' => [
                        'tab' => 'mg.pagebuilder::lang.page.banner_tab',
                        'label' => 'mg.pagebuilder::lang.page.banner_image',
                        'field' => 'viewBag[banner_image]',
                        'span' => 'auto',
                        'type' => 'mediafinder',
                        'mode' => 'image',
                        'comment' => 'Banner Image at the top of the page'
                    ],
                    'viewBag[banner_title]' => [
                        'tab' => 'mg.pagebuilder::lang.page.banner_tab',
                        'label' => 'mg.pagebuilder::lang.page.banner_title',
                        'field' => 'viewBag[banner_title]',
                        'span' => 'auto',
                        'type' => 'text'
                    ],
                    'viewBag[page_navigation]' => [
                        'tab' => 'mg.pagebuilder::lang.page.banner_tab',
                        'label' => 'mg.pagebuilder::lang.page.secondary_nav',
                        'options' => $this->getMenuCodeOptions(),
                        'showSearch' => true,
                        'span' => 'auto',
                        'required' => 1,
                        'type' => 'dropdown',
                        'placeholder' => '- No Menu Selected -'
                    ],
                ]);
            }
        });
    }
    private function getMenuCodeOptions()
    {
        $result = [];

        $theme = Theme::getEditTheme();
        $menus = PagesMenu::listInTheme($theme, true);

        foreach ($menus as $menu) {
            $result[$menu->code] = $menu->name;
        }

        return $result;
    }
}
