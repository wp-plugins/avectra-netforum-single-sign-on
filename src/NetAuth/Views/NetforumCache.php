<?php namespace NetAuth\Views;

use WP\Views\View;

class NetforumCache extends View
{
    protected $fields = [
        'cache' => [
            'desc'   => 'Cache configuration for netFORUM module. <p><strong>Warning: </strong>Cache module has been disabled in this basic version. <br>Please contact <strong>info@fusionspan.com</strong> for full version.</p>',
            'fields' => [
                'key' => [
                    'title'    => 'Cache Secret Key',
                    'desc'     => 'Enter exactly 16 or 20 random characters for cache encryption.',
                    'validate' => ['.{16,20}', 'must be exactly 16 or 20 random characters.'],
                    'required' => true,
                    'default'  => ['wp_generate_password', [20, true]],
                ],
                'ttl' => [
                    'title'    => 'Cache TTL',
                    'desc'     => 'Enter cache time to live settings in seconds.',
                    'validate' => ['\d+', 'must be numeric.'],
                    'required' => true,
                    'default'  => 86400,
                ],
            ],
        ],
    ];
}