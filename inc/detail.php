<div class="row">
  <div class="span8">

<?php

/*
**
** ToDo:
**  - check for $host in $_SESSION['servers'] !
**  - session expire
*/

$host = @$_params['2'];

if (isset($host)) {
	// host provided, show backup folders and logs
	$backups = array_diff(scandir("/data/mondo/"), array('..', '.'));
	$filter = preg_grep("/^" . $host . "/", $backups);

	// see if we actually have backups for this host

	if (empty($filter)) {
		echo "<div class=\"alert alert-notice\"><b>Oh!</b> No backups found :(</div>\n";
	} else {
		$i = 1;
		foreach ($filter as $backup_dir) {
			$files = array_diff(scandir("/data/mondo/" . $backup_dir), array('..', '.'));
			echo "<div class=\"tabbable\">\n";
			echo "  <ul class=\"nav nav-tabs\">\n";
			echo "    <li class=\"active\"><a href=\"#list". $i . "\" data-toggle=\"tab\">Files</a></li>\n";
			echo "    <li><a href=\"#mlog". $i . "\" data-toggle=\"tab\">Mondo Log</a></li>\n";
			echo "    <li><a href=\"#clog". $i . "\" data-toggle=\"tab\">Cronjob Log</a></li>\n";
			echo "  </ul>\n";

			echo "  <div class=\"tab-content\">\n";
			echo "    <div class=\"tab-pane active\" id=\"list". $i . "\">\n";
			$output = array();
			exec("ls -alh /data/mondo/" . $backup_dir, $output);
			echo "      <pre>\n";
			// remove . .. and 'total' from ls output
			unset($output['0']);
			unset($output['1']);
			unset($output['2']);
			
			foreach ($output as $line) {
				echo $line . "\n";
			}

			echo "      </pre>\n";
			echo "    </div>\n";

			$mlog = "/data/mondo/" . $backup_dir . "/mondoarchive.log";
			echo "    <div class=\"tab-pane\" id=\"mlog". $i . "\">\n";
	        read_logfile($mlog);
	        echo "    </div>\n";

	        $clog = "/data/mondo/" . $backup_dir . "/" . $host . "-mondo.log";
			echo "    <div class=\"tab-pane\" id=\"clog". $i . "\">\n";
	        read_logfile($clog);
	        echo "    </div>\n";
	        echo "  </div><!-- /nav nav-tabs-->\n";
	        echo "</div><!-- /tabbable -->\n";
	        $i++;
		}
	}
}

?>

  </div><!-- /span8 -->
  <div class="span3 offset1">

<?php

	// display host select form
if (!isset($host)) {
	echo "<p>Please select a host</p>\n";
} else {
	echo "<p>Details for <b>" . $host . "</b></p>\n";
}
echo "<form action=\"" . $_self . "\" method=\"post\">\n";
echo "  <fieldset>\n";
echo "    <select>\n";
if (!isset($host)) { echo "      <option>-----</option>\n"; }
foreach ($_SESSION['servers'] as $key => $value) {
	$sname = str_replace(".zrh.local", "", $key);
	$name = $key;

	echo "<option ";
	if ($host == $sname) { echo "selected=\"selected\" "; }
	echo "onClick=\"window.location = '/detail/" . $sname . "'\" >". $name . "</option>\n";
}  
echo "    </select>\n";
echo "  </fieldset>\n";
echo "</form>\n";

?>
  </div><!-- /span4 -->
</div><!-- /row1 -->
