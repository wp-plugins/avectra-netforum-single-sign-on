<?php namespace NetAuth\Views;

use WP\Views\View;

class Netforum extends View
{
    protected $fields = [
        'single sign on' => [
            'desc'   => 'xWeb API settings for netFORUM.',
            'fields' => [
                'wsdl'     => [
                    'title'    => 'xWeb WSDL Url',
                    'desc'     => 'xWeb WSDL Url.',
                    //['<a href="#" id="test-connection">Test Connection?</a>',
                    //'xWeb WSDL url, must start with http:// and ends with .wsdl'],
                    'validate' => [
                        '(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?.+',
                        'must start with http:// or https://'
                    ],
                    'required' => true,
                ],
                'username' => [
                    'title'    => 'xWeb Username',
                    'desc'     => 'Username to the xWeb user account, format (a-z 0-9 _-)',
                    'validate' => ['[a-zA-Z0-9_]+', 'format (a-z 0-9 _-)'],
                    'required' => true,
                ],
                'password' => [
                    'title'    => 'xWeb Password',
                    'desc'     => 'Password to the xWeb user account.',
                    //'validate' => ['.{5,}', 'must be minimum 5 characters.'],
                    'required' => true,
                    'callback' => 'passwordfield'
                ],
            ],
        ],
        'connection'     => [
            'desc'   => 'Connection timeout settings for netFORUM.',
            'fields' => [
                'timeout'         => [
                    'title'    => 'Timeout',
                    'desc'     => 'How long to wait to hear a reply from netFORUM.',
                    'validate' => ['\d{1,2}', 'must be numeric.'],
                    'required' => true,
                    'default'  => 9,
                ],
                'connect_timeout' => [
                    'title'    => 'Connection Timeout',
                    'desc'     => 'How long to wait for the initial connection.',
                    'validate' => ['\d{1,2}', 'must be numeric.'],
                    'required' => true,
                    'default'  => 9,
                ],
            ],
        ],
    ];
}
