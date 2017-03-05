<!-- connect to MySQL Database -->
<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "project_se";
$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
?>

<?php
//php session
session_start();

//redirect to dashboard if user is logged in
if (isset($_SESSION['username'])) {
  //header("Location: index.php");
}

//check if Login form is submitted
if (isset($_POST['submit'])) {
  //to prevent SQL INJECTION ATTACK
  $userName = trim(mysqli_real_escape_string($connection, $_POST["username"]));
  $userPassword = trim(mysqli_real_escape_string($connection, $_POST["password"]));


  //validate input login details from "accounts" TABLE
  $sql = "SELECT * FROM `accounts` WHERE `userName`='$userName';";
  $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
  if (!$result) {
    die("SELECT * FROM `accounts` . . . , failed: " . mysqli_error($connection));
  } else {
    //fetches one row and return it as an array,
    $row = mysqli_fetch_row($result);

    //user exists so compare supplied $password
    if ($row) {
      $existing_hash = "$row[1]";
      if (password_verify("$userPassword", "$existing_hash")) {
        $_SESSION['username'] = $row[0];
        $_SESSION['fullname'] = $row[3];
        $_SESSION['userid'] = $row[2];
        
        for($i=0; $i < $pref; $i++)
    {
      echo($pref[$i] . " ");
    }
        //header('Location:index.php');
      } else {
        $message = "Wrong Username/Password, try again . . .";
      }
    } else {
      $message = "User does not exists.";
    }
  }
} else {
  //default username
  $username = "";
  //default message
  $message = "Books ● Movies ● Music";
}
?>
<!DOCTYPE html>
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
  <!-- Flat-UI for Bootstrap CSS -->
  <link href="bower_components/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet" type="text/css" />
  <!-- FontAwesome CSS -->
  <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Trip.js CSS -->
  <link href="bower_components/trip.js/dist/trip.min.css" rel="stylesheet" type="text/css" />
  <!-- Custom CSS -->
  <link href="css/main.css" rel="stylesheet" type="text/css" />
  <link href="css/login-register.css" rel="stylesheet" type="text/css" />

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
<body>
<!-- Login Form -->
<form action="login.php" method="POST">
  <div class="text-center">
    <a href="index.php">
      <span class="brand">Kibitzer</span>
      <?php echo "<p id=\"messages\">" . $message . "</p>"; ?>
    </a>
  </div>
  <div class="input-group input-group-lg">
    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
    <input type="text" name="username" value="" autofocus="" class="form-control" required="" placeholder="Username"
           aria-describedby="sizing-addon1"/>
  </div>
  <div class="input-group input-group-lg">
    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
    <input type="password" name="password" value="" class="form-control" required="" placeholder="Password"
           aria-describedby="sizing-addon1"/>
  </div>
  <div class="text-center">
  <select name="pref[]" data-toggle="select" multiple class="text-center form-control multiselect multiselect-primary mrs mbm">
    <option value="36" selected>Books</option>
    <option value="66" selected>Movies</option>
    <option value="68" selected>Music</option>
  </select>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Login</button>
</form>
<div class="footer">
  <hr/>
  <a class="input-group" href="register.php">Make an account</a>
</div>

  <!-- jQuery -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Twitter Bootstrap Core JS -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="bower_components/flat-ui/dist/js/flat-ui.min.js"></script>
  <script src="bower_components/flat-ui/docs/assets/js/application.js"></script>
</body>

</html>
<!-- close database connection -->
<?php
mysqli_close($connection);
?>
