<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    //łączy z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass);
	if(!$link) echo '<b>przerwane połączenie </b>';
	if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';

    //passy do admina
$login = 'maciek';
$pass = 'Admin123';
?>