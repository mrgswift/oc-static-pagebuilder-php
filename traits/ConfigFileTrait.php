<?php namespace MG\PageBuilder\Traits;
use Config;

trait ConfigFileTrait
{
    public function getConfig($source=null, $param=null)
    {
        if ($source === 'plugin' || $source === 'theme') {
            if ($source === 'theme' && !empty($param)) {
                $configpath = base_path().'/themes/'.$param.'/config/pagebuilder.php';
                $configvals = [];
                file_exists($configpath) && $configvals = require($configpath);
                return $configvals;
            } elseif ($source === 'plugin' && !empty($param)) {
                return Config::get('mg.pagebuilder::'.$param);
            }
        }
        return [];
    }
    public function configValue($value)
    {
        return !empty($value) ? $value : null;
    }
}
