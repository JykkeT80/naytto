
<?php

require_once HELPERS_DIR . 'DB.php';
function haeTiedot($yritys) {
    return DB::run('SELECT nimi, liikevaihto, materiaalit, henkilosto, poistot, muutkulut, 
    rahoitus, verot, kokonaismaara, osakehinta, sijoitus FROM sijoitus WHERE lisaaja = "'.$_SESSION['user'].'" AND nimi = "'.$yritys.'";')->fetchAll();
}

function haeYritykset() {
    return DB::run('SELECT nimi FROM sijoitus WHERE lisaaja = "'.$_SESSION['user'].'";')->fetchAll();
}
//AND nimi = "'.$yritys.'"
?>
