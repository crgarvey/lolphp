<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Plugin;

class DateTime
{
    /**
     * @param $milliseconds
     * @return \DateTime
     */
    public function convertMsDateTime($milliseconds)
    {
        $timestamp                          = (int) substr($milliseconds, 0, -3); // To avoid the INT overflow limit.
        $datetime                           = new \DateTime("@$timestamp");
        return $datetime;
    }
}