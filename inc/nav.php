  </head>
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Mondo</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li<?php active_url("detail"); ?>><a href="/detail">Detail</a></li>
            </ul>
          </div><!-- /nav-collapse -->
          <div class="pull-right">
<?php

echo "<button class=\"btn\"><i class=\"icon-user\"></i> " . $_users[$_user] . "</button>\n";

?>
          </div><!-- /pull-right -->
        </div><!-- /container -->
      </div><!-- /navbar-inner -->
    </div><!-- /navbar -->

    <div class="container">
