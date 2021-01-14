<?php namespace MG\PageBuilder\Exceptions;

class ThemeAssetsMissing extends PageBuilderException
{
    /**
     * {@inheritdoc}
     */
    public $httpStatusCode = 404;

    /**
     * {@inheritdoc}
     */
    public $errorType = 'theme_assets_missing';

    /**
     * {@inheritdoc}
     */
    public function __construct($theme)
    {
        parent::__construct('Theme assets configuration file is missing. Expected File: '.base_path().'/themes/' . $theme . '/config/assets.json does not exist!');
    }
}
