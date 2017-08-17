<?php

namespace pxgamer\LogBrowserChecker;

/**
 * Class IIS
 * @package pxgamer\LogBrowserChecker
 */
class IIS extends WebServer
{
    /**
     * IIS constructor.
     * @param Config $oConfig
     */
    public function __construct(Config $oConfig)
    {
        Parent::__construct($oConfig);
        $this->iTotalPercent = 0;

        return $this;
    }

    /**
     * @return $this
     * @throws \ErrorException
     */
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
            $this->iTotalPercent = $this->iTotalPercent + (($b / $totalBrowsers) * 100);
        }

        if ($this->iTotalPercent < 98) {
            throw new \ErrorException(self::ERROR_NOT_TOTAL_PERCENTAGE);
        }

        return $this;
    }

    /**
     * @param \SplFileInfo $fileInfo
     */
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

                    if (!in_array($sSessionId,
                            $this->aSessions) && !in_array($m_aSplitIds[$this->oConfig->getValue('ip_column')],
                            $this->aIPs)) {
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
