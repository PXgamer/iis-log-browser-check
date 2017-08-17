<?php

namespace pxgamer\LogBrowserChecker;

use BrowscapPHP\Browscap;

/**
 * Class WebServer
 * @package pxgamer\LogBrowserChecker
 */
class WebServer
{
    const ERROR_NOT_TOTAL_PERCENTAGE = 'Possible error, less than 100% total';

    /**
     * @var array|null
     */
    protected $aIPs = ['127.0.0.1'];
    /**
     * @var null|string
     */
    protected $rootDir;
    /**
     * @var null|string
     */
    protected $siteName;
    /**
     * @var string
     */
    protected $siteDir;
    /**
     * @var array
     */
    protected $aUserAgents;
    /**
     * @var array
     */
    protected $aBrowsers;
    /**
     * @var array
     */
    protected $aSessions;
    /**
     * @var int
     */
    protected $iTotalPercent;
    /**
     * @var Config
     */
    protected $oConfig;
    /**
     * @var Browscap
     */
    protected $oBrowscap;
    /**
     * @var \DirectoryIterator
     */
    protected $oFileIterator;

    /**
     * WebServer constructor.
     * @param Config $oConfig
     */
    public function __construct(Config $oConfig)
    {
        $this->oConfig = $oConfig;

        $this->rootDir = $this->oConfig->getValue('root_dir') ? $this->oConfig->getValue('root_dir') : getcwd();

        $this->siteName = $this->oConfig->getValue('site_name') ? $this->oConfig->getValue('site_name') : '';

        $this->siteDir = $this->rootDir . DIRECTORY_SEPARATOR . $this->siteName;
        $this->aUserAgents = [];
        $this->aIPs = $this->oConfig->getValue('ignored_ips') ? $this->oConfig->getValue('ignored_ips') : $this->aIPs;

        $this->aBrowsers = [];
        $this->aSessions = [];
        $this->iTotalPercent = 0;

        $this->oBrowscap = new Browscap();
    }

    /**
     * Look for files to execute on
     *
     * @return $this
     */
    public function findFiles()
    {
        $this->oFileIterator = new \DirectoryIterator($this->siteDir);

        foreach ($this->oFileIterator as $file) {
            if ($file->getExtension() == 'log') {
                $this->statsRunner($file);
            }
        }

        return $this;
    }

    /**
     * Return an array of browsers and their count in format [browser => int]
     *
     * @return array
     */
    public function getBrowserStats()
    {
        return $this->aBrowsers;
    }

    /**
     * Return an array of unique session IDs
     *
     * @return array
     */
    public function getSessionsIds()
    {
        $this->aSessions = array_unique($this->aSessions);
        return $this->aSessions;
    }

    /**
     * Return an array of unique user agent strings
     *
     * @return array
     */
    public function getUserAgents()
    {
        $this->aUserAgents = array_unique($this->aUserAgents);
        return $this->aUserAgents;
    }

    /**
     * Empty extendable function
     */
    public function execute()
    {

    }

    /**
     * Empty extendable function, should be provided an instance of \SplFileInfo by the findFiles() function
     *
     * @param \SplFileInfo $fileInfo
     */
    protected function statsRunner(\SplFileInfo $fileInfo)
    {

    }

    /**
     * Strip a session id from a cookie string
     *
     * @param $sCookie
     * @return mixed
     */
    protected function getSessionFromCookieString($sCookie)
    {
        $sCookie = current(explode(';', $sCookie));
        return current(explode(',', $sCookie));
    }
}
