<?php

require('inc/functions.php');
if (!isset($_SESSION)){ session_start(); }

// determine if the user has access
// user info provided through an NTLM capable browser
// if no NTLM user is provided, throw an error 

$_users = array(
                   "u100839" => "Christoph Richle",
                   "u105560" => "Sandro Roth",
                   "u106118" => "Ivan Torretti",
                   "u103601" => "Martin Schudel",
                   "x052624" => "Lukasz Matwiejczuk",
               );


@$_user = $_SERVER['REMOTE_USER'];

$ntlm_error = false;
$access = false;

if (isset($_user)) {
    if (array_key_exists($_user, $_users)) {
        $access = true;
    } else {
        $access = false;
    }    
} else {
    $ntlm_error = true;
    $access = false;
}

if ($access) {

    // access is granted, include stuff

    $_params = array_filter(explode("/", $_SERVER['REQUEST_URI']));
    $_url = @$_params['1'];

    require('inc/header.php');
    require('inc/nav.php');

    // 'case' order matters!
    switch ($_url) {
        case "detail":
            include('inc/detail.php');
            break;
        default:
            include('inc/mondo.php');
    }

    require('inc/footer.php');
} else {

    // access is not granted, display small error page

    require('inc/header.php');
?>
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .access-box {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .access-box .access-box-heading,
      .access-box .checkbox {
        margin-bottom: 10px;
      }
      .access-box input[type="text"],
      .access-box input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    </head>
    <body>
      <div class="container">
<?php
    echo "<div class=\"access-box\">\n";
    echo "  <h2 class=\"access-box-heading\">Unauthorized!</h2>\n";
    if ($ntlm_error) {
        echo "  <div class=\"alert alert-info\">Your Browser does not support NTLM!</div>";
    } else {
        echo "  <div class=\"alert alert-info\">You don't have access to this site!</div>";
    }
    echo "<p>Please contact <a href=\"mailto:datacenter@zurich-airport.com?Subject=Mondo%20Web\" target=\"_top\">SIIR</a>\n";
    echo "</div>\n";
    //echo "<pre>";
    //echo $user . "\n";
    //echo $ntlm_error . "\n";
    //var_dump($granted_users);
    //echo "</pre>";

    echo "</div></body></html>";
}

?>
