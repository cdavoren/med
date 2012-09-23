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
<style>
</style>
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/jquery-1.8.1.js"></script>
<script type="text/javascript">
$.appConfig = {};
$.appConfig.app_server = '<?php echo $config['app_server'] ?>';
$.appConfig.app_root = '<?php echo $config['app_root'] ?>';
$.appConfig.ssl_enabled = <?php echo $config['ssl_enabled'] ?>;
</script>
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/common.js"></script>
<script type="text/javascript">
$.session = {};
$.session.sessionid='<?php echo session_id() ?>';
</script>
</head>
<body>
<div id="header">
<div class="headerinner">
<div class="headertitle">

</div>
<div class="headerright">
    <div class="headerlogin" style="display: <?php echo $loggedUser === null ? 'block' : 'none' ?>">
    <form action="#" method="post">
        <a href="#" title="Forgot password">Forgotten password</a> | <a href="#" title="Register">Register</a>&nbsp;
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
$(document).ready(function() { 
    $('#loginbutton').click(login);
    $('#logoutlink').click(logout);
});
</script>
</div>
<div id="content">
