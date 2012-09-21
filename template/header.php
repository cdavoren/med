<?php header('content-type: text/html; charset=utf-8') ?>
<!DOCTYPE HTML>
<?php
if (!isset($pathPrefix)) {
    $pathPrefix = '';
}
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
<script type="text/javascript" src="<?php echo $pathPrefix ?>script/common.js"></script>
</head>
<body>
<div id="header">
<div class="headerinner">
<div class="headertitle">

</div>
<div class="headerlogin" style="display: <?php echo $loggedUser === null ? 'block' : 'none' ?>">
<form action="#" method="post">
    <a href="#" title="Forgot password">Forgotten password</a> | <a href="#" title="Register">Register</a>&nbsp;
    <input type="text" size="10" name="username" id="username" />
    <input type="password" size="10" name="password" id="password" />
    <input type="button" value="Login" id="loginbutton" onclick="login();"/>
</form>
</div>
<div class="headerwelcome" style="display: <?php echo $loggedUser === null ? 'none' : 'block' ?>">
<em>Welcome <strong><?php echo $loggedUser !== null ? $loggedUser->GetFullname() : '[not logged in]' ?></strong></em> | <a href="javascript:logout();" title="Logout">Logout</a>
</div>
<div class="headerclear">
</div>
</div>
</div>
<div id="content">
