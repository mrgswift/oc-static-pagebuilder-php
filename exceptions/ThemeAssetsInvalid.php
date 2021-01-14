<?php namespace MG\PageBuilder\Exceptions;

class ThemeAssetsInvalid extends PageBuilderException
{
    /**
     * {@inheritdoc}
     */
    public $httpStatusCode = 422;

    /**
     * {@inheritdoc}
     */
    public $errorType = 'theme_assets_invalid';

    /**
     * {@inheritdoc}
     */
    public function __construct($theme)
    {
        parent::__construct('Static Page Builder assets configuration file: ' . base_path().'/themes/' . $theme . '/config/assets.json is not a valid JSON string!');
    }
}
