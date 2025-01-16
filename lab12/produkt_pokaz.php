<link rel="stylesheet" href="css/admin.css">

<?php

// załączamy zawartość pliku cfg.php

include('./cfg.php');

// funkcja PokazProdukt pokazuje informacje o produkt o danym id
// oraz umożliwia przekierowanie do koszyka, jeśli produkt jest dostępny

function PokazProdukt($id)
{
    // wyszukujemy w bazie danych produkt od id = $id

    global $link;
    $query = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($link, $query);

    // jeśli zapytanie się udało, to kontynuujemy
    // w przeciwnym przypadku wyświetlany jest odpowiedni komunikat

    if($result)
    {
        $brak = 0;
        while($row = mysqli_fetch_array($result))
        {
            // wyszukujemy w bazie danych kategorii odpowiadającej produktowi

            $idk = $row['category'];
            $query1 = "SELECT * FROM categories WHERE id='$idk' LIMIT 1";
            $result1 = mysqli_query($link ,$query1);
            $row1 = mysqli_fetch_array($result1);
            $matka = $row1['parent_id'];
            if($row1['parent_id'] > 0){
                $query2 = "SELECT * FROM categories WHERE id='$matka' LIMIT 1";
                $result2 = mysqli_query($link ,$query2);
                $row2 = mysqli_fetch_array($result2);
            }
            $brak = 1;

            // ustalamy cenę brutto produktu

            $cena = round($row['price'] + ($row['price'] * $row['vat'] / 100), 2);

            // wyświetlamy informację o produkcie

            echo '<h1 class="naglowek" style="padding-top: 36px">'.$row['title'].'</h1>';
            echo '<center><img style="padding-bottom: 36px;" src="data:image/jpeg;base64,'.base64_encode($row['image']).'" height="300"/>';
            echo '
				<table>	
					<tr><th class="tn">name</th><td class="tdane">'.$row['title'].'</td></tr>		
					<th class="tn">description</th><td class="topis">'.$row['description'].'</td>';
            if($row1['parent_id'] > 0){
                echo '<tr><th class="tn">category</th><td class="tdane">'.$row1['name'].' '.$row2['name'].'</td></tr>';
            }else{
                echo '<tr><th class="tn">category</th><td class="tdane">'.$row1['name'].'</td></tr>';
            }
            echo '
					<tr><th class="tn">price</th><td class="tdane">'.$cena.' PLN</td></tr>
					<tr><th class="tn">amount</th><td class="tdane">'.$row['amount'].'</td></tr>					
					<tr><th class="tn">size</th><td class="tdane">'.$row['size'].'</td></tr>
					
				</center>';

            // jeśli produkt jest dostępny, to można dodać go do koszyka,
            // w przeciwnym przypadku wyświetla się odpowiedni komunikat

            if($row['status'] == 1){
                echo '<tr><th class="ttak">AVAILABLE</th><td class="tb"><a href="koszyk.php?availability='.$row['id'].'"><b>ADD TO CART</b></a></td></tr></table>';
            }else{
                echo '<tr><th class="tnie">NOT AVAILABLE</th><td class="tb">CANNOT ADD</td></tr></table>';
            }
        }

        echo '</table></center>';
    }
    else
    {
        echo '<h1 class="naglowek">Error viewing the product!</h1></center>';
    }

}

// jeśli jest ustanowiona zmienna $_GET['id'] i nie jest pusta, to wywołujemy funkcję PokazProdukt()

if(isset($_GET['id']) && !empty($_GET['id']))
{
    $id = $_GET['id'];
    PokazProdukt($id);
}

echo '<p style="text-align: center; padding-top: 36px;"><a href="sklep.php">wróć</a></p>';

?>