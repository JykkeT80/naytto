<?php $this->layout('template', ['title' => 'Yhteydenotto'])?>

<div class="esittely_teksti p">
<div>
<h1>Heräsikö kysymyksiä?</h1>
<br>
<p>Ota yhteyttä asiakaspalveluumme: </p>
<p>asiakaspalvelu@be22koodaajat.fi </p>
<p>tai p. 0600 123 456 </p>
<p>(ark klo 9-16, 1,20€/min + ppm)</p>
<br>
<p>Voit jättää palautteen myös</p>
<p>alla yhteydenottolomakkeella:</p>
<br>
</div>

<form method="post" action="yhteydenotto">
<div>
  <label>Sähköpostiosoite:</label>
  <input name="email" type="text" value="<?= getValue($formdata,'email')?>">
    <div class="error"><?= getValue($error,'email');?></div>
</div>
<div>
  <label>Viesti:</label>
  <textarea name="viesti" rows="8" cols="30" 
  value="<?=getValue($formdata,'viesti')?>"></textarea><div class="error"><?= getValue($error,'viesti');?></div>
</div>
<div>
  <input type="submit" name="laheta" value="Lähetä">
</div>
<!-- Lomakkeen tyhjennysnappi
<div>
  <input type="reset" name="laheta" value="Tyhjennä">
</div>-->
</form>

</div>