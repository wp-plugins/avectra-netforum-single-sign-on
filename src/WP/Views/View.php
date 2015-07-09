<?php namespace WP\Views;

use Netforum\Traits\SingletonTrait;

/**
 * Class View
 *
 * @package WP\Views
 */
abstract class View extends Form
{
    use SingletonTrait;

    protected $group;
    protected $fields = [
        'abstract' => [
            'desc'   => 'this is the abstract section.',
            'fields' => [
                'field1' => [
                    'title'    => 'abstract field 1',
                    'desc'     => 'you should extend this abstract class and not use it directly.',
                    'validate' => ['[a-zA-Z0-9_]{5,}', 'must be minimum 5 characters, format (a-z 0-9 _-)'],
                    'required' => true,
                    'callback' => null,
                    'default'  => 'some value',
                    'filter'   => 'trim',
                ],
            ],
        ],
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->page = Page::getCurrentPage();
        $this->group = snake_case(end(explode('\\', get_called_class())));

        /*dd('I am in class: ' . get_called_class());
        dd('I am in page: ' . $this->page);
        dd('I am in group: ' . $this->group);*/

        if ( isset($_POST[$this->page]) && sizeof($_POST[$this->page]) > 0 ) {
            !$this->validate()
                ? $this->flash(true)
                : ($this->store() && $this->flash());
        }

        $this->init();
        $this->render();
    }

    /**
     * @return bool
     */
    protected function init()
    {
        register_setting(
            $this->page, // option group
            $this->group, // option name
            [$this, 'sanitize']
        );

        if ( sizeof($this->fields) <= 0 ) {
            return false;
        }

        array_walk($this->fields, function ($e, $key) {
            $this->makeSection($key, $e['desc']);

            // add on demand js for the section.
            if ( isset($e['js']) ) {
                Input::handleCallback($e['js']);
            }

            array_walk($e['fields'], function ($f, $k) use ($key) {
                $f += ['key'     => $k,
                       'section' => $key];
                $this->makeField($k, $f);
            });
        });
    }

    /**
     *
     */
    protected function render()
    {
        if ( sizeof($this->fields) > 0 ) {
            settings_fields($this->group);
            do_settings_sections($this->page);
            submit_button();
        }
    }

    /**
     * @param      $title
     * @param null $desc
     * @param null $cb
     * @return mixed
     */
    protected function makeSection($title, $desc = null, $cb = null)
    {
        /*dd('ADDING SECTION: ' . $title);
        dd('TITLE: ' . $title);
        dd('SECTION: ' . $this->toSlug($title));*/

        if ( is_null($cb) ) {
            $cb = function () use ($desc) {
                print $desc;
            };
        }

        return add_settings_section(
            $this->toSlug($title),
            ucwords($title), $cb,
            $this->page
        );
    }

    /**
     * @param       $id
     * @param array $e
     * @return mixed
     */
    protected function makeField($id, array $e)
    {
        // set default callback as text field.
        if ( is_null($e['callback']) ) {
            $e['callback'] = 'textfield';
        }

        // set callback to specified callback.
        if ( !is_array($e['callback']) ) {
            $e['callback'] = [
                __NAMESPACE__ . '\Input', $e['callback']
            ];
        }

        // make section slug.
        $section = $this->toSlug($e['section']);

        // set default arguments.
        if ( sizeof($e['args']) <= 0 ) {
            $e['args'] = [
                'group'   => $this->group,
                'section' => $section,
                'id'      => $id,
                'desc'    => $e['desc'],
                'default' => $e['default'],
                'filter'  => $e['filter'],
                'js'      => $e['js'],
            ];
        }

        // generate the field.
        return add_settings_field(
            $id, $e['title'], $e['callback'],
            $this->page, $section,
            $e['args']
        );
    }

    /**
     * @param        $e
     * @param string $delimeter
     * @return mixed
     */
    protected function toSlug($e, $delimeter = '_')
    {
        return preg_replace('/[^\w]/', $delimeter, strtolower($e));
    }
}