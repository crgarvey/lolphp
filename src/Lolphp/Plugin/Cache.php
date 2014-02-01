<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Plugin;

/**
 * Class Cache
 * @package Lolphp\Plugin
 */
class Cache extends BasePlugin
{
    /**
     * @param $key
     * @return string
     */
    public function hash($key)
    {
        return hash('crc32', $key);
    }

    /**
     * Searches the cache directory.
     *
     * @param       mixed       $wildcard
     * @return      null|string
     */
    public function getCacheKeyWildcard($wildcard)
    {
        $cacheDir      = $this->configuration->getCache()->getOptions()['cacheDir'];

        // Wildcard param as an array (implode).
        if (is_array($wildcard)) {
            $wildcard           = implode('.', $wildcard);
        }

        foreach (glob($cacheDir . '*') as $filename) {
            if (strpos($filename, $wildcard) !== false) {
                return basename($filename);
            }
        }
        return null;
    }

    /**
     * @param $wildcard
     * @return array|null
     */
    public function getCacheKeys($wildcard = null)
    {
        $cacheDir      = $this->configuration->getCache()->getOptions()['cacheDir'];
        $cacheKeys     = [];
        foreach (glob($cacheDir . '*') as $filename) {
            if (strpos($filename, $wildcard) !== false) {
                $cacheKeys[] = basename($filename);
            }
        }

        if (count($cacheKeys)) {
            return $cacheKeys;
        }

        return null;
    }
}