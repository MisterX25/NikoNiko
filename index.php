<?php 
session_start();
require_once("src/includes/functions.php");

$configs = include("src/config/config.php"); 
$pages = include("src/config/pages.php");

$page = 'home';
if(isset($_GET["page"]) && $_GET["page"] != ''){
	$page = htmlspecialchars($_GET["page"]);
}
if(!(file_exists("src/pages/".$page.".php"))){
	$page = 'error';
	$code = '404';
	header($_SERVER["SERVER_PROTOCOL"]." ".$code);
}

$title = isset($pages[$page]["title"]) ? $pages[$page]["title"] : '';
$keywords = isset($pages[$page]["keywords"]) ? $pages[$page]["keywords"] : '';
$description = isset($pages[$page]["description"]) ? $pages[$page]["description"] : '';

// data is ready: show page
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="keywords" content="<?= $keywords ?>">
    <meta name="description" content="<?= $description ?>">
    <meta charset="utf-8" />

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <script type="text/javascript" src="src/js/links.js"></script>
    <script type="text/javascript" src="src/js/buttons.js"></script>
    <script type="text/javascript" src="src/js/forms.js"></script>
    <script type="text/javascript" src="src/js/load.js"></script>

    <link href="src/css/styles.css" rel="stylesheet" type="text/css" />

    <title><?php echo $title ?></title>
</head>

<body>

<header>
    <a href="home" id="logo"><img src="assets/images/logoInformatiqueVert.jpg" alt="logo" /></a>
    <nav id="menu">
        <a href="home">Accueil</a>
        <a href="people">Personnes</a>
        <a href="classes">Cours</a>
        <a href="workweeks">Semaines de travail</a>
        <a href="links">Aide</a>
    </nav>
</header>

<main>
    <h1><?= $title ?></h1>
    <div id="content">
        <?php include("src/pages/".$page.".php"); ?>
    </div><!-- content -->
</main><!-- page -->

<footer>
    &copy; 2016 <a href="http://www.cpnv.ch">CPNV</a> - XCL - All Rights Reserved
</footer>

</body>
</html>
