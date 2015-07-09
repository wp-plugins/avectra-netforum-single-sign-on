<?php

if ( !function_exists('http_build_url') ) {
    define('HTTP_URL_REPLACE', 1);              // Replace every part of the first URL when there's one of the second URL
    define('HTTP_URL_JOIN_PATH', 2);            // Join relative paths
    define('HTTP_URL_JOIN_QUERY', 4);           // Join query strings
    define('HTTP_URL_STRIP_USER', 8);           // Strip any user authentication information
    define('HTTP_URL_STRIP_PASS', 16);          // Strip any password authentication information
    define('HTTP_URL_STRIP_AUTH', 32);          // Strip any authentication information
    define('HTTP_URL_STRIP_PORT', 64);          // Strip explicit port numbers
    define('HTTP_URL_STRIP_PATH', 128);         // Strip complete path
    define('HTTP_URL_STRIP_QUERY', 256);        // Strip query string
    define('HTTP_URL_STRIP_FRAGMENT', 512);     // Strip any fragments (#identifier)
    define('HTTP_URL_STRIP_ALL', 1024);         // Strip anything but scheme and host

    // Build an URL
    // The parts of the second URL will be merged into the first according to the flags argument.
    //
    // @param   mixed           (Part(s) of) an URL in form of a string or associative array like parse_url() returns
    // @param   mixed           Same as the first argument
    // @param   int             A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
    // @param   array           If set, it will be filled with the parts of the composed url like parse_url() would return
    function http_build_url($url, $parts = [], $flags = HTTP_URL_REPLACE, &$new_url = false)
    {
        $keys = ['user', 'pass', 'port', 'path', 'query', 'fragment'];

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ( $flags & HTTP_URL_STRIP_ALL ) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        } // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else if ( $flags & HTTP_URL_STRIP_AUTH ) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
        }

        // Parse the original URL
        $parse_url = parse_url($url);

        // Scheme and Host are always replaced
        if ( isset($parts['scheme']) )
            $parse_url['scheme'] = $parts['scheme'];
        if ( isset($parts['host']) )
            $parse_url['host'] = $parts['host'];

        // (If applicable) Replace the original URL with it's new parts
        if ( $flags & HTTP_URL_REPLACE ) {
            foreach ($keys as $key) {
                if ( isset($parts[$key]) )
                    $parse_url[$key] = $parts[$key];
            }
        } else {
            // Join the original URL path with the new path
            if ( isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH) ) {
                if ( isset($parse_url['path']) )
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                else
                    $parse_url['path'] = $parts['path'];
            }

            // Join the original query string with the new query string
            if ( isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY) ) {
                if ( isset($parse_url['query']) )
                    $parse_url['query'] .= '&' . $parts['query'];
                else
                    $parse_url['query'] = $parts['query'];
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key) {
            if ( $flags & (int) constant('HTTP_URL_STRIP_' . strtoupper($key)) )
                unset($parse_url[$key]);
        }


        $new_url = $parse_url;

        return
            ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
            . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
            . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
            . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
            . ((isset($parse_url['path'])) ? $parse_url['path'] : '')
            . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
            . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '');
    }
}

if ( !function_exists('colorize') ) {
    function colorize($text, $status = "blue")
    {
        $out = "";
        switch ($status) {
            case "green":
            case "SUCCESS":
                $out = "[42m"; //Green background
                break;
            case "red":
            case "FAILURE":
                $out = "[41m"; //Red background
                break;
            case "yellow":
            case "WARNING":
                $out = "[43m"; //Yellow background
                break;
            case "blue":
            case "NOTE":
                $out = "[44m"; //Blue background
                break;
            default:
                throw new Exception("Invalid status: " . $status);
        }
        return chr(27) . "$out" . "$text" . chr(27) . "[0m";
    }
}

if ( !function_exists('prettyXML') ) {
    function prettyXML($xml)
    {
        if ( !$xml || !class_exists('DomDocument') ) {
            return $xml;
        }

        $doc = new \DomDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xml);
        return $doc->saveXML();
    }
}

