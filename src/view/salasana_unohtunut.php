<?php $this->layout('template', ['title' => 'Salasana unohtunut?']) ?>

<div class="otsikko_ja_teksti">
<form action="" method="POST">
<h1>Unohtunut salasana?</h1>
<br>
<p>Ei hätää, voit vaihtaa unohtuneen </p>
<p>salasanan tilaamalla </p> 
<p>vahvistuslinkin sähköpostiisi.</p>

  <div>
    <label for="email">Sähköposti:</label>
    <input id="email" type="email" name="email">
  </div>
  <div>
    <input type="submit" name="laheta" value="Lähetä">
  </div>
</form>
</div>