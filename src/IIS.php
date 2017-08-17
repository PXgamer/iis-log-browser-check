<?php

namespace pxgamer\LogBrowserChecker;

class IIS extends WebServer
{
    protected $aSplitIds;
    protected $aFiles;
    protected $totalPercent;

    public function __construct(Config $oConfig)
    {
        Parent::__construct($oConfig);
        $this->aFiles = 0;
        $this->totalPercent = 0;

        return $this;
    }

    public function execute()
    {
        foreach ($this->aUserAgents as $cur) {
            $inp = str_replace("+", " ", $cur);
            $browser = $this->oBrowscap->getBrowser($inp);

            if (!isset($this->aBrowsers[$browser->parent])) {
                $this->aBrowsers[$browser->parent] = 1;
            } else {
                $this->aBrowsers[$browser->parent]++;
            }
        }

        $totalBrowsers = array_sum($this->aBrowsers);
        foreach ($this->aBrowsers as $b) {
            $this->totalPercent = $this->totalPercent + (($b / $totalBrowsers) * 100);
        }

        if ($this->totalPercent < 98) {
            throw new \ErrorException(self::ERROR_NOT_TOTAL_PERCENTAGE);
        }

        return $this;
    }

    protected function statsRunner(\SplFileInfo $fileInfo)
    {
        parent::statsRunner($fileInfo);

        $currentFile = fopen($this->siteDir . DIRECTORY_SEPARATOR . $fileInfo, "r");

        if ($currentFile) {
            // Read through file
            while (!feof($currentFile)) {
                $m_sLine = fgets($currentFile);

                // Check for site name validity
                if (preg_match('/PHPSESSID=(.*?) /', $m_sLine, $aCookieSections) && substr($m_sLine, 0, 1) !== '#') {
                    $m_aSplitIds = explode(" ", $m_sLine);

                    $sSessionId = $this->getSessionFromCookieString($aCookieSections[1]);

                    if (!in_array($sSessionId, $this->aSessions) && !in_array($m_aSplitIds[$this->oConfig->getValue('ip_column')], $this->aIPs)) {
                        $this->aSessions[] = $sSessionId;
                        $m_sUserStat = $m_aSplitIds[$this->oConfig->getValue('session_column')];
                        $this->aUserAgents[] = $m_sUserStat;
                    }
                }
            }

            fclose($currentFile);
        }
    }
}
