
<?php

/***************
// debug output
echo "<hr><br>\n";

echo "<pre>\n";
echo "\$_params: ";
print_r ($_params);
echo "</pre><br>\n";

echo "<pre>\n";
echo "\$_POST: ";
print_r ($_POST);
echo "</pre><br>\n";

echo "<pre>\n";
echo "\$_GET: ";
print_r ($_GET);
echo "</pre><br>\n";

echo "<pre>\n";
echo "\$_SESSION: ";
print_r ($_SESSION);
echo "</pre><br>\n";

echo "<pre>\n";
echo "\$_SERVER: ";
print_r ($_SERVER);
echo "</pre><br>\n";
*****************/

?>

          <hr>
          <footer>
            <p>
              &copy; Flughafen Z&uuml;rich AG 2013 - SIIR
<?php

$script_end = microtime(true);
$script_time = ($script_end - $script_start);
$script_time = round($script_time, 3);
//echo "<br><b>Script time: " . $script_time . "s</b>\n";

?>
            </p>
          </footer>

    </div>
    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/bootstrap.js"></script>
    
  </body>
</html>
