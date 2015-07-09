<?php namespace NetAuth;

class RemoveAdminBar
{
    /**
     * @var array
     */
    private $menuItems = ['Dashboard'];

    /**
     *
     */
    public function __construct()
    {
        add_filter('after_setup_theme', [$this, 'removeAdminBar']);
        add_action('admin_menu', [$this, 'removeAdminMenuItems']);
    }

    /**
     * @return bool
     */
    private function hasPermission()
    {
        return !current_user_can('manage_options');
    }

    /**
     * @return bool
     */
    public function removeAdminBar()
    {
        // add filters only if its not admin.
        if ( !$this->hasPermission() )
            return false;

        show_admin_bar(false);
        add_filter('show_admin_bar', '__return_false');
        add_filter('wp_admin_bar_class', '__return_false');
    }

    /**
     * @return bool
     */
    public function removeAdminMenuItems()
    {
        global $menu;

        // add filters only if its not admin.
        if ( !$this->hasPermission() )
            return false;

        $remove = array_filter($this->menuItems, '__');
        end($menu);
        while (prev($menu)) {
            $item = explode(' ', $menu[key($menu)][0]);
            if ( in_array($item[0] != null
                ? $item[0]
                : "", $remove) ) {
                unset($menu[key($menu)]);
            }
        }

        // add logout button in front end.
        if (preg_match("/src\s*=\s*('|\")(.*?)(\"|')/", get_avatar(get_current_user_id(), 20), $m)) {
            $menu[] = [
                'Log Out',
                'read', wp_logout_url(),
                'Logout',
                'menu-top',
                'logout',
                $m[2]
            ];
        }
    }
}