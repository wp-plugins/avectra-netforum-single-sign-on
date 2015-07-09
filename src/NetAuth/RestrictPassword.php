<?php namespace NetAuth;

class RestrictPassword
{
    /**
     *
     */
    public function __construct()
    {
        add_filter('show_password_fields', [$this, 'disable']);
        add_filter('allow_password_reset', [$this, 'disable']);
        add_filter('gettext', [$this, 'remove']);
    }

    /**
     * @return bool
     */
    public function disable()
    {
        if ( is_admin() ) {
            $data = wp_get_current_user();
            $user = new \WP_User($data->ID);

            return !empty($user->roles)
            && is_array($user->roles)
            && array_shift($user->roles) == 'administrator';
        }
        return false;
    }

    /**
     * @param $e
     * @return mixed
     */
    public function remove($e)
    {
        return preg_replace('/lost your password\??/is', '', $e);
    }
}

?>