<?php
session_start();
require_once("src/includes/functions.php");

$configs = include("src/config/config.php");
$pages = include("src/config/pages.php");

$page = 'home';
if (isset($_GET["page"]) && $_GET["page"] != '')
{
    $page = htmlspecialchars($_GET["page"]);
}
if (!(file_exists("src/pages/" . $page . ".php")))
{
    $page = 'error';
    $code = '404';
    header($_SERVER["SERVER_PROTOCOL"] . " " . $code);
}

$title = isset($pages[$page]["title"]) ? $pages[$page]["title"] : '';
$keywords = isset($pages[$page]["keywords"]) ? $pages[$page]["keywords"] : '';
$description = isset($pages[$page]["description"]) ? $pages[$page]["description"] : '';

extract($_SESSION); // $user
if (!isset($user)) $page = "login"; // force login page
// data is ready: show page
?>

<!DOCTYPE html>
<html>
<head>
    <base href="/NikoNiko/"> <!-- This because of the URL Rewriting, that shifts the base path -->
    <meta name="keywords" content="<?= $keywords ?>">
    <meta name="description" content="<?= $description ?>">
    <meta charset="utf-8"/>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <script type="text/javascript" src="src/js/dynamenu.js"></script>
    <script type="text/javascript" src="src/js/links.js"></script>
    <script type="text/javascript" src="src/js/buttons.js"></script>
    <script type="text/javascript" src="src/js/forms.js"></script>
    <script type="text/javascript" src="src/js/load.js"></script>
    <script type="text/javascript" src="src/js/clickabletable.js"></script>
    <script type="text/javascript" src="src/js/misc.js"></script>

    <link href="src/css/styles.css" rel="stylesheet" type="text/css"/>

    <title><?php echo $title ?></title>
</head>

<body data-page="<?= $page ?>">

<header>
    <a href="home" id="logo"><img src="assets/images/logoInformatiqueVert.jpg" alt="logo"/></a>
    <nav id="menu">
        <div class="dynamenutop"><a href="home">Accueil</a></div>
        <div class="dynamenutop" id="menuPersons">Personnes</div>
        <div class="dynamenutop"><a href="classes">Cours</a></div>
        <div class="dynamenutop"><a href="workweeks">Semaines de travail</a></div>
        <div class="dynamenutop"><a href="links">Aide</a></div>
        <div class="dynamenutop"><a href="contact">Contact</a></div>
        <?php
        if (isset($user))
        {
            echo "<a class='rightsidelink' href='logout'>Déconnexion $user</a>";
        }
        ?>
        </ul>
    </nav>
</header>

<main>
    <h1><?= $title ?></h1>
    <div id="content">
        <?php include("src/pages/" . $page . ".php"); ?>
    </div><!-- content -->
</main><!-- page -->

<footer>
    &copy; 2016 <a href="http://www.cpnv.ch">CPNV</a> - XCL - All Rights Reserved
</footer>

</body>
</html>
