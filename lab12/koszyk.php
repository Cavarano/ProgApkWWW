<link rel="stylesheet" href="css/admin.css">

<?php

session_start();
include('./cfg.php');

// funkcja addToCard() wyświetla formularz dodawania produktów,
// dodaje do koszyka

function addToCard($id)
{
    global $link;

    // jeśli $id = 0 to wyświetla wszystkie dostępne produkty,
    // w przypadku podanego $id wyświetla tylko produkt o $id

    if($id == 0)
    {
        $query = "SELECT * FROM products ORDER BY id ASC";
        $result = mysqli_query($link, $query);
    }
    else
    {
        $query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
        $result = mysqli_query($link, $query);
    }

    echo '<div class="col"><h1 class="naglowek">Dostępne Produkty</h1>';

    // formularz poniżej przedstawia wybrane produkty z bazy danych,
    // w przypadku błędu bądź braku takich produktów funkcja
    // wyrzuca odpowiedni komunikat

    if($result)
    {
        $brak = 0;
        while($row = mysqli_fetch_array($result))
        {
            $cena = round($row['price'] + ($row['price'] * $row['vat'] / 100), 2);
            if($row['status'] == 1)
            {
                $brak = 1;
                echo ' 
				<div><center>
					<img src="data:image/jpeg;base64,'.base64_encode($row['image']).'" height="300"/>
					<form method="post" action="koszyk.php?fun=add&id='.$row['id'].'">
						<input type="hidden" name="idp" value='.$row['id'].' />
						<input type="hidden" name="cena" value='.$cena.' />
						<table>
							<tr><td>nazwa:</td><td><b>'.$row['title'].'</b></td></tr>
							<tr><td>cena:</td><td>'.$cena.' PLN</td></tr>
							<tr><td>ilość:</td><td><input type="number" name="amount" value=1 required /></td></tr>
							<tr><td></td><td><input type="submit" name="add_cart" value="Do koszyka" /></td></tr>
						</table>
					</form>
				</center></div>';
            }
        }
        if($brak == 0)
        {
            echo '<center>Brak produktów</center>';
        }
        echo '</div>';
    }
    else
    {
        echo '<center>Błąd wczytywania produktów</center>';
    }

    // jeśli klikamy na "Add to cart", funkcja przechodzi dalej

    if(isset($_POST['add_cart']) && isset($_GET['id']) && isset($_GET['fun']))
    {
        if($_GET['fun'] == 'add')
        {
            // jeśli nie podano ilości ustawia na 1, w przeciwnym razie dolicza podaną ilość do koszyka

            if(!isset($_SESSION['count']))
            {
                $_SESSION['count'] = 1;
            }
            else
            {
                $_SESSION['count']++;
            }

            // nadanie numeru dla produktu w koszyku i innych pól

            $nr = $_SESSION['count'];
            $idp = $_POST['idp'];
            $cena = $_POST['cena'];
            $amount = $_POST['amount'];

            // idiotproof: jeśli wartość niższa od 1 to ilość ustawia na 1

            if($amount < 1)
            {
                $amount = 1;
            }

            // wyszukujemy w bazie danych produkt o id = $idp

            $query = "SELECT * FROM products WHERE id='$idp' LIMIT 1";
            $result = mysqli_query($link ,$query);
            $row = mysqli_fetch_array($result);

            // jeżeli produkt o podanym $idp występuje w koszyku, pozycja jest edytowana

            $x = 1;

            while($x < $_SESSION['count'])
            {
                if($_SESSION[$x.'_1'] == $idp)
                {
                    $_SESSION[$x.'_2'] += $amount;
                    $_SESSION[$x.'_6'] += $cena * $amount;

                    // warunek na utrzymanie maksymalnej możliwej ilości danego produktu w koszyku

                    if($_SESSION[$x.'_2'] > $row['amount'])
                    {
                        $_SESSION[$x.'_2'] = $row['amount'];
                        $_SESSION[$x.'_6'] = $cena * $row['amount'];
                    }
                    $_SESSION[$x.'_3'] = time();
                    $_SESSION['count']--;

                    // przekierowywujemy do koszyk.php

                    echo "<script>window.location.href='koszyk.php';</script>";
                    exit();
                }
                $x++;
            }

            // zapisanie danych produktów w tablicy 2 wymiarowej - resztę pobierzemy po idp z bazy danych

            $prod[$nr]['idp'] = $idp;
            $prod[$nr]['amount'] = $amount;
            $prod[$nr]['date'] = time();
            $prod[$nr]['title'] = $row['title'];
            $prod[$nr]['price'] = $cena;
            $prod[$nr]['price_sum'] = $cena * $prod[$nr]['amount'];
            $prod[$nr]['img'] = $row['image'];

            // stworzenie dwuwymiarowej numeracji - dla jednowymiarowej tablicy

            $nr_0 = $nr.'_0';
            $nr_1 = $nr.'_1';
            $nr_2 = $nr.'_2';
            $nr_3 = $nr.'_3';
            $nr_4 = $nr.'_4';
            $nr_5 = $nr.'_5';
            $nr_6 = $nr.'_6';
            $nr_7 = $nr.'_7';

            // zapisanie w tablicy sesji danych produktów

            $_SESSION[$nr_0] = $nr;
            $_SESSION[$nr_1] = $prod[$nr]['idp'];
            $_SESSION[$nr_2] = $prod[$nr]['amount'];
            $_SESSION[$nr_6] = $prod[$nr]['price_sum'];

            // jeśli liczba sztuk przekracza stan w magazynie,
            // to ustalana jest wartość stanu magazynu

            if($_SESSION[$nr_2] > $row['amount'])
            {
                $_SESSION[$nr_2] = $row['amount'];
                $_SESSION[$nr_6] = $cena * $row['amount'];
            }
            $_SESSION[$nr_3] = $prod[$nr]['date'];
            $_SESSION[$nr_4] = $prod[$nr]['title'];
            $_SESSION[$nr_5] = $prod[$nr]['price'];

            $_SESSION[$nr_7] = $prod[$nr]['img'];

            // przekierowywujemy do koszyk.php

            echo "<script>window.location.href='koszyk.php';</script>";
        }
    }
}

