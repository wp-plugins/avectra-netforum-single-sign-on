<?php namespace NetAuth\Views;

use WP\Views\View;
use WP\Views\Page;

class NetforumHelp extends View
{
    protected $fields = [];

    public function __construct()
    {
        $this->publish();
    }

    private function publish()
    {
        include_once(
            Page::getTemplatesPath(__DIR__) . '/help.tpl'
        );
    }
}