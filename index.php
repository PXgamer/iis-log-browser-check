<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <?php
    if (isset($_GET['site'])) {
        $site = $_GET['site'];
    }

    /**
     * Class InfoCheck
     */
    class InfoCheck
    {
        /**
         * InfoCheck constructor.
         * @param $args
         */
        function __construct($args, $site)
        {
            $this->dir = "./logs/" . $site . "/";
            $this->m_aIPs = array('62.49.204.18', '213.104.185.19');
            $this->m_aSplitIds = array();
            $this->m_aTotalStats = array();
            $this->browsers = array();
            $this->browserVersions = array();
            $this->m_sSessions = array();
            $this->aFiles = 0;
            $this->totalPercent = 0;
            $this->browsersN = "";
            if ($args !== '') {
                $this->inp = str_replace("+", " ", $args[1]);
            }
            $this->findFiles();
            $this->start($site);
        }

        /**
         * @return bool
         */
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
                            // Check for sitename validity
                            if (preg_match('*PHPSESSID=* ', $m_sLine) == true && substr($m_sLine, 0, 1) !== '#') {
                                $m_aSplitIds = explode(" ", $m_sLine);
                                $sSID = explode("PHPSESSID=", $m_aSplitIds[13]);
                                $sSID = $sSID[1];
                                $sSID = current(explode(';', $sSID));
                                $sSID = current(explode(',', $sSID));
                                if (!in_array($sSID, $this->m_sSessions) && !in_array($m_aSplitIds[10], $this->m_aIPs)) {
                                    $this->m_sSessions[] = $sSID;
                                    $m_sUserStat = $m_aSplitIds[12];
                                    $this->m_aTotalStats[] = $m_sUserStat;
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

        /**
         * @return string
         */
        function start($site)
        {
            $count_ff = array();


            echo '<table class="table"><tr><th>Browser Type</th><th>Number of Uses</th><th>Percentage (%)</th></tr>';
            foreach ($this->m_aTotalStats as $cur) {
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
                echo "<tr><td>$b</td><td>$count_ff[$b]</td><td>" . round(($count_ff[$b] / $total) * 100) . "</td></tr>";
                $this->totalPercent = $this->totalPercent + round(($count_ff[$b] / $total) * 100);
            }
            echo "<tr><td><b>Total:</b></td><td>$total</td><td>$this->totalPercent</td></tr>";
            if ($this->totalPercent < 98) {
                echo "<b>Possible error, less than 100% total</b>";
            }

            $this->createTXT($count_ff, $site);

        }

        /**
         * @param $data
         * @param $dupval
         * @return int
         */
        function findDuplicates($data, $dupval)
        {
            $nb = 0;
            foreach ($data as $key => $val)
                if (strpos($val, $dupval) !== false) $nb++;
            return $nb;
        }

        function createTXT($count_ff, $site)
        {
            $m_oCurTime = date('Y-m-d_his', time());
            $dateData = "<a class='btn btn-default' download href='stats/$site/" . $m_oCurTime . ".txt'><span class='glyphicon glyphicon-download'></span> Download " . $site . "/" . $m_oCurTime . "<a/>";
            if (!file_exists("stats/$site/")) {
                mkdir("stats/$site", 0700);
            }
            $m_hEndFile = fopen("stats/$site/" . $m_oCurTime . ".txt", "w") or die("Unable to open file!");
            fwrite($m_hEndFile, "Browser	Uses\n");
            foreach ($this->browsers as $b) {
                fwrite($m_hEndFile, $b . "	" . $count_ff[$b] . "\n");
            }
            fclose($m_hEndFile);
            echo "<br/><br/>" . $dateData . "<br/><br/>";
        }
    }

    if (!isset($argv)) {
        $argv = '';
    }

    if (isset($site) && !empty($site)) {
        echo "<title>$site</title>";
        $infoCheck = new InfoCheck($argv, $site);
    } else {
        echo "
						<style>
						.hover-bottom {
							padding-bottom: 0px;
							border: transparent;
							border-bottom: 0.01em solid transparent;
							background: transparent;
						}
						.hover-bottom:focus {
							outline: none;
							border-bottom: 1px dotted grey;
						}
						</style>
							<div style='margin-top: 300px; text-align: center;'>
							<form>
								<h1>Please enter a sitename</h1>
								<br/>
								<div class='form-group'><pre style='background: none; border: none;'>infoCheck.php<b>?site=<input autocomplete='off' type='text' class='hover-bottom' name='site' placeholder='siteName'></input></b></pre></div><div class='form-group'><input class='btn btn-default' type='submit'></div></form></div>";
        echo "<title>InfoCheck Home</title>";
    }
    ?>
</div>
</body>