<?php

if (isset($_REQUEST['site']) && $_REQUEST['site'] !== '')
{
	$site = $_REQUEST['site'];
	?>
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
				<h1><?=strtoupper($site)?> Reports</h1>
				<ul>
					<?php
					$files = scandir("stats/$site/");
					foreach ($files as $file) {
						if (is_file("stats/$site/$file"))
						{
							echo "<li><a href='stats/$site/$file' target='_blank'>$file</a></li>";
						}
					}
					?>
				</ul>
	<?php
}
else
{
	header('Location: /infoCheck/');
}