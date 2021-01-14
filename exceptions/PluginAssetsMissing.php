<?php namespace MG\PageBuilder\Exceptions;

class PluginAssetsMissing extends PageBuilderException
{
    /**
     * {@inheritdoc}
     */
    public $httpStatusCode = 404;

    /**
     * {@inheritdoc}
     */
    public $errorType = 'plugin_assets_missing';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('Static Page Builder assets configuration file is missing. Expected File: '.base_path().'/plugins/mg/pagebuilder/config/assets.json does not exist!');
    }
}
