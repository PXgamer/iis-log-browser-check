<?php

namespace pxgamer\IISLogBrowserChecker;

class InfoCheck
{
    const ERROR_NOT_TOTAL_PERCENTAGE = 'Possible error, less than 100% total';

    public $dir;
    public $aIPs;
    public $aSplitIds;
    public $browsers;
    public $aTotalStats;
    public $browserVersions;
    public $aFiles;
    public $sSessions;
    public $totalPercent;
    public $browsersN;
    public $site;
    public $inp;

    public function __construct($args, $site)
    {
        $this->site = isset($site) ? $site : '';
        $this->dir = "./logs/" . $site . "/";
        $this->aIPs = array('127.0.0.1');
        $this->aSplitIds = array();
        $this->aTotalStats = array();
        $this->browsers = array();
        $this->browserVersions = array();
        $this->sSessions = array();
        $this->aFiles = 0;
        $this->totalPercent = 0;
        $this->browsersN = "";

        if ($args !== '') {
            $this->inp = str_replace("+", " ", $args[1]);
        }

        $this->findFiles();

        return $this;
    }

    public function execute()
    {
        $count_ff = array();

        foreach ($this->aTotalStats as $cur) {
            $inp = str_replace("+", " ", $cur);
            $browser = get_browser($inp, true);
            $this->browsers[] = $browser['parent'];
            $this->browserVersions[] = $browser['version'];
        }

        $this->browsersN = array_unique($this->browsers);
        foreach ($this->browsersN as $i) {
            $count_ff[$i] = $this->findDuplicates($this->browsers, $i);
        }

        $this->browsers = array_unique($this->browsers);

        $total = array_sum($count_ff);

        foreach ($this->browsers as $b) {
            echo "<tr><td>$b</td><td>$count_ff[$b]</td><td>" . round((($count_ff[$b] / $total) * 100),
                    2) . "</td></tr>";
            $this->totalPercent = $this->totalPercent + (($count_ff[$b] / $total) * 100);
        }

        if ($this->totalPercent < 98) {
            throw new \ErrorException(self::ERROR_NOT_TOTAL_PERCENTAGE);
        }

        return $this;
    }

    function findFiles()
    {
        $files = scandir($this->dir);
        foreach ($files as $file) {
            if (strpos($file, '.log')) {
                $currentFile = fopen($this->dir . $file, "r");
                if ($currentFile) {
                    // Read through file
                    while (!feof($currentFile)) {
                        $m_sLine = fgets($currentFile);
                        // Check for site name validity
                        if (preg_match('*PHPSESSID=* ', $m_sLine) == true && substr($m_sLine, 0, 1) !== '#') {
                            $m_aSplitIds = explode(" ", $m_sLine);
                            $sSID = explode("PHPSESSID=", $m_aSplitIds[13]);
                            $sSID = $sSID[1];
                            $sSID = current(explode(';', $sSID));
                            $sSID = current(explode(',', $sSID));
                            if (!in_array($sSID, $this->sSessions) && !in_array($m_aSplitIds[10], $this->aIPs)) {
                                $this->sSessions[] = $sSID;
                                $m_sUserStat = $m_aSplitIds[12];
                                $this->aTotalStats[] = $m_sUserStat;
                            }
                        }
                    }
                }
                fclose($currentFile);
                $this->aFiles = $this->aFiles++;
            }
        }
        return true;
    }

    function findDuplicates($data, $dupval)
    {
        $nb = 0;
        foreach ($data as $key => $val) {
            if (strpos($val, $dupval) !== false) {
                $nb++;
            }
        }
        return $nb;
    }
}