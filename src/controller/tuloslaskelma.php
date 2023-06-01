<?php

require_once HELPERS_DIR . 'DB.php';
require_once MODEL_DIR . 'tulosta.php';

function tarkistaTiedot ($formdata) {
    require_once (MODEL_DIR . 'lisaa.php');
    $error = [];

    if (!isset($formdata['nimi']) || !$formdata['nimi']) {
        $error['nimi'] = "Syötä yrityksen nimi.";
    } else {
        $tulos = haeTiedot();
        foreach ($tulos as $avain) {
            $arvo = $avain['nimi'];
            if (strtolower($formdata['nimi']) == strtolower($arvo)) {
                $error['nimi'] = "Yrityksen tiedot ovat jo tietokannassa.";
            }
        }
    }

    #liikevaihto
    if (!isset($formdata['liikevaihto']) AND $formdata['liikevaihto'] !=0 || !$formdata['liikevaihto']) {
        $error['liikevaihto'] = "Syötä yrityksen liikevaihto.";
    } else if ($formdata['liikevaihto'] < "0") {
        $error['liikevaihto'] = "Syötä liikevaihto positiivisena numerona.";
    } else {
        $formdata['liikevaihto'] = $formdata['liikevaihto'];
    }

    #materiaalit
    if (!isset($formdata['materiaalit']) AND $formdata['materiaalit'] != 0 || !$formdata['materiaalit']) {
        $error['materiaalit'] = "Syötä yrityksen materiaalikulut.";
    } else if ($formdata['materiaalit'] < "0") {
        $error['materiaalit'] = "Syötä materiaalikulut suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['materiaalit'] = $formdata['materiaalit'];
    }
   
    #henkilosto
    if (!isset($formdata['henkilosto']) AND $formdata['henkilosto'] != 0|| !$formdata['henkilosto']) {
        $error['henkilosto'] = "Syötä yrityksen henkilöstökulut.";
    } else if ($formdata['henkilosto'] < "0") {
        $error['henkilosto'] = "Syötä henkilöstökulut suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['henkilosto'] = $formdata['henkilosto'];
    }

    #poistot
    if (!isset($formdata['poistot']) AND $formdata['poistot'] != 0|| !$formdata['poistot']) {
        $error['poistot'] = "Syötä yrityksen poistot.";
    } else if ($formdata['poistot'] < "0") {
        $error['poistot'] = "Syötä poistot suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['poistot'] = $formdata['poistot'];
    }

    #muutkulut
    if (!isset($formdata['muutkulut']) AND $formdata['muutkulut'] != 0 || !$formdata['muutkulut']) {
        $error['muutkulut'] = "Syötä yrityksen muut kulut.";
    } else if ($formdata['muutkulut'] < "0") {
        $error['muutkulut'] = "Syötä muut kulut suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['muutkulut'] = $formdata['muutkulut'];
    }

    #rahoitus
    if (!isset($formdata['rahoitus']) AND $formdata['rahoitus'] != 0 || !$formdata['rahoitus']) {
        $error['rahoitus'] = "Syötä yrityksen rahoituskulut.";
    } else if ($formdata['rahoitus'] < "0") {
        $error['rahoitus'] = "Syötä rahoituskulut suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['rahoitus'] = $formdata['rahoitus'];
    }

    #verot
    if (!isset($formdata['verot']) AND $formdata['verot'] != 0 || !$formdata['verot']) {
        $error['verot'] = "Syötä yrityksen verot.";
    } else if ($formdata['verot'] < "0" ) {
        $error['verot'] = "Syötä verot suurempana tai yhtäsuurena kuin nolla";
    } else {
        $formdata['verot'] = $formdata['verot'];
    }

    #kokonaismaara
    if (!isset($formdata['kokonaismaara']) || !$formdata['kokonaismaara']) {
        $error['kokonaismaara'] = "Syötä osakkeiden lukumäärä.";
    }

    #osakkeen hinta
    if (!isset($formdata['osakehinta']) || !$formdata['osakehinta']) {
        $error['osakehinta'] = "Syötä osakkeen hinta.";
    } 

    #sijoitus
    if (!isset($formdata['sijoitus']) || !$formdata['sijoitus']) {
        $error['sijoitus'] = "Syötä sijoitettava summa.";
    } 
    
    if (!$error) {
        $nimi = $formdata['nimi'];
        $liikevaihto = $formdata['liikevaihto'];
        $materiaalit = $formdata['materiaalit'];
        $henkilosto = $formdata['henkilosto'];
        $poistot = $formdata['poistot'];
        $muutkulut = $formdata['muutkulut'];
        $rahoitus = $formdata['rahoitus'];
        $verot = $formdata['verot'];
        $kokonaismaara = $formdata['kokonaismaara'];
        $osakehinta = $formdata['osakehinta'];
        $sijoitus = $formdata['sijoitus'];

        $idyritys = lisaaTiedot($nimi, $liikevaihto, $materiaalit, $henkilosto, $poistot, $muutkulut, 
        $rahoitus, $verot, $kokonaismaara, $osakehinta, $sijoitus);

        if ($idyritys) {
            return [
                "status" => 200,
                "id" => $idyritys,
                "data" => $formdata
            ];
        }else {
            return [
                "status" => 500,
                "data" => $formdata
            ];
        }
    }else {
        return [
            "status" => 400,
            "data"   => $formdata,
            "error"  => $error
        ];
    }
}
?>