// funkcja removeFromCard() usuwana produkt z koszyka

function removeFromCard()
{
    if(isset($_GET['nr']))
    {
        $nr = $_GET['nr'];

        if($nr == $_SESSION['count'])
        {
            // jeśli produkt jest na końcu koszyka

            unset($_SESSION[$nr.'_0']);
            unset($_SESSION[$nr.'_1']);
            unset($_SESSION[$nr.'_2']);
            unset($_SESSION[$nr.'_3']);
            unset($_SESSION[$nr.'_4']);
            unset($_SESSION[$nr.'_5']);
            unset($_SESSION[$nr.'_6']);
            unset($_SESSION[$nr.'_7']);
        }
        else
        {
            // jeśli produkt nie jest na końcu koszyka

            for($x = $nr; $x < $_SESSION['count'] ; $x++)
            {
                $t = $x + 1;
                $_SESSION[$x.'_1'] = $_SESSION[$t.'_1'];
                $_SESSION[$x.'_2'] = $_SESSION[$t.'_2'];
                $_SESSION[$x.'_3'] = $_SESSION[$t.'_3'];
                $_SESSION[$x.'_4'] = $_SESSION[$t.'_4'];
                $_SESSION[$x.'_5'] = $_SESSION[$t.'_5'];
                $_SESSION[$x.'_6'] = $_SESSION[$t.'_6'];
                $_SESSION[$x.'_7'] = $_SESSION[$t.'_7'];
            }

            unset($_SESSION[$_SESSION['count'].'_0']);
            unset($_SESSION[$_SESSION['count'].'_1']);
            unset($_SESSION[$_SESSION['count'].'_2']);
            unset($_SESSION[$_SESSION['count'].'_3']);
            unset($_SESSION[$_SESSION['count'].'_4']);
            unset($_SESSION[$_SESSION['count'].'_5']);
            unset($_SESSION[$_SESSION['count'].'_6']);
            unset($_SESSION[$_SESSION['count'].'_7']);
        }

        // jeśli usunęliśmy jedyny produkt w koszyku

        $_SESSION['count']--;
        if($_SESSION['count'] == 0)
        {
            unset($_SESSION['count']);
        }

        // przekierowanie do koszyk.php

        echo "<script>window.location.href='koszyk.php';</script>";
    }
}

// EditAmount() wyświetla formularz edytowania ilości produktu w koszyku

