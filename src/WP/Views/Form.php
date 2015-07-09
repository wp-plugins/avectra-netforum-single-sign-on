<?php namespace WP\Views;

abstract class Form
{
    protected $wp;

    /**
     * @return bool
     */
    protected function store()
    {
        $post = $_POST[$this->page];
        array_walk_recursive($post,
            [$this, 'sanitize']
        );

        return add_option($this->page, $post)
        || update_option($this->page, $post);
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        if ( !isset($_POST[$this->page]) ) {
            return false;
        }

        $post = $_POST[$this->page];
        $this->wp = new \WP_Error();

        foreach ($this->fields as $key => $section) {
            $key = $this->toSlug($key);  // incase its not.
            foreach ($section['fields'] as $field => $option) {
                $field = $this->toSlug($field); // incase its not.
                /*dd('type: ' . gettype($post[$key][$field]));
                dd('key[field]: ' . $key . '['.$field.']');
                dd('val: ' . $post[$key][$field]);*/
                if ( isset($option['required']) && $option['required'] ) {
                    // if field is !null but only empty.
                    if ( !is_null($post[$key][$field]) && trim($post[$key][$field]) == "" ) {
                        $this->wp->add($field, __($option['title'] . ' cannot be left empty.'));
                        continue;
                    }
                }
                if ( isset($option['validate']) && !is_null($option['validate']) ) {
                    if ( !is_array($option['validate']) )
                        continue;

                    if ( !preg_match('/^' . current($option['validate']) . '$/is', $post[$key][$field]) ) {

                        $msg = (end($option['validate']) != '')
                            ? end($option['validate'])
                            : 'must be valid characters.';

                        $this->wp->add($field, __($option['title'] . ' ' . $msg));
                        continue;
                    }
                }
            }
        }

        return sizeof($this->wp->get_error_codes()) <= 0;
    }

    /**
     * @param bool $error
     * @param null $msg
     * @return int
     */
    protected function flash($error = false, $msg = null)
    {
        if ( $error ) {
            return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>',
                $error ? 'error' : 'updated',
                $error ? __('Uh oh!') : __('Yay!'),
                __(implode('<br>', $this->wp->get_error_messages()))
            );
        }

        return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>',
            'updated',
            'Yay!',
            is_null($msg)
                ? __('Your request has been processed successfully.')
                : __($msg)
        );
    }

    /**
     * @param $e
     * @return string
     */
    protected function sanitize(&$e)
    {
        return $e = trim(stripslashes(sanitize_text_field($e)));
    }

}