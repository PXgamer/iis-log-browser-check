<?php

namespace pxgamer\LogBrowserChecker;

/**
 * Class IIS
 * @package pxgamer\LogBrowserChecker
 */
class Config
{
    /**
     * @var array
     */
    private $aConfigValues;

    /**
     * IIS constructor.
     * @param array $aConfigValues
     */
    public function __construct($aConfigValues= [])
    {
        $this->aConfigValues= $aConfigValues;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        return isset($this->aConfigValues[$key]) ? $this->aConfigValues[$key] : null;
    }
}