<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="RS">
  <meta name="author" content="Ashesh Kumar Singh <user501254@gmail.com>">
  <title>Kibitzer</title>
  <!-- Twitter Bootstrap Core CSS -->
  <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Custom Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Bitter:700" rel="stylesheet" type="text/css" />
  <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700|Roboto' rel='stylesheet' type='text/css'>
  <!-- Flat-UI for Bootstrap -->
  <link href="bower_components/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet" type="text/css" />
  <!-- Trip.js -->
  <link href="bower_components/trip.js/dist/trip.min.css" rel="stylesheet" type="text/css" />
  <!-- Custom CSS -->
  <link href="css/main.css" rel="stylesheet" type="text/css" />
  <link href="css/rate.css" rel="stylesheet" type="text/css" />
  <script src="js/modernizr.custom.js" type="text/javascript"></script>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="57x57" href="/img/favicons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/img/favicons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/img/favicons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/img/favicons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/img/favicons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/img/favicons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/img/favicons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/img/favicons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon-180x180.png">
  <link rel="icon" type="image/png" href="/img/favicons/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/img/favicons/android-chrome-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="/img/favicons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="/img/favicons/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="/img/favicons/manifest.json">
  <link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="shortcut icon" href="/img/favicons/favicon.ico">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="msapplication-TileImage" content="/img/favicons/mstile-144x144.png">
  <meta name="msapplication-config" content="/img/favicons/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">
</head>

<body id=music>
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand brand" href="index.php">Kibitzer</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li id="tour1" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
              class="glyphicon glyphicon-user"></i><?php echo $_SESSION['full_name'] ?><span
              class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#reset">Reset</a></li>
              <li class="divider"></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
  </nav>
  <div class="wrapper">
    <ul class="stage clearfix">

      <li class="scene">
        <div class="movie" onclick="return true">
          <div class="poster"></div>
          <div class="info">
            <header>
              <h1>It's a Wonderful Life</h1>
              <span class="year">1946</span>
              <span class="rating">PG</span>
              <span class="duration">130 minutes</span>
            </header>
            <p class="abstract text-justify">
              In Bedford Falls, New York on Christmas Eve, George Bailey is deeply troubled. Prayers for his well-being from friends and family reach Heaven. Clarence Odbody, Angel Second Class, is assigned to visit Earth to save George, thereby earning his wings. Franklin and Joseph, the head angels, review George's life with Clarence.
            </p>
          </div>
        </div>
      </li>

      <li class="scene">
        <div class="movie" onclick="return true">
          <div class="poster"></div>
          <div class="info">
            <header>
              <h1>Vengeance Valley</h1>
              <span class="year">1951</span>
              <span class="rating">NR</span>
              <span class="duration">83 minutes</span>
            </header>
            <p class="abstract text-justify">
              A cattle baron takes in an orphaned boy and raises him, causing his own son to resent the boy. As they get older the resentment festers into hatred, and eventually the real son frames his stepbrother for fathering an illegitimate child that is actually his, seeing it as an opportunity to get his half-brother out of the way so he can have his father's empire all to himself.
            </p>
          </div>
        </div>
      </li>

      <li class="scene">
        <div class="movie" onclick="return true">
          <div class="poster"></div>
          <div class="info">
            <header>
              <h1>The Gold Rush</h1>
              <span class="year">1925</span>
              <span class="rating">NR</span>
              <span class="duration">95 minutes</span>
            </header>
            <p class="abstract text-justify">
              The Tramp travels to the Yukon to take part in the Klondike Gold Rush. He gets mixed up with some burly characters and falls in love with the beautiful Georgia. He tries to win her heart with his singular charm.
            </p>
          </div>
        </div>
      </li>
      
      <li class="scene">
        <div class="movie" onclick="return true">
          <div class="poster"></div>
          <div class="info">
            <header>
              <h1>The Gold Rush</h1>
              <span class="year">1925</span>
              <span class="rating">NR</span>
              <span class="duration">95 minutes</span>
            </header>
            <p class="abstract text-justify">
              The Tramp travels to the Yukon to take part in the Klondike Gold Rush. He gets mixed up with some burly characters and falls in love with the beautiful Georgia. He tries to win her heart with his singular charm.
            </p>
          </div>
        </div>
      </li>
      
      <li class="scene">
        <div class="movie" onclick="return true">
          <div class="poster"></div>
          <div class="info">
            <header>
              <h1>The Gold Rush</h1>
              <span class="year">1925</span>
              <span class="rating">NR</span>
              <span class="duration">95 minutes</span>
            </header>
            <p class="abstract text-justify">
              The Tramp travels to the Yukon to take part in the Klondike Gold Rush. He gets mixed up with some burly characters and falls in love with the beautiful Georgia. He tries to win her heart with his singular charm.
            </p>
          </div>
        </div>
      </li>

    </ul>
  </div>
  <!-- /.wrapper -->
</body>

</html>