<?php header('Content-type: text/html; charset=utf-8') ?>
<?php header('Access-Control-Allow-Origin: http://ubuntu-vm') ?>
<!DOCTYPE HTML>
<?php
if (!isset($pathPrefix)) {
    $pathPrefix = App::getRelativeRootForPath();
}
$loggedUser = App::getUser();
$config = App::getConfiguration();

?>
<html>
<head>
<?php if (isset($pageTitle)) : ?>
<title><?php echo $pageTitle ?></title>
<?php endif; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix ?>css/yui-3.6.0-reset-fonts-base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix ?>css/common.css" />
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/jquery-1.8.1.js"></script>
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/common.js"></script>
<script type="text/javascript">
$.appConfig = {
  ajax_domain : '<?php echo $config['app_server'].$config['app_root'] ?>',
  current_origin : '<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$config['app_server'].$config['app_root'] ?>'
  <?php echo ($config['ssl_enabled'] ? (',https_endpoint : "https://'.$config['app_server'].$config['app_root'].'"') : '') ?> 
<?php if ($loggedUser !== null) : ?>
  ,user : '<?php echo $loggedUser->getUsername() ?>',
  user_fullname : '<?php echo $loggedUser->getFullname() ?>'
<?php endif ?>
};
</script>
</head>
<body>
<div id="header">
<div class="headerinner">
<div class="headertitle">
<a href="<?php echo $config['app_root']?>" title="Home">
<img id="headerlogo" src="<?php echo $pathPrefix ?>images/medtest_logo.png" alt="medtest" />
<span>MedTest</span>
</a>
</div>
<div class="headerright">
    <div class="headerlogin" style="display: <?php echo $loggedUser === null ? 'block' : 'none' ?>">
    <!-- <form action="<?php echo ($config['ssl_enabled'] ? 'https' : 'http').'://'.$config['app_server'].$config['app_root'].'user/login.php' ?>" method="post" id="loginform"> -->
    <form action="javascript:void(0);" method="post" id="loginform">
        <a id="resetpasswordlink" href="#" title="Forgot password">Forgotten password</a> | <a href="<?php echo $pathPrefix ?>user/add.php" title="Register">Register</a>&nbsp;
        <input type="text" size="10" name="username" id="username" />&nbsp;
        <input type="password" size="10" name="password" id="password" />&nbsp;
        <input type="submit" class="custombutton" value="Login" id="loginbutton"  />
    </form>
    </div>
    <div class="headerwelcome" style="display: <?php echo $loggedUser === null ? 'none' : 'block' ?>">
    <em>Welcome <strong><?php echo $loggedUser !== null ? $loggedUser->GetFullname() : '[not logged in]' ?></strong></em> | <a href="<?php echo $pathPrefix ?>user/profile.php" title="Profile">Profile</a> | <a href="#" title="Logout" id="logoutlink" >Logout</a>
    </div>
    <div id="headerloading">
        <span id="headerstatus">[status message]</span><img src="<?php echo $pathPrefix ?>images/ajax-loader-bg-black.gif" alt="processing" />
    </div>
    <div id="headererror">
        Error message.
    </div>
</div>
<div class="headerclear">
</div>
</div>
<script type="text/javascript">
test_function = function() {
    console.log('Test output.');
}

$(document).ready(function() { 
    $('#loginbutton').click(login);
    $('#logoutlink').click(logout);
    $('#resetpasswordlink').click(resetPassword);
});
</script>
</div>
<div id="content">
