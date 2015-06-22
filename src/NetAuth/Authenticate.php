<?php namespace NetAuth;

class Authenticate
{
    private $ssoToken;

    /**
     *
     */
    public function __construct()
    {
        add_filter('authenticate', [$this, 'validate'], 10, 3);
    }

    /**
     * @param $user
     * @param $username
     * @param $password
     * @return bool|\WP_Error|\WP_User
     */
    public function validate($user, $username, $password)
    {
        if ( empty($username) || empty($password) ) {
            return false;
        }

        try {
            $user = get_user_by('login', $username);

            // nf meta wont exist if its a local wp user.
            $meta = get_user_meta($user->ID, 'netforum', true);

            // let regular wp users sign in.
            if ( is_object($user) && empty($meta) ) {
                return false;
            }

            // external authentication
            $nf = $this->authenticate($username, $password);

            // reference.
            $uData = [
                'user_email' => strtolower($nf->EmailAddress),
                'user_login' => strtolower($nf->EmailAddress),
                'first_name' => ucwords($nf->ind_first_name),
                'last_name'  => ucwords($nf->ind_last_name),
                'nickname'   => ucwords($nf->ind_first_name) . ' ' .
                    ucwords($nf->ind_last_name),
                ''
            ];

            // add new user if authenticated.
            if ( !is_object($user) ) {
                // sync user
                // useful if email or names were changed on nf.
                if ( $syncId = $this->isSyncNeeded($nf->cst_id, $user->ID) ) {

                    global $wpdb;
                    // update user login.
                    $wpdb->update($wpdb->users, array_slice($uData, 0, 2), [
                        'ID' => $syncId
                    ]);

                    // update user meta data.
                    $user = new \WP_User(wp_update_user([
                            'ID' => $syncId
                        ] + $uData
                    ));

                } else {
                    // create new user.
                    $user = new \WP_User(wp_insert_user($uData));
                }
            } else {
                // update user meta data on each login.
                // first, last names could have changed.
                wp_update_user($uData + [
                        'ID' => $user->ID
                    ]);
            }

            // add sso to meta & session.
            $this->setSession($nf, $user);

            // add call for groups plugin
            do_action('nf_SyncGroups');
        }
        catch (\Exception $e) {
            // comment, if you wish to fall back on WordPress authentication
            remove_action('authenticate', 'wp_authenticate_username_password', 20);
            $user = new \WP_Error('denied', __('Uh Oh!<br> ' . $e->getMessage()));
        }

        return $user;
    }

    /**
     * @param $nf
     * @param $user
     */
    protected function setSession($nf, $user)
    {
        $params = [
            'cst_id'    => (int) $nf->cst_id,
            'cst_key'   => (string) $nf->cst_key,
            'sso_token' => $this->ssoToken
        ];

        // add to meta
        update_user_meta($user->ID, 'netforum', $params);

        // add to session.
        if ( !session_id() )
            session_start();

        $_SESSION += [
            'netforum' => $nf
        ];
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    protected function authenticate($username, $password)
    {
        $opt = get_option('netforum');
        if ( !is_array($opt) || empty($opt['single_sign_on']['wsdl']) ) {
            throw new \Exception('Something went wrong, netforum xweb credentials not set.');
        }

        $conf = [
            'debug'       => false,
            'ttl'         => 12,
            'timeout'     => $opt['connection']['timeout'],
            'wsdl'        => $opt['single_sign_on']['wsdl'],
            'username'    => $opt['single_sign_on']['username'],
            'password'    => $opt['single_sign_on']['password'],
            'credentials' => [
                'username' => $username,
                'password' => $password
            ],
        ];

        // full version support.
        if ( class_exists('Netforum\Views\NetforumCache') ) {
            $opt = get_option('netforum_cache');
            $conf += ['cache' => [
                'path'   => __DIR__ . '/tmp/',
                'secret' => $opt['cache']['key'],
                'ttl'    => $opt['cache']['ttl'],
            ]];
        }

        $nf = new \Netforum\Providers\ServiceProvider($conf);

        // basic vs full version support.
        if ( property_exists($nf, 'auth') ) {
            // inherit sso.
            $this->ssoToken = $nf->auth->getSsoToken();
            return $nf->onDemand->getCustomerByKey();
        } else {
            $this->ssoToken = $nf->simple->getSsoToken();
            return $nf->simple->getCustomerByKey();
        }
    }

    /**
     * @param $cstId
     * @param $userId
     * @return bool
     */
    private function isSyncNeeded($cstId, $userId)
    {
        global $wpdb;

        if ( $cstId <= 0 ) {
            return false;
        }

        $q = $wpdb->get_row(
            sprintf('select * from %s where meta_value like "%s" limit 1',
                $wpdb->usermeta,
                '%\"cst_id\";i:' . (int) esc_sql($cstId) . ';%'
            ));

        if ( !is_object($q) ) {
            return false;
        }

        // sync
        return $userId != $q->user_id
            ? $q->user_id
            : false;
    }
}