<?php

namespace pxgamer\LogBrowserChecker;

use BrowscapPHP\Browscap;

class WebServer
{
    const ERROR_NOT_TOTAL_PERCENTAGE = 'Possible error, less than 100% total';

    protected $aIPs = ['127.0.0.1'];
    protected $rootDir;
    protected $siteName;
    protected $siteDir;
    protected $aUserAgents;
    protected $aBrowsers;
    protected $aSessions;
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
    protected $iFiles;

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

        $this->oBrowscap = new Browscap();
    }

    public function findFiles()
    {
        $this->oFileIterator = new \DirectoryIterator($this->siteDir);

        foreach ($this->oFileIterator as $file) {
            if ($file->getExtension() == 'log') {
                $this->statsRunner($file);
                $this->iFiles = $this->iFiles++;
            }
        }

        return $this;
    }

    public function getBrowserStats()
    {
        return $this->aBrowsers;
    }

    public function getSessionsIds()
    {
        return $this->aSessions;
    }

    public function getUserAgents()
    {
        return $this->aUserAgents;
    }

    public function execute()
    {

    }

    protected function statsRunner(\SplFileInfo $fileInfo)
    {

    }

    protected function getSessionFromCookieString($sCookie)
    {
        $sCookie = current(explode(';', $sCookie));
        return current(explode(',', $sCookie));
    }
}
