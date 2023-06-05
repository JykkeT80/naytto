<?php $this->layout('template', ['title' => 'Kirjaudu'])?>

<div class="kirjautuminen">
<form action="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" method="POST">
<br>
<h1>Kirjaudu sisään</h1>
  <div>
    <label>Sähköposti:</label>
    <input type="text" name="email">
  </div>
  <div>
    <label>Salasana:</label>
    <input type="password" name="salasana">
  </div>
  <div class="error"><?= getValue($error,'virhe'); ?></div>
  <div>
    <input type="submit" name="laheta" value="Kirjaudu">
  </div>
  <div class="info">
  <a href="tilaa_vaihtoavain">Salasana unohtunut?</a>
  </div>
  <div class="info">
  <a href="lisaa_tili">Luo uusi tili</a>
  </div>
  </form>

<div class="yhteys_teksti">
  <p>Ongelmia kirjautumisessa? </p>
  <p>Ota yhteyttä asiakaspalveluumme: </p>
  <p>asiakaspalvelu@be22koodaajat.fi </p>
  <p>tai p. 0600 123 456 </p>
  <p>(ark klo 9-16, 1,20€/min + ppm)</p>
  <br>
</div>

</div>



  


