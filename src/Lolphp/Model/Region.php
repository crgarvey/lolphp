<?php
/**
 * Created for Lolphp on 2/1/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Model;

class Region extends ModelBase
{
    /**
     * Constants
     */
    const REGION_NA     = 'na';
    const REGION_EUW    = 'euw';
    const REGION_EUNE   = 'eune';
    const REGION_BR     = 'br';
    const REGION_TR     = 'tr';
    const REGION_RU     = 'ru';
    const REGION_LAN    = 'lan';
    const REGION_LAS    = 'las';
    const REGION_OCE    = 'oce';

    protected $regionList          = [
        self::REGION_NA         => 'North America',
        self::REGION_EUW        => 'Europe West',
        self::REGION_EUNE       => 'Europe Nordic & East',
        self::REGION_BR         => 'Brazil',
        self::REGION_TR         => 'Turkey',
        self::REGION_RU         => 'Russia',
        self::REGION_LAN        => 'Latin America North',
        self::REGION_LAS        => 'Latin America South'
    ];

    /**
     * Checks whether or not the region, by it's key, exists.
     *
     * @param       mixed       $key
     * @return      boolean
     */
    public function isValid($key)
    {
        return (bool) array_key_exists($key, $this->regionList);
    }

    /**
     * Returns region list as pairs.
     * 
     * @return      array
     */
    public function getPairs()
    {
        return $this->regionList;
    }

    /**
     * Validates the region.
     *
     * @param       string      $region
     * @param       string      $defaultRegion
     * @return      string
     */
    public function validateRegion($region, $defaultRegion = self::REGION_NA)
    {
        if ($this->isValid($region) === false) {
            $region = $defaultRegion;
        }

        return $region;
    }
}
