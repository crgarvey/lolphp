<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

/**
 * Class View
 */
class View
{
    protected $filename;
    protected $data;

    /**
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public function escape($str)
    {
        return htmlspecialchars($str); //for example
    }

    /**
     * @param $name
     * @return bool
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return false;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param bool $print
     * @return string
     */
    public function render($print = true)
    {
        ob_start();
        include($this->filename);
        $rendered = ob_get_clean();
        if ($print) {
            echo $rendered;

            return '';
        }

        return $rendered;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
