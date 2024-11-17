

<?php
include('cfg.php');
?>

<!DOCTYPE html>
<head>

	<script src="js/kolorujtlo.js" type="text/javascript"></script>
	<script src="js/jquery-3.7.1.js"></script>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="pl" />
	<meta name="Author" content="Maciej Kała" />
	<title>index</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<table>
<tr>
    <td><div class="menuBox"><a href="index.php">Strona Główna</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=lab1_burdzchalifa">Burdż Chalifa</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=lab1_merdeka">Merdeka 118</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=lab1_shanghaitower">Shanghai Tower</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=lab1_abradzalbajt">Abradż al-Bajt</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=lab1_pinganfinancecenter">Ping An Finance Center</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=filmy">Filmy</a></div></td>
	<td><div class="menuBox"><a href="index.php?page=poligon">Testuj Poligon</a></div></td>
</tr>
</table>
<br>

<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
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



<br>
<table>

    <tr><td>
        <h2>Przelicznik wysokości</h2>
		<form name="converter">
            <label>Enter Amount:</label>
			<input type="text" name="input" value="0" oninput="convert(this.form, this.form.to, this.form.from)">
			<br><br>
			<label>From:</label>
			<select name="to">
                <option value="3.2808399">metry</option>
				<option value="1">stopy</option>
			</select>
        <label>To:</label>
			<select name="from">
                <option value="1">stopy</option>
				<option value="3.2808399">metry</option>
			</select>
        <br><br>
			<label>Converted Amount:</label>
			<input type="text" name="display" value="0" readonly>
			<br><br>
			<input type="button" value="Otwórz konwerter w nowym oknie" onclick="openWindow()">
			<br><br>
		</form>
		</td></tr>
		<tr><td><i>Skontaktuj się ze mną!</i></td></tr>
		<tr>
			<td>
				<div id="footer">
					<form action="mailto:przykladowy.mail@mail.pl" method="post" enctype="text/plain">
						<label for="name">Imię:</label><br>
						<input type="text" id="name" name="name"><br><br>
						<label for="message">Wiadomość:</label><br>
						<textarea id="message" name="message"></textarea><br><br>
						<input type="submit" value="Wyślij">
					</form>
				</div>
			</td>
		</tr>
    </table>
<?php
$nr_indeksu = '169251';
$nrGrupy = '2';
echo 'Autor: Maciej Kala '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
define('PROJECT_ID', $nr_indeksu);
define('PROJECT_VERSION', 1.5);
?>
</body>
