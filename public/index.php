<?php
require_once '../src/init.php';

$request = str_replace($config['urls']['baseUrl'],'',$_SERVER['REQUEST_URI']);
$request = strtok($request, '?');

$templates = new League\Plates\Engine(TEMPLATE_DIR);


switch ($request) {
    case '/':
    case '/etusivu':
        echo $templates->render('etusivu');
        break;

    case '/lisaa':
        if (isset($_POST['laheta'])) {
            $formdata = siistiTiedot($_POST);
            require_once CONTROLLER_DIR . 'tuloslaskelma.php';
            $tulos = tarkistaTiedot($formdata);
            if ($tulos['status'] == "200") {
                echo "Tiedot lisätty yrityksen $_POST[nimi] nimellä.";
                break;
            }
            echo $templates ->render('lisaa', ['formdata' => $formdata, 'error' => $tulos['error']]);
            break;
            /*require_once MODEL_DIR . 'lisaa.php';
            $lisaa = lisaaTiedot($_POST['nimi'], $_POST['liikevaihto'], $_POST['materiaalit'],
            $_POST['henkilosto'], $_POST['poistot'], $_POST['muutkulut'], $_POST['rahoitus'],
            $_POST['verot'], $_POST['kokonaismaara'], $_POST['osakehinta'], $_POST['sijoitus']);
            echo "Tiedot lisätty yrityksen $_POST[nimi] nimellä."; break; */
        } else {
            echo $templates->render('lisaa', ['formdata' => [], 'error' =>[]]);
            break;
        }
    
    case '/lisaa_tili':
        if (isset($_POST['laheta'])) {
            $formdata = siistiTiedot($_POST);
            require_once CONTROLLER_DIR . 'tili.php'; #lisaa_tili tk-funktio
            $tulos = lisaaTili($formdata);
            if ($tulos['status'] == '200') {
            echo $templates->render('tili_luotu', ['formdata' => $formdata]);
            }
            echo $templates ->render('lisaa_tili', ['formdata' => $formdata, 'error' => $tulos['error']]);
    
        } else {
            echo $templates ->render('lisaa_tili', ['formdata' => [], 'error' => []]);
        }
        
    case '/tulosta':
        require_once MODEL_DIR . 'tulosta.php';
        $hae=haeTiedot();
        echo $templates->render('tulosta', ['hae' => $hae]);
        break;

    case '/notfound':
        echo $templates->render('notfound');
        break;
    
    default:
        echo $templates->render('notfound');

    }

?>