function EditAmount()
{
    global $link;

    if(isset($_GET['nr']))
    {
        $nr = $_GET['nr'];
        $id = $_SESSION[$nr.'_1'];

        // wyszukujemy w bazie danych produkt o id = $id

        $query = "SELECT * FROM products WHERE id='$id' LIMIT 1";
        $result = mysqli_query($link ,$query);
        $row = mysqli_fetch_array($result);

        echo '
		<div>
			<h1 class="naglowek"><b>Change amount</b></h1>
			<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
				<table>
					<tr><td><b>amount:</b></td><td><input type="number" name="amount" value="'.$_SESSION[$nr.'_2'].'" required /></td></tr>
					<tr><td></td><td><input type="submit" name="edit_amount" value="Change" /></td></tr>
				</table>
			</form>
		</div>
		';

        // po kliknięciu "Change" przechodzimy dalej

        if(isset($_POST['edit_amount']) && !empty($_GET['nr']))
        {
            // idiotproof: jeśli podana ilość jest mniejsza od 1 to nie zmienia ilości w koszyku.

            $ile = $_POST['amount'];
            if($ile < 1)
            {
                echo "<script>window.location.href='koszyk.php';</script>";
                exit();
            }

            // limit: jeśli podana ilość jest większa od maksymalnej, ustala ilość w koszyku na maksymalną

            if($ile > $row['amount'])
            {
                $ile = $row['amount'];
            }

            $_SESSION[$nr.'_2'] = $ile;
            $_SESSION[$nr.'_6'] = $_SESSION[$nr.'_5'] * $ile;
            echo "<script>window.location.href='koszyk.php';</script>";
            exit();
        }
    }
}

// showCard() wyświetla zawartość koszyka
// można usuwać odpowiednie produkty, zmieniać ich ilość, czyścić koszyk i złożyć zamówienie

function showCard()
{
    // tworzymy zmienną sumy koszyka

    $suma = 0;

    echo '<div class="colskrol"><h1 class="naglowek">Koszyk</h1><center>';

    // jeśli jest chociaż jeden produkt w koszyku to go wyświetla
    // jeśli nie - funkcja wyrzuca odpowiedni komunikat

    if(isset($_SESSION['count']))
    {
        echo '<table><tr><th class="te"></th><th class="tb">name</th><th class="tb">ilość</th><th class="tb">product price</th><th class="tb">product total</th></tr>';
        $x = 1;
        while($x <= $_SESSION['count'])
        {
            // doliczamy cenę danych produktów do sumy koszyka
            $suma += $_SESSION[$x.'_6'];
            echo '
				<tr>				
					<td class="te"><img src="data:image/jpeg;base64,'.base64_encode($_SESSION[$x.'_7']).'" height="80"/></td>
					<td class="tdane"><b>'.$_SESSION[$x.'_4'].'</b></td>
					<td class="tdane">'.$_SESSION[$x.'_2'].'</td>
					<td class="tdane">'.$_SESSION[$x.'_5'].' PLN</td>
					<td class="tdane">'.$_SESSION[$x.'_6'].' PLN</td>
					<td class="tdedytuj"><a href="koszyk.php?fun=edit&nr='.$_SESSION[$x.'_0'].'"><b>ilość</b></a></td>
					<td class="tdane"><a href="koszyk.php?fun=remove&nr='.$_SESSION[$x.'_0'].'"><b>usuń</b></a></td>
				</tr>
			';
            $x++;
        }

        echo '
			<tr>	
				<td class="te"></td>
				<td class="te"></td>
				<td class="te"></td>
				<td class="tn"><b>total</b></td>
				<td class="tn">'.$suma.' PLN</td>
				<td class="tdedytuj"><a href="koszyk.php?fun=order"><b>zamów</b></a></td>
				<td class="tn"><a href="koszyk.php?fun=clear"><b>wyczyść</b></a></td>
				<td></td><td></td>			
			</tr></table></center><br>';

        // wywołanie EditAmount() w przypadku kliknięcia edit przy danym produkcie

        if(isset($_GET['fun']) && $_GET['fun'] == 'edit')
        {
            EditAmount();
        }
        echo '</div>';
    }else{
        echo 'Brak produktów w koszyku</center></div>';
    }
}

echo '<div class="row">';

if(isset($_GET['availability']) && !empty($_GET['availability']))
{
    $iddp = $_GET['availability'];
    addToCard($iddp);
}else{
    addToCard(0);
}

showCard();

if(isset($_GET['fun']) && $_GET['fun'] == 'remove')
{
    removeFromCard();
}

if(isset($_GET['availability']) && !empty($_GET['availability']))
{
    echo '<p style="text-align: center"><a href="produkt_pokaz.php?id='.$iddp.'">back to product</p>';
}

if(isset($_GET['fun']) && $_GET['fun'] == 'clear')
{
    session_destroy();
    echo "<script>window.location.href='koszyk.php';</script>";
    exit();
}

if(isset($_GET['fun']) && $_GET['fun'] == 'order')
{
    session_destroy();
    echo "<script>window.location.href='potwierdzenie.php';</script>";
    exit();
}

echo '</div>';
echo '<p style="text-align: center; padding-top: 32px;"><a href="sklep.php">wróć</a></p>';

?>