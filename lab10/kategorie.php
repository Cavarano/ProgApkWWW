<link rel="stylesheet" href="./css/admin.css">
<?php

// łączymy z bazą danych przez cfg.php
include('cfg.php');

// DodajKategorie() wyświetla formularz dzięki któremu możemy dodać dane kategorii do bazy danych
function DodajKategorie()
{
    global $link;

    echo '
    <div>
        <h1 class="naglowek"><b>Add category</b></h1>
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
			<table>
				<tr><td><b>name:</b></td><td><input type="text" size="24" name="name" required /></td></tr>
				<tr><td><b>parent_id:</b></td><td><input type="text" size="24" name="parent_id" value=0 required /></td></tr>
				<tr><td><b>submit:</b></td><td><input type="submit" name="add" value="Add" /></td></tr>
			</table>
		</form>
    </div>
    ';

    if(isset($_POST['add']))
    {
        $nazwa = $_POST['name'];
        $parent_id = $_POST['parent_id'];

        $query = "INSERT INTO categories (parent_id, name) VALUES ('$parent_id', '$nazwa')";
        $result = mysqli_query($link, $query);

        if($result)
        {
            echo "<script>window.location.href='kategorie.php';</script>";
            exit();
        }
        else
        {
            echo "<center>Błąd podczas dodawania kategorii: " . mysqli_error($link)."</center>";
        }
    }
}

// FormularzDoUsuwania() wyświetla formularz do usuwania kategorii
function FormularzDoUsuwania()
{
    echo '
    <div>
        <h1 class="naglowek"><b>Remove category<b/></h1>
            <form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <table>
                    <tr><td><b>id:</b></td><td><input type="text" size="24" name="id1" required /></td></tr>
                    <tr><td><b>submit:</b></td><td><input type="submit" name="remove" value="Remove" /></td></tr>
                </table>
            </form>
    </div>
    ';
}

// UsunKategorie($id) usuwa kategorię przy podaniu ID
function UsunKategorie($id)
{
    global $link;

    $query = "SELECT id FROM categories WHERE parent_id = '$id'";
    $result = mysqli_query($link, $query);
    if($result)
    {
        while($row = mysqli_fetch_array($result))
        {
            UsunKategorie($row['id']);
        }
    }

    $query1 = "DELETE FROM categories WHERE id = '$id' LIMIT 1";
    $result1 = mysqli_query($link, $query1);
    if(!$result1)
    {
        echo 'Error!';
    }
}

// EdytujKategorie() edytuje kategorię według danych podanych w formularzu
function EdytujKategorie()
{
    global $link;

    echo '
	<div>
		<h1 class="naglowek"><b>Change category<b/></h1>
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
			<table>
				<tr><td><b>id:</b></td><td><input type="text" size="24" name="id2" required /></td></tr>
				<tr><td><b>name:</b></td><td><input type="text" size="24" name="name" /></td></tr>
				<tr><td><b>parent_id:</b></td><td><input type="text" size="24" name="parent_id" /></td></tr>
				<tr><td><b>submit:</b></td><td><input type="submit" name="change" value="Change" /></td></tr>
			</table>
		</form>
	</div>
	';

    if(isset($_POST['change']))
    {
        $id = $_POST['id2'];
        $nazwa = $_POST['name'];
        $matka = $_POST['parent_id'];

        $query = "SELECT * FROM categories WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        if(is_null($row))
        {
            echo 'There is no category ID '.$id.'';
            exit();
        }

        $query = "UPDATE categories SET name = '$nazwa', parent_id = '$matka' WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($link, $query);
        if($result)
        {
            echo "<script>window.location.href='kategorie.php';</script>";
            exit();
        }
        else
        {
            echo "Error: ".mysqli_error($link)."";
        }
    }
}

// PokazKategorie($parent_id, $ile) wyświetla kategorie w strukturze drzewa
function PokazKategorie($parent_id = 0, $ile = 0)
{
    global $link;
    $query = "SELECT * FROM categories WHERE parent_id = '$parent_id'";
    $result = mysqli_query($link, $query);
    if($result){
        $brak = 0;
        while($row = mysqli_fetch_array($result))
        {
            $brak = 1;
            for($i=0; $i < $ile; $i++)
            {
                echo '<span style="color: #FFFFFF;">.</span>';
            }
            echo '<b><span style="color:#ffdb23; ">' .$row['id'].'</span> '.$row['name'].'</b><br>';
            PokazKategorie($row['id'], $ile + 1);
        }
        if($brak == 0 && $ile == 0)
        {
            echo "No categories...";
        }
    }
}

// wywołuje poprzedne funkcje po kolei, aby można było ich używać

echo '<h1 class="naglowek">Categories</h1>';
PokazKategorie();
echo '</p>';

DodajKategorie();

FormularzDoUsuwania();
if(isset($_POST['remove']))
{
    $id = $_POST['id1'];
    UsunKategorie($id);
    echo "<script>window.location.href='kategorie.php';</script>";
    exit();
}

EdytujKategorie();

?>