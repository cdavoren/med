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
<script type="text/javascript" source="<?php echo $pathPrefix ?>script/jquery-1.8.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix ?>css/yui-3.6.0-reset-fonts-base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $pathPrefix ?>css/common.css" />
<style>
</style>
</head>
<body>
