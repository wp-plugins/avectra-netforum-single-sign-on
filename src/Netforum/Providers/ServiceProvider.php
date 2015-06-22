<?php namespace Netforum\Providers;

use \Netforum\Request;

class ServiceProvider
{
    public function __construct(array $config)
    {
        // mimic the full version of netforum package.
        $wsdl = $config['wsdl'];
        $this->simple = new Request($wsdl, $config);
    }
}