if ( !function_exists('autoload_psr4') ) {
    /**
     * PSR-4 autoloader ;)
     *
     * @param $className
     */
    function autoload_psr4($className)
    {
        $namespace = $fileName = '';
        $extensions = [".php", ".class.php", ".inc"];

        // Sets the include path as the "src" directory
        $includePath = dirname(__FILE__);

        if ( false !== ($lastNsPos = strripos($className, '\\')) ) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className)/* . '.php'*/
        ;
        $fullFileName = $includePath . DIRECTORY_SEPARATOR . $fileName;

        foreach ($extensions as $ext) {
            //dd($fullFileName . $ext);
            if ( file_exists($fullFileName . $ext) ) {
                require_once($fullFileName . $ext);
            }
        }
    }
}

if ( !function_exists('autoload_psr0') ) {
    /**
     * PSR-0 autoloader ;)
     *
     * @param $className
     */
    function autoload_psr0($className)
    {
        $extensions = [".php", ".class.php", ".inc"];
        $thisClass = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        $baseDir = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        if ( substr($baseDir, -strlen($thisClass)) === $thisClass ) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        }
        $className = ltrim($className, '\\');
        $fileName = $baseDir;
        $namespace = '';
        if ( $lastNsPos = strripos($className, '\\') ) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className);
        foreach ($extensions as $ext) {
            //dd("FIle: " . $fileName . $ext);
            if ( file_exists($fileName . $ext) ) {
                require_once($fileName . $ext);
            }
        }
    }
}

if ( !function_exists('registerAutoloader') ) {
    /**
     * Register PSR-X autoloader
     */
    function registerAutoloader($n = 'psr4')
    {
        //dd("registerAutoloader ...\n");
        spl_autoload_register('autoload_' . $n);
    }
}

if ( !function_exists('dd') ) {
    function dd($e, $q = false)
    {
        $break = php_sapi_name() == 'cli'
            ? "\n"
            : "<br>\n";
        if ( is_string($e) ) {
            echo $e . $break;
            if ( $q ) exit();
            return;
        }
        if ( php_sapi_name() == 'cli' ) {
            print_r($e);
        } else {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        }
        if ( $q ) exit();
    }
}

if ( !function_exists('printfa') ) {
    function printfa($format, $arr)
    {
        return call_user_func_array('printf', array_merge((array) $format, $arr));
    }
}

if ( !function_exists('camel_case') ) {
    /**
     * Convert a value to camel case.
     *
     * @param  string $value
     * @return string
     */
    function camel_case($value)
    {
        $camelCache = [];
        if ( isset($camelCache[$value]) ) {
            return $camelCache[$value];
        }
        return $camelCache[$value] = lcfirst(studly($value));
    }
}

if ( !function_exists('studly') ) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @return string
     */
    function studly($value)
    {
        $studlyCache = [];
        $key = $value;
        if ( isset($studlyCache[$key]) ) {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

if ( !function_exists('snake_case') ) {
    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        $snakeCache = [];
        $key = $value . $delimiter;
        if ( isset($snakeCache[$key]) ) {
            return $snakeCache[$key];
        }
        if ( !ctype_lower($value) ) {
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $delimiter, $value));
        }
        return $snakeCache[$key] = $value;
    }
}

if ( !function_exists('starts_with') ) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ( $needle != '' && strpos($haystack, $needle) === 0 ) return true;
        }

        return false;
    }
}

if ( !function_exists('contains') ) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ( $needle != '' && strpos($haystack, $needle) !== false ) return true;
        }

        return false;
    }
}

if ( !function_exists('ends_with') ) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ( (string) $needle === substr($haystack, -strlen($needle)) ) return true;
        }

        return false;
    }
}

if ( !function_exists('finish') ) {
    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string $value
     * @param  string $cap
     * @return string
     */
    function finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:' . $quoted . ')+$/', '', $value) . $cap;
    }
}

if ( !function_exists('is') ) {
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string $pattern
     * @param  string $value
     * @return bool
     */
    function is($pattern, $value)
    {
        if ( $pattern == $value ) return true;

        $pattern = preg_quote($pattern, '#');

        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern) . '\z';

        return (bool) preg_match('#^' . $pattern . '#', $value);
    }
}