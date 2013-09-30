<?php

if (isset($_POST['session_reload']) and $_POST['session_reload']) {
    delete_session();
    header("Location: " . $_self);
}

if (!isset($_SESSION['servers'])) {
    require('inc/xmlrpc/xmlrpc.inc');

    $satellite_login = 'scriptuser';
    $satellite_password = '_sCripts4SAT!';
    $satellite_url = 'http://spch1355.zrh.local/rpc/api';

    $client = new xmlrpc_client($satellite_url);
    $client->return_type = 'phpvals';

    // log in to satellite
    $message = new xmlrpcmsg("auth.login", array(new xmlrpcval($satellite_login, "string"), new xmlrpcval($satellite_password, "string")));
    $resp = $client->send($message);
    $key = $resp->value();

    // get system list
    $message = new xmlrpcmsg("system.listSystems", array(new xmlrpcval($key, "string")));
    $resp = $client->send($message);

    foreach ($resp->value() as $r) {
        $sysid = $r['id'];
        $sysname = $r['name'];

        // get DMI info for harware type
        $message = new xmlrpcmsg("system.getDmi", array(new xmlrpcval($key, "string"), new xmlrpcval($sysid, "int")));
        $resp = $client->send($message);
        
        $rr = $resp->value();
        // remove 'ProLiant' from hardware string
        $syshw = str_replace("ProLiant", "", $rr['product']);

        // filter out VMs and fill $_SESSION
        if (!preg_match('/VMware/',$rr['product'])) {
            $_SESSION['servers'][$sysname] = $syshw;
        }
    }
}

// load backups into array
$backups = array_diff(scandir("/data/mondo/"), array('..', '.'));

?>

  <div class="row">
    <div class="span7">
      <table class="table table-bordered table-condensed table-striped">
        <thead>
          <tr>
            <th>Hostname</th>
            <th>Hardware</th>
            <th>Last Backup</th>
            <th>Size</th>
            <th>#</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>

<?php

// table with all servers
foreach ($_SESSION['servers'] as $name => $hw) {
    // use short hostname for links
    $sname = str_replace(".zrh.local", "", $name);
    
    echo "<tr><td><a href=\"/detail/" . $sname . "\">" . $name . "</a></td><td>". $hw . "</td>\n";

    // search $backups for current host
    $filter = preg_grep("/^" . $sname . "/", $backups);
    
    echo "<td>";
    // check for backups
    if (empty($filter)) {
        echo "N/A";
    } else {
        // sort array by backup date and only show latest
        // see functions.php for 'cmp'
        usort($filter, "cmp");
        $exploded = explode("-", $filter['0']);
        // date format is DD.MM.YYYY HHMM
        $last_backup_date = $exploded['1'] . "." . $exploded['2'] . "." . $exploded['3'] . " " . $exploded['4'];
        echo $last_backup_date;
    }
    echo "</td>\n";

    // size of last backup
    echo "<td>";
    if (empty($filter)) {
        echo "N/A";
    } else {
        echo format_bytes(dirsize("/data/mondo/" . $filter['0'])); 
        //echo "/data/mondo/" . $filter['0'];
    }
    echo "</td>\n";

    // number of backups
    echo "<td>" . count($filter) . "</td>\n";

    // check backup and it's age
    echo "<td><span class=\"label ";
    if (empty($filter)) {
        echo "label-important\"><i class=\"icon-ban-circle icon-white\"></i> Missing";
    } else {
        if (strtotime($last_backup_date) > strtotime("-7 days")) {
            echo "label-success\"><i class=\"icon-ok icon-white\"></i> Current";
        } else {
            echo "label-warning\"><i class=\"icon-warning-sign icon-white\"></i> Old";
        }
    }
    echo "</span></td></tr>\n";
}



?>
        </tbody>
      </table>
    </div><!-- /span6 -->
    <div class="span3 offset2"><!-- span -->
      <p>Reload Server List from Satellite API</p>
      <form method="post" action="<?php echo $_self; ?>">
        <fieldset>
          <button type="submit" class="btn btn-primary" name="session_reload" value="true">Go</button>
        </fieldset>
      </form>
    </div><!-- /span3 -->
  </div><!-- row -->