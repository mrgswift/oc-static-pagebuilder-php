<?php namespace MG\PageBuilder\Exceptions;

class PluginAssetsInvalid extends PageBuilderException
{
    /**
     * {@inheritdoc}
     */
    public $httpStatusCode = 422;

    /**
     * {@inheritdoc}
     */
    public $errorType = 'plugin_assets_invalid';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('Static Page Builder assets configuration file: '.base_path().'/plugins/mg/pagebuilder/config/assets.json is not a valid JSON string!');
    }
}
