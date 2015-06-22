<?php namespace WP\Views;

class Input
{
    /**
     * @param $args
     */
    static public function textfield($args)
    {
        return self::field($args, $type = 'text');
    }

    /**
     * @param $args
     */
    static public function passwordfield($args)
    {
        return self::field($args, $type = 'password');
    }

    /**
     * @param $args
     */
    static public function checkbox($args)
    {
        return self::field($args, $type = 'checkbox', null);
    }

    /**
     * @param $args
     */
    static public function textarea($args)
    {
        return self::field($args, $type = 'textarea', null);
    }

    /**
     * @param        $args
     * @param string $type
     * @param string $class
     */
    static protected function field($args, $type = 'text', $class = 'regular-text')
    {
        $id = $args['id'];
        $group = $args['group'];
        $section = $args['section'];

        // set field name based on section.
        $fieldName = "{$group}[$section][$id]";

        // make default value.
        $value = self::makeDefaultValue($args);

        // add checkbox checked value
        $extra = '';
        if ( $type == 'checkbox' ) {
            $extra .= checked(1, (bool) $value, false);
            if ( !checked(1, (bool) $value, false) ) {
                $value = checked(1, $value, false) ? 0 : 1;
            }
        }

        // embed JS =)
        if ( !is_null($args['js']) ) {
            self::addJS($args['js'], $fieldName, $value, $args);
        }

        // params
        $params = [
            'class' => $class,
            'type'  => $type,
            'name'  => $fieldName,
            'value' => esc_attr($value),
            'extra' => $extra,
        ];

        // print field
        switch ($type) {
            case 'textarea':
                self::itemTextArea($params);
                break;
            default:
                self::itemTextField($params);
        }

        // add description for input field.
        self::addDescription($args['desc']);
    }

    /**
     * @param array $params
     */
    static private function itemTextArea(array $params)
    {
        printf("<textarea cols=30 rows=4 class='%s' type='%s' name='%s' %s>%s</textarea>",
            $params['class'],
            $params['type'],
            $params['name'],
            $params['extra'],
            $params['value']
        );
    }

    /**
     * @param array $params
     */
    static private function itemTextField(array $params)
    {
        printf("<input class='%s' type='%s' name='%s' value='%s' %s />",
            $params['class'],
            $params['type'],
            $params['name'],
            $params['value'],
            $params['extra']
        );
    }

    /**
     * @param $desc
     */
    static private function addDescription($desc)
    {
        if ( is_array($desc) ) {
            printf('<small>&nbsp; %s</small><br><small>%s</small>',
                array_shift($desc),
                esc_attr(array_pop($desc))
            );
        } else {
            printf("<br><small>%s</small>", esc_attr($desc));
        }
    }

    /**
     * @param array $args
     * @return array|mixed|null
     */
    static private function makeDefaultValue(array $args)
    {
        $default = $args['default'];
        $filter = $args['filter'];

        // get value from option.
        $return = self::getOptionValue($args);

        // set default value.
        // if its an array use callback function.
        if ( is_null($return) && is_array($default) ) {
            $default = self::handleCallback($default);
        }

        // filter value based on filter callback.
        if ( is_array($filter) ) {
            $return = self::handleCallback($filter, [
                is_null($return)
                    ? $default
                    : $return
            ]);
        }

        return is_null($return)
            ? $default
            : $return;
    }

    /**
     * @param       $callback
     * @param array $return
     * @return array|mixed
     */
    static public function handleCallback($callback, array $return = [])
    {
        if ( !is_array($callback) )
            return $return;

        $call = array_shift($callback);
        $params = is_array(end($callback))
            ? is_null($return)
                ? end($callback)
                : array_merge(end($callback), $return)
            : $return;

        return call_user_func_array(
            $call, $params
        );
    }

    /**
     * @param array $args
     * @return null
     */
    static private function getOptionValue(array $args)
    {
        $id = $args['id'];
        $group = $args['group'];
        $section = $args['section'];

        // if on post, display last value ;)
        if ( isset($_REQUEST[$group][$section][$id]) ) {
            return $_REQUEST[$group][$section][$id];
        }

        // set predefined value
        $opt = get_option($group);
        return isset($opt[$id]) && !empty($opt[$id])
            ? $opt[$id]
            : isset($opt[$section][$id]) && !empty($opt[$section][$id])
                ? $opt[$section][$id]
                : null;
    }

    /**
     * @param        $js
     * @param string $key
     * @param string $value
     * @param string $args
     */
    static public function addJS($js, $key = '', $value = '', $args = '')
    {
        $group = $section = null;
        is_array($args) ? extract($args) : null;

        $getInput = function ($key, $delim = '') {
            return sprintf("$('input[name%s=\"%s\"]')", $delim, $key);
        };

        printf("<script>jQuery(document).ready(function($) { %s });</script>",
            preg_replace(
                ['/%field:(.*?)%/i', '/%field/', '/%value/'], [
                $getInput('[$1]', '*'),
                $getInput($key),
                esc_attr($value),
            ], self::compress($js))
        );
    }

    /**
     * @param $buffer
     * @return mixed
     */
    static protected function compress($buffer)
    {
        // remove comments
        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        // remove tabs, spaces, newlines, etc.
        $buffer = str_replace(["\r\n", "\r", "\t", "\n", '  ', '    ', '     '], '', $buffer);
        // remove other spaces before/after )
        $buffer = preg_replace(['(( )+\))', '(\)( )+)'], ')', $buffer);

        return $buffer;
    }
}