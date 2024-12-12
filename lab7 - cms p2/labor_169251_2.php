<?php
$nr_indeksu = '169251';
$nrGrupy = '2';
echo 'Maciej Kała '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
echo 'Zastosowanie metody include() <br />';
echo 'Pozwala na dołączenie pliku <br />';
include 'fruits.php';
echo "$apple <br />";
echo "$banana <br />";
echo '<br/>Warunki if, else, elseif, switch  <br /><br/>';
echo 'if, else jeśli, inaczej - jeśli argument jest prawdą, wykonaj. Inaczej wykonaj coś innego. <br />';
echo 'elseif jeśli warunków ma być więcej, switch jeśli potrzebujemy dużo różnych opcji. <br/><br />';
$a = 1;
$b = 2;
$c = 3;

if ($a > $b) {
  echo "a is the biggest <br/><br/>";
  }
    elseif ($b > $c){
  echo "b is the biggest <br/><br/>";
  }
  else{
  echo "c is the biggest <br/><br/>";
  }



echo 'Pętla while() i for()  <br /><br/>';
echo 'Pozwala na zapętlenie funkcji<br /><br/>';

$i = 1;
while ($i <= 5) {
    echo $i++;
}
for ($i = 1; $i <= 5; $i++) {
    echo $i;
}
echo '<br /><br/>Typy zmiennych $_GET, $_POST, $_SESSION  <br/><br/>';
echo '$_GET pozwala na wyciągnięcie wartości zmiennych z urla,<br/> $_POST - Tablica asocjacyjna zmiennych przekazywanych do bieżącego skryptu za pomocą metody HTTP POST,<br/> $_SESSION - Tablica asocjacyjna zawierająca zmienne sesji dostępne dla bieżącego skryptu. <br/><br/>';
?>