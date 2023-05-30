<?php $this->layout('template', ['title' => 'Tili luotu']) ?>

<div class="otsikko_ja_teksti">
  <h1>Sinulle on luotu tili!</h1>
  <p>Sähköpostiisi (<?= getValue($formdata,'email') ?>)</p>
  <p>on lähetetty vahvistusviesti. </p>
  <p>Käy vahvistamassa sähköpostiosoitteesi</p> 
  <p>klikkaamalla viestissä olevaa linkkiä</p>
  <br>
</div>