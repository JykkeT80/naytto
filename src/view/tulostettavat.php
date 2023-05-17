<?php $this->layout('template', ['title' => 'Tallennetut yritykset']); 

require_once MODEL_DIR . 'tulosta.php';
echo "Tallentamasi yritykset: <br>";
$yritykset=haeYritykset();
foreach ($yritykset as $y) {
?>
<form action="" method="POST">
<input type="checkbox" name="check[]" value=<?php $y; ?>><?php echo "$y[nimi]"; ?><br>
<?php

}
?>
<br>
<p> Valitse yritykset joiden sijoitustietoja haluat vertailla ja paina tulosta. </p>
<form action="" method="POST">
<input type="submit" name="tulosta" value="Tulosta">
</form>

<p>Voit halutessasi poistaa valitsemasi yrityksen tiedot painamalla poista </p>

<form action="" method="POST">
<input type="submit" name="poista" value="Poista">
</form>

