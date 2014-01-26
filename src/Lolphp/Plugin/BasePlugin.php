<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Plugin;

use Lolphp\Configuration;

/**
 * Class BasePlugin
 * @package Lolphp\Plugin
 */
class BasePlugin
{
    /**
     * @var Configuration $configuration
     */
    protected $configuration;

    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }
}