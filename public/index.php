<?php
session_start();
require_once '../src/init.php';

$request = str_replace($config['urls']['baseUrl'],'',$_SERVER['REQUEST_URI']);
$request = strtok($request, '?');

$templates = new League\Plates\Engine(TEMPLATE_DIR);

switch ($request) {
    case '/':
    case '/etusivu':
        echo $templates->render('etusivu');
        break;

    case "/kirjaudu":
        if (isset($_POST['laheta'])) {
            require_once CONTROLLER_DIR . 'kirjaudu.php';
            if (tarkistaKirjautuminen($_POST['email'],$_POST['salasana'])) {
                require_once MODEL_DIR . 'henkilo.php';
                $user = haeHenkilo($_POST['email']);
                if ($user['vahvistettu']) {
                    session_regenerate_id();
                    $_SESSION['user'] = $user['email'];
                    header("Location: " . $config['urls']['baseUrl']);
                } else {
                    echo $templates->render('kirjaudu', [ 'error' => ['virhe' => 'Tili on vahvistamatta! Ole hyvä, ja vahvista tili sähköpostissa olevalla linkillä.']]);
                }
            } else {
                echo $templates->render('kirjaudu', [ 'error' => ['virhe' => 'Väärä käyttäjätunnus tai salasana!']]);
            }
        } else {
            echo $templates->render('kirjaudu', [ 'error' => []]);
        }
        break;
        
    case "/logout":
        require_once CONTROLLER_DIR . 'kirjaudu.php';
        logout();
        header("Location: " . $config['urls']['baseUrl']);
        break;

    case '/lisaa':
        if (isset($_SESSION['user'])) {
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
            
            } else {
                echo $templates->render('lisaa', ['formdata' => [], 'error' =>[]]);
                break;
            }
        } else {
            echo $templates->render('kirjautumaton');
        }
        break;

    case '/tallennusok':
        echo $templates->render('tallennusok');
        break;
            
    case '/lisaa_tili':
        if (isset($_POST['laheta'])) {
            $formdata = siistiTiedot($_POST);
            require_once CONTROLLER_DIR . 'tili.php';
            $tulos = lisaaTili($formdata,$config['urls']['baseUrl']);
            if ($tulos['status'] == '200') {
                echo $templates->render('tili_luotu', ['formdata' => $formdata]);
                break;
            }
            echo $templates->render('lisaa_tili', ['formdata' => $formdata, 'error' => $tulos['error']]);
            break;
        } else {
            echo $templates->render('lisaa_tili', ['formdata' => [], 'error' => []]);
            break;
        }

    case "/vahvista":
        if (isset($_GET['key'])) {
            $key = $_GET['key'];
            require_once MODEL_DIR . 'henkilo.php';
            if (vahvistaTili($key)) {
                echo $templates->render('tilin_aktivointi');
            } else {
            echo $templates->render('tilin_aktivointi_virhe');
            }
        } else {
            header("Location: " . $config['urls']['baseUrl']);
            }
        break;
    
    case "/tilaa_vaihtoavain":
        $formdata = siistiTiedot($_POST);
        if (isset($formdata['laheta'])) {    
            require_once MODEL_DIR . 'henkilo.php';
            $user = haeHenkilo($formdata['email']);
            if ($user) {
                require_once CONTROLLER_DIR . 'tili.php';
                $tulos = luoVaihtoavain($formdata['email'],$config['urls']['baseUrl']);
                if ($tulos['status'] == "200") {
                    echo $templates->render('vaihtoavaimen_lahetys');
                    break;
                }
                echo $templates->render('virhe');
                break;
            } else {
                echo $templates->render('vaihtoavaimen_lahetys');
                break;
            }
        
        } else {
            echo $templates->render('salasana_unohtunut');
        }
        break;       
    
    case '/tulosta':
        if (isset($_SESSION['user'])) {
            echo $templates->render('tulosta');
            break;
        } else {
            echo $templates ->render('kirjautumaton');
            break;
        }

    case '/yhteydenotto':
        # Kun lähetä-nappia on painettu yhteydenottolomakkeella, 
        # otetaan valmisteltavaksi lähetettävä viesti
        if (isset($_POST['laheta'])) {
            require_once CONTROLLER_DIR . 'viesti.php';
            require_once HELPERS_DIR . 'form.php';
            # Viestin siistiminen ylimääräisistä merkeistä
            $formdata = siistiTiedot($_POST);
            # Viestin ja email-osoitteen virhetarkistus
            $tulos = tarkistaViesti($formdata);
            # Viesti lähetetään, ellei sisältänyt virheitä
            if ($tulos['status'] == "200") {
            $email = getValue($formdata,'email');
            $viesti = getValue($formdata,'viesti');
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: ' .$email. "\r\n";
            $result = mail("kirsi.nykanen@edu.sasky.fi", "Palaute", $viesti, $headers);
            # Mikäli viestin lähetys ei onnistu, ilmoitetaan virheestä virhe-sivulla
            if(!$result) {
                header( "Location: virhe" );
                break;
            # Kun viestin lähetys onnistuu, ohjataan kiitos-sivulle 
            } else {
                header( "Location: kiitos" );
        }
            }
            # Mikäli viesti sisältää virheitä, ilmoitetaan niistä lomakkeella
            else
            echo $templates->render('yhteydenotto', ['formdata' => $formdata, 'error' => $tulos['error']]);
            break;
            }
        # Mikäli Lähetä-nappia ei olla painettu, renderöidään yhteydenotto-lomakesivu
        else {
            echo $templates->render('yhteydenotto', ['formdata' => [], 'error' => []]);
            break;
        }

    case '/kiitos':
        echo $templates->render('kiitos');
        break;

    case '/notfound':
        echo $templates->render('notfound');
        break;
    
    case "/reset":
        $resetkey = $_GET['key'];
        require_once MODEL_DIR . 'henkilo.php';
        $rivi = tarkistaVaihtoavain($resetkey);
        if ($rivi) {
            if ($rivi['aikaikkuna'] < 0) {
                echo $templates->render('reset_virhe');
                break;
            }
        } else {
            echo $templates->render('reset_virhe');
            break;
        }
  
        $formdata = siistiTiedot($_POST);
        if (isset($formdata['laheta'])) {
            require_once CONTROLLER_DIR . 'tili.php';
            $tulos = resetoiSalasana($formdata,$resetkey);
            if ($tulos['status'] == "200") {
            echo $templates->render('reset_valmis');
            break;
        }
        echo $templates->render('reset_lomake', ['error' => $tulos['error']]);
        break;
      
        } else {
            echo $templates->render('reset_lomake', ['error' => '']);
            break;
        }

    default:
        echo $templates->render('notfound');

    }

?>



