<?php namespace WP\Views;

use WP\Traits\Helpers;

/**
 * Class Page
 *
 * @package WP\Views
 */
class Page
{
    use Helpers;

    /**
     * @var
     */
    public static $default;
    /**
     * @var
     */
    public static $static;

    /**
     *
     */
    public function __construct()
    {
        self::set('caller', get_called_class());

        $actions = [
            'nf_logo' => 'getHeaderLogo',
            'nf_head' => 'getHeader',
            'nf_tabs' => 'getTabs',
            'nf_foot' => 'getFooter',
        ];

        add_action('admin_menu', [$this, 'makeView']);
        array_walk($actions, function ($v, $k) {
            if ( method_exists(self::get('caller'), $v) ) {
                add_action($k, [self::get('caller'), $v]);
            }
        });
    }

    /**
     * This function is basically the construct()
     * getTpl() is used as static and we getInstance
     * once instance is initiated actual construct
     * comes into play.
     */
    static public function getTpl()
    {
        // initiate the instance via the caller page.
        // caller page is extending WP\Views\View and
        // it uses singleton so we can call getInstance().
        $obj = self::get('namespace') . '\\' . ucfirst(camel_case(self::get('page')));
        class_exists($obj)
            ? add_action('nf_body', [$obj, 'getInstance'])
            : null;

        include_once(self::get('templatePath') . '/main.tpl');

        /*dd('dynamic page: ' . self::get('page'));
        dd('nf_tabs did action: ' . did_action('nf_tabs'));
        dd('nf_body did action: ' . did_action('nf_body'));*/
        if ( !class_exists($obj) ) {
            dd('class ' . $obj . ' doesn\'t exist.');
        }
    }

    /**
     *
     */
    static public function makeView()
    {
        // bootstrap the plugin.
        // sets plugin paths using reflection ;)
        self::bootstrap();
        /////////////

        $caller = self::get('caller');
        //dd(__FUNCTION__ . ' caller: ' . $caller);
        array_walk($caller::$plugin, function ($v, $k) use ($caller) {

            // if menu item doesnt exist only then
            // add main menu item.
            if ( !self::isMenuItemExists($k) ) {
                add_menu_page(
                    ucwords($k), $k,
                    'manage_options', $v['uri'],
                    [$caller, 'getTpl'],
                    self::get('assetsUrl') . $v['menu_logo']
                );
            }

            array_walk($v['pages'], function ($uri, $page) use ($v, $caller) {
                add_submenu_page(
                    $v['uri'],
                    ucwords($page), ucwords($page),
                    'manage_options',
                    $uri, [$caller, 'getTpl']
                );
            });
        });
    }

    /**
     * @param $item
     * @return bool
     */
    static public function isMenuItemExists($item)
    {
        global $menu;

        $exists = false;
        array_walk($menu, function ($v) use ($item, &$exists) {
            if ( preg_match('/^' . trim($item) . '$/i', $v[0]) ) {
                $exists = true;
            }
        });

        return $exists;
    }

    /**
     * @param      $tab
     * @param      $page
     * @param null $desc
     */
    static protected function makeTab($tab, $page, $desc = null)
    {
        $active = $page == self::getCurrentPage()
            ? 'nav-tab-active'
            : '';

        printf("<a class='nav-tab %s' href='?page=%s' title='%s'>%s</a>",
            $active,
            is_null($page)
                ? self::getCurrentPage()
                : $page,
            ucfirst($desc),
            ucwords($tab)
        );
    }

    /**
     *
     */
    static public function getTabs()
    {
        $caller = self::get('caller');
        //$caller = self::$caller;
        //dd("CALLER: " . $caller);
        //dd(array_shift($caller::$plugin)['pages']);
        array_walk(array_shift($caller::$plugin)['pages'],
            function ($uri, $page) {
                self::makeTab($page, $uri);
            }
        );
    }
}