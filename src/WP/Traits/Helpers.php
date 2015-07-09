<?php namespace WP\Traits;

trait Helpers
{
    /**
     * @param      $option
     * @param      $value
     * @param null $for
     */
    static public function set($option, $value, $for = null)
    {
        $class = is_null($for) ? get_called_class() : $for;
        self::$static[$class][$option] = $value;
    }

    /**
     * @param      $key
     * @param null $for
     * @return null
     */
    static public function get($key, $for = null)
    {
        $class = is_null($for) ? get_called_class() : $for;
        return isset(self::$static[$class][$key])
            ? self::$static[$class][$key]
            : null;
    }

    /**
     * @return string
     */
    static public function whoami()
    {
        return get_called_class();
    }

    /**
     *
     */
    static public function bootstrap()
    {
        // initiate reflection
        $class = get_called_class();
        $object = new \ReflectionObject(new $class);
        $dir = dirname($object->getFileName());
        $dir = dirname(dirname($dir));

        // get paths using reflection
        self::set('pluginPath', plugin_dir_path($dir));
        self::set('pluginUrl', plugin_dir_url($dir));

        // page uri and paths.
        self::set('page', self::getCurrentPage());
        self::set('caller', get_called_class());
        self::set('namespace', self::getNamespace(get_called_class()));
        self::set('assetsUrl', self::get('pluginUrl') . 'assets');
        self::set('templatePath', self::get('pluginPath') . 'assets/templates');
        self::set('jsPath', self::get('pluginPath') . 'assets/javascripts');
    }

    /**
     * @return mixed
     */
    static public function getCurrentPage()
    {
        return isset($_GET['page'])
            ? sanitize_title($_GET['page'])
            : self::getDefaultPage();
    }

    /**
     * @param null $pluginPath
     * @return null|string
     */
    static public function getJsPath($pluginPath = null)
    {
        $path = is_null($pluginPath)
            ? self::get('pluginPath')
            : dirname($pluginPath);

        $js = self::get('jsPath');
        return empty($js)
            ? $path . '/assets/javascripts'
            : $js;
    }

    /**
     * @param null $pluginPath
     * @return mixed
     */
    static public function getAssetsUrl($pluginPath = null)
    {
        $path = is_null($pluginPath)
            ? self::get('pluginUrl')
            : plugin_dir_url($pluginPath);

        $uri = self::get('assetsUrl');
        return empty($uri)
            ? esc_url_raw($path . 'assets')
            : esc_url_raw($uri);
    }

    /**
     * @param null $pluginPath
     * @return null|string
     */
    static public function getTemplatesPath($pluginPath = null)
    {
        $path = is_null($pluginPath)
            ? self::get('pluginPath')
            : dirname(dirname(dirname($pluginPath)));

        $tpl = self::get('templatePath');
        return empty($tpl)
            ? $path . '/assets/templates'
            : $tpl;
    }

    /**
     * @return mixed
     */
    static public function getDefaultPage()
    {
        return self::$default;
    }

    /**
     * @return mixed
     */
    static public function getPluginInfo()
    {
        return get_plugin_data(
            self::get('pluginPath') . '/' .
            self::getPluginFileName()
        );
    }

    /**
     * @return mixed
     */
    static public function getPluginVersion()
    {
        return self::getPluginInfo()['Version'];
    }

    /**
     * @param bool $ext
     * @return string
     */
    static public function getPluginFileName($ext = true)
    {
        return plugin_basename(
            self::get('pluginPath')) .
        ($ext ? '.php' : '');
    }

    /**
     * @param $c
     * @return string
     */
    static public function getNamespace($c)
    {
        return '\\' . substr($c, 0, strrpos($c, '\\'));
    }
}