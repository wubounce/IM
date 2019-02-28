<?php
$error = '';
$_SESSION['imobi_data'] = array('imVersion' => '3.8', 'xmlVersion' => '3.5');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include( 'mt/mt_pconnect.php' );
    include( 'mt/mt_pwd.php' );
    $userName = $_POST['userName'];
    $user_password = $_POST['userPassword'];
    $loginSql = $db -> prepare('SELECT * FROM mt_account WHERE user_name=:user_name');
    $loginSql -> bindParam(':user_name', $userName);

    if ($loginSql -> execute())
    {
        $data = $loginSql -> fetch(PDO :: FETCH_ASSOC);
        if ($data)
        {
            $bcrypt = new Bcrypt();
            $isGood = $bcrypt -> verify($user_password, $data['user_password']);
            if (!$isGood)
            {
                $error = 'Your password is incorrect.';
            } 
            else
            {
                $_SESSION['iMobi_sessionTime'] = time();
                $_SESSION['iMobi_userName'] = $userName;
                $_SESSION['iMobi_userId'] = $data['user_id'];
                $_SESSION['iMobi_timeout'] = $data['user_timeout'];
                $_SESSION['iMobi_timezone'] = $data['user_timezone'];
                $_SESSION['iMobi_Login'] = true;

                if (!empty($_SESSION['lastPage']))
                {
                    $redirect = $_SESSION['lastPage'];
                    unset($_SESSION[lastPage]);
                } 
                else
                {
                    $redirect = 'campaigns.php';
                } 

                header('location: ' . $redirect);
                exit();
            } 
        } 
        else
        {
            $error = 'The username entered is not found.';
        }
    }
    else
    {
        $error = $loginSql -> errorCode();
        $error = ($error == '3D000' ? 'Database Connection Error: (DATABASE NOT DEFINED) - The database in mt/mt_config.php is blank. Enter a database name in mt/mt_config.php to connect to the database.<br><br>(Also, be sure the correct databse user name and password are entered in mt/mt_config.php)' : 'Error Code - ' . $error);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet" />
  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>iMobiTrax Login</title>
  <link rel="stylesheet" href="style.css" type="text/css" />
  <link rel="stylesheet" href="../app/plugins/colorbox/colorbox.css" type="text/css" />
  <script type="text/javascript" src="../app/plugins/colorbox/jquery.colorbox-min.js"></script>
  <script type="text/javascript" src="js.js"></script>
  <script type="text/javascript" src="ajax/ajax.js"></script>
  <script type="text/javascript">
    $(document).ready(loadPage);
    function loadPage() {
        attachColorbox();
    }
  </script>
</head>

<body>

  <div id="content">
    <div id="header">
        <img src="../images/logo.jpg" class="logo" width="188" height="60" alt="iMobiTrax">
    </div>

    <div id="main">
    <h1>Login</h1>
    <?php
    if (!empty($error))
    {
        echo '<div class="error">' . $error . '</div>';
    }
    ?>
    <div id="login">
    <form method="post" action="">
        <input type="hidden" name="tokenId" value="605871997">
        <input type="hidden" name="846c3d198c2af4004cdc58e6e5f00468d81bdda4" value="921541f1e1363396dda7d30197ea67593e9f6bcc">
        <table class="login">
            <tr>
                <td class="name">Username:</td>
                <td><input id="userName" type="text" name="userName" value=""></td>
            </tr>
            <tr>
                <td class="name">Password:</td>
                <td>
                    <input id="userPassword" type="password" name="userPassword">					
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center"><input id="submit" style="margin:5px 0 0 0" class="submit_btn" type="submit" value="Sign In"></td>
            </tr>
        </table>
    </form>
    <p style="text-align:center; font-size:13px"><a href="password.php">I forgot my password</a></p>
    </div>
        </div>
        <div id="footer">
            <img src="../images/logo_small.jpg" width="94" height="30" alt="iMobiTrax">
            <p>&copy; 2013 Mobile Tracking Software ¨C iMobiTrax, LLC - Cracked By LeiFeng</p>
        </div>
    </div>

</body>
</html>
