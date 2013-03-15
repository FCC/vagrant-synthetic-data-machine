<?php
$page = (isset($page)) ? $page : "home";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Generator: Developer Documentation</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/docs.css" rel="stylesheet">
    <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body data-spy="scroll" data-target=".bs-docs-sidebar">

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="dropdown">
              	<a class="brand dropdown-toggle" data-toggle="dropdown" href="#">
              		Developer Doc
              		<b class="caret"></b>
              	</a>
		        <ul class="dropdown-menu">
		          <li><a href="./">Developer Doc</a></li>
		          <li><a href="phpdoc/">PHP Documentation</a></li>
		          <li><a href="jsdoc/">JS Documentation</a></li>
		        </ul>
              </li>
              <li <?php if ($page == "home") echo 'class="active"'; ?>><a href="index.php">Intro</a></li>
              <li <?php if ($page == "core") echo 'class="active"'; ?>><a href="core.php">The Core Script</a></li>
              <li <?php if ($page == "dataTypes") echo 'class="active"'; ?>><a href="dataTypes.php">Data Types</a></li>
              <li <?php if ($page == "exportTypes") echo 'class="active"'; ?>><a href="exportTypes.php">Export Types</a></li>
              <li <?php if ($page == "country") echo 'class="active"'; ?>><a href="countryData.php">Country Data</a></li>
              <li <?php if ($page == "translations") echo 'class="active"'; ?>><a href="translations.php">Translations</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
