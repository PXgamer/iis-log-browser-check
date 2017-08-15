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
    /**
     * @var Browscap
     */
    protected $oBrowscap;
    /**
     * @var \DirectoryIterator
     */
    protected $oFileIterator;
    protected $iFiles;

    public function __construct($rootDir = null, $options = [])
    {
        if ($rootDir) {
            $this->rootDir = $rootDir;
        } else {
            $this->rootDir = getcwd();
        }

        $this->site = isset($options['site_name']) ? $options['site_name'] : '';

        $this->siteDir = $this->rootDir . $this->site . DIRECTORY_SEPARATOR;
        $this->aTotalStats = [];
        $this->aIPs = isset($options['ignored_ips']) ? $options : $this->aIPs;

        $this->aBrowsers = [];

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

    public function execute()
    {

    }

    protected function statsRunner(\SplFileInfo $fileInfo)
    {

    }
}
