<?php

function tarkistaTiedot ($formdata) {
    require_once (MODEL_DIR . 'lisaa.php');
    $error = [];

    #nimi, pitää vielä tehdä funktio, joka estää saman yrityksen nimen syöttämisen toistamiseen. 
    if (!isset($formdata['nimi']) || !$formdata['nimi']) {
        $error['nimi'] = "Syötä yrityksen nimi.";
    } /*else {
        if (!preg_match("/^[- '\p{L}]+$/u", $formdata['nimi'])) {
            $error['nimi'] = "Syötä yrityksen nimi ilman erikoismerkkejä.";
        } #else {haeTiedotNimella ($formdata['nimi'])}
    }*/

    #liikevaihto
    if (!isset($formdata['liikevaihto']) || !$formdata['liikevaihto']) {
        $error['liikevaihto'] = "Syötä yrityksen liikevaihto.";
    } else {
        if (!is_numeric($formadata['liikevaihto']) || !$formadata['liikevaihto']>0) {
            $error['liikevaihto'] = "Syötä liikevaihto positiivisena numerona.";
        }
    }

    #materiaalit
    if (!isset($formdata['materiaalit']) || !$formdata['materiaalit']) {
        $error['materiaalit'] = "Syötä yrityksen materiaalikulut.";
    } else {
        if (!is_numeric($formadata['materiaalit']) || !$formadata['materiaalit']>=0) {
            $error['materiaalit'] = "Syötä materiaalikulut suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #henkilosto
    if (!isset($formdata['henkilosto']) || !$formdata['henkilosto']) {
        $error['henkilosto'] = "Syötä yrityksen henkilöstökulut.";
    } else {
        if (!is_numeric($formadata['henkilosto']) || !$formadata['henkilosto']>=0) {
            $error['henkilosto'] = "Syötä henkilöstökulut suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #poistot
    if (!isset($formdata['poistot']) || !$formdata['poistot']) {
        $error['poistot'] = "Syötä yrityksen poistot.";
    } else {
        if (!is_numeric($formdata['poistot']) || !$formdata['poistot']>=0) {
            $error['poistot'] = "Syötä poistot suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #muutkulut
    if (!isset($formdata['muutkulut']) || !$formdata['muutkulut']) {
        $error['muutkulut'] = "Syötä yrityksen muut kulut.";
    } else {
        if (!is_numeric($formdata['muutkulut']) || !$formdata['muutkulut']>=0) {
            $error['muutkulut'] = "Syötä muut kulut suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #rahoitus
    if (!isset($formdata['rahoitus']) || !$formdata['rahoitus']) {
        $error['rahoitus'] = "Syötä yrityksen rahoituskulut.";
    } else {
        if (!is_numeric($formdata['rahoitus']) || !$formdata['rahoitus']>=0) {
            $error['rahoitus'] = "Syötä rahoituskulut suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #verot
    if (!isset($formdata['verot']) || !$formdata['verot']) {
        $error['verot'] = "Syötä yrityksen rahoituskulut.";
    } else {
        if (!is_numeric($formdata['verot']) || !$formdata['verot']>=0) {
            $error['verot'] = "Syötä rahoituskulut suurempana tai yhtäsuurena kuin nolla";
        }
    }

    #kokonaismaara
    if (!isset($formdata['kokonaismaara']) || !$formdata['kokonaismaara']) {
        $error['kokonaismaara'] = "Syötä osakkeiden lukumäärä.";
    } else {
        if (!is_numeric($formdata['kokonaismaara']) || !$formdata['kokonaismaara']>0) {
            $error['kokonaismaara'] = "Syötä osakkeiden lukumäärä positiivisena lukuna.";
        }
    }

    #osakkeen hinta
    if (!isset($formdata['osakehinta']) || !$formdata['osakehinta']) {
        $error['osakehinta'] = "Syötä osakkeen hinta.";
    } else {
        if (!is_numeric($formdata['osakehinta']) || !$formdata['osakehinta']>0) {
            $error['osakehinta'] = "Syötä osakkeen hinta positiivisena lukuna.";
        }
    }
    
    #sijoitus
    if (!isset($formdata['sijoitus']) || !$formdata['sijoitus']) {
        $error['sijoitus'] = "Syötä osakkeisiin sijoitettava määrä.";
    } else {
        if (!is_numeric($formdata['sijoitus']) || !$formdata['sijoitus']>0) {
            $error['sijoitus'] = "Syötä sijoitettava määrä positiivisena lukuna.";
        }
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