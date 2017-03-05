<!-- connect to MySQL Database -->
<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "kibitzer";
$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
?>

<?php
//php session
session_start();

//redirect to dashboard if user is logged in
if (isset($_SESSION['username'])) {
  header("Location: index.php");
}

//check if Register form is submitted
if (isset($_POST['submit'])) {
  //to prevent SQL INJECTION ATTACK
  $userFullName = trim(ucwords(mysqli_real_escape_string($connection, $_POST["fullname"])));
  $userName = trim(mysqli_real_escape_string($connection, $_POST["username"]));
  if (!preg_match_all('$\S*(?=\S{4,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$', $_POST["password"])) {
    $message = "The password does not meet the requirements!";
  } else {
    $password = trim(mysqli_real_escape_string($connection, $_POST["password"]));
  }

  $options = [
    'cost' => 12
  ];
  $userPassword = password_hash($password, PASSWORD_BCRYPT, $options) . "\n";

  $sql = "CREATE TABLE IF NOT EXISTS `accounts` (";
  $sql .= "`userID` INT NOT NULL , ";
  $sql .= "`userFullName` VARCHAR(36) NOT NULL , ";
  $sql .= "`userName` VARCHAR(24) NOT NULL , ";
  $sql .= "`userPassword` CHAR(60) NOT NULL , ";
  $sql .= "`wantsBooksRec` BOOLEAN NULL DEFAULT NULL , ";
  $sql .= "`wantsMoviesRec` BOOLEAN NULL DEFAULT NULL , ";
  $sql .= "`wantsMusicRec` BOOLEAN NULL DEFAULT NULL , ";
  $sql .= "PRIMARY KEY (`userID`), UNIQUE (`userFullName`), UNIQUE (`userName`))";
  $sql .= "ENGINE = InnoDB;";
  $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
  
  //make sure that the name and fullname don't already exist
  $sql = "SELECT * FROM `accounts` WHERE `userName`='$userName' OR `userFullName`='$userFullName';";
  $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
  if (mysqli_num_rows($result) >= 1) {
    $message = "User account already exists.";
  } else {
    //make a new entry in "accounts" TABLE
    $sql = "SELECT COUNT(*) FROM `accounts`;";
    $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    if (!$result) {
      die("SELECT COUNT(*) FROM `accounts`, failed: " . mysqli_error($connection));
    } else {
      //assign contineous userID
      $rowcount=mysqli_num_rows($result);
      printf("Result set has %d rows.\n",$rowcount);
      $rows = mysqli_fetch_row($result);
      $userID = $rows[0];
    }
    $sql = "INSERT INTO `accounts` VALUES ('{$userID}','{$userFullName}','{$userName}','{$userPassword}','NULL','NULL','NULL');";
    $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    if (!$result) {
      die("INSERT INTO `accounts` . . . , failed: " . mysqli_error($connection));
    } else {
      //display registration successful message and redirect to Login page
      $message = "Registered as \"" . $userName . "\", redirecting . . .";
      header("refresh:3;url=login.php");
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
  <!-- Registeration Form -->
  <form id="register-form" action="register.php" method="POST">
    <div class="text-center">
    <a href="index.php">
      <span class="brand">Kibitzer</span>
      <?php echo "<p id=\"messages\">" . $message . "</p>"; ?>
    </a>
    </div>
    <div class="form-group input-group-lg">
      <input type="text" name="fullname" required="" pattern="^[A-Za-z. ]{3,36}" placeholder="Your full name" class="form-control" oninvalid="setCustomValidity('must be between 3 to 36 characters long; can have alphabets, dots and spaces.')" onchange="try{setCustomValidity('')}catch(e){}">
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
      <input type="text" name="username" class="form-control" autocomplete="off" required="" pattern="^[a-z0-9.]{3,24}$" placeholder="enter desired Username" aria-describedby="sizing-addon1" oninvalid="setCustomValidity('must be between 3 to 24 characters long; can have lowercase alphabets, numbers and dots.')" onchange="try{setCustomValidity('')}catch(e){}">
    </div>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
      <input type="text" name="password" class="form-control" autocomplete="off" required="" pattern="^(?=^.{4,}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" placeholder="enter some Password" aria-describedby="sizing-addon1" oninvalid="setCustomValidity('must be atleast 4 characters long; must be a combination of upercase & lowercase alphabets and digit(s); other characters are optional.')" onchange="try{setCustomValidity('')}catch(e){}">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Make account</button>
  </form>
  <div class="footer">
    <hr/>
    <a class="input-group" href="login.php">Already have an account?</a>
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
