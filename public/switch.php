<?php
# LISÄÄ TIEDOT KOHTA EI TOIMI LAINKAAN!
switch ($request) {
    case '/':
    case '/etusivu':
        echo $templates->render('etusivu');

    case '/lisaa':
        if (isset($_POST['laheta'])) {
            require_once MODEL_DIR . 'lisaa.php';
            $lisaa = lisaaTiedot($_POST['nimi'], $_POST['liikevaihto'], $_POST['materiaalit'],
            $_POST['henkilosto'], $_POST['poistot'], $_POST['muutkulut'], $_POST['rahoitus'],
            $_POST['verot'], $_POST['kokonaismaara'], $_POST['osakehinta'], $_POST['sijoitus']);
            echo "Tiedot lisätty yrityksen $_POST[nimi] nimellä.";
            break;
        } else {
            $templates->render('lisaa');
            break;
        }
    
    case '/lisaa_tili':
        if (isset($_POST['laheta'])) {
            $formdata = siistiTiedot($_POST);
            require_once CONTROLLER_DIR . 'tili.php'; #uusi_tili tk-funktio
            $tulos = lisaaTili($formdata);
            if ($tulos['status'] == '200') {
            echo $templates->render('tili_luotu', ['formdata' => $formdata]);
            }
            echo $templates ->render('lisaa_tili', ['formdata' => $formdata, 'error' => $tulos['error']]);
    
        } else {
            echo $templates ->render('lisaa_tili', ['formdata' => [], 'error' => []]);
        }
        
    case '/hae':
        require_once MODEL_DIR . 'hae.php';
        $hae=haeTiedot();
        echo $templates->render('hae', ['hae' => $hae]);
        break;

    case '/notfound':
        echo $templates->render('notfound');
        break;
    
    default:
        echo $templates->render('notfound');

    }
/*