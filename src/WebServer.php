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
    protected $aTotalStats;
    protected $aBrowsers;
    protected $aSessions;
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
        $this->rootDir = $oConfig->getValue('root_dir') ? $oConfig->getValue('root_dir') : getcwd();

        $this->site = $oConfig->getValue('site_name') ? $oConfig->getValue('site_name') : '';

        $this->siteDir = $this->rootDir . $this->site . DIRECTORY_SEPARATOR;
        $this->aTotalStats = [];
        $this->aIPs = $oConfig->getValue('ignored_ips') ? $oConfig->getValue('ignored_ips') : $this->aIPs;

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
