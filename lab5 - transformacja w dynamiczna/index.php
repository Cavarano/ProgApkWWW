<!DOCTYPE html>
<head>
	<script src="js/kolorujtlo.js" type="text/javascript"></script>
	<script src="js/jquery-3.7.1.js"></script>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="pl" />
	<meta name="Author" content="Maciej Kała" />
	<title>Strona Główna</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
        <tr>
           <td>
                <menu>
					<li><u><a href="index.php">Strona Główna</a></u></li>
					<li><u><a href="index.php?page=lab1_burdzchalifa">Burdż Chalifa</a></u></li>
					<li><u><a href="index.php?page=lab1_merdeka">Merdeka 118</a></u></li>
					<li><u><a href="index.php?page=lab1_shanghaitower">Shanghai Tower</a></u></li>
					<li><u><a href="index.php?page=lab1_abradzalbajt">Abradż al-Bajt</a></u></li>
					<li><u><a href="index.php?page=lab1_pinganfinancecenter">Ping An Finance Center</a></u></li>
					<li><u><a href="index.php?page=filmy">Filmy</a></u></li>
					<li><u><a href="index.php?page=poligon">Testuj Poligon</a></u></li>
				</menu>
			</td>
		</tr>
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$nr_indeksu = '169251';
$nrGrupy = '2';
echo 'Autor: Maciej Kala '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
define('PROJECT_ID', $nr_indeksu);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $file_path = 'html/' . $page . '.html';

    if (file_exists($file_path)) {
        include($file_path);
    } else {
        echo "Wybrana strona nie istnieje.";
    }
} else {
    include('html/glowna.html');
}
?>
</body>
