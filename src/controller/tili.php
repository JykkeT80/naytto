<?php

function lisaaTili ($formdata) {
    require_once  (MODEL_DIR . 'lisaa_tili.php');
    $error = [];

    #lomaketietojen tarkistus, jos muoto ei ole oikea, virhelistaan virhekuvaus
    #jos kaikki läpi virhelista lopus tyhjä
    if (!isset($formadata['nimi']) || !$formadata['nimi']) {
        $error['nimi'] = "Anna nimesi.";
    } else {
        if (!preg_match("/^[- '\p{L}]+$/u", $formadata['nimi'])) {
            $error['nimi'] = "Syötä nimesi ilman erikoismerkkejä";
        }
    }

    //tarkistetaan sposti määritelty ja oikea muoto
    if (!isset($formdata['email']) || !$formdata['email']) {
        $error['email'] = "Anna sähköpostiosoitteesi.";
    } else {
        if (!filter_var($formdata['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Sähköpostiosoite on virheellisessä muodossa";
        }   else {
            if (haeTiliSahkopostilla($formdata['email'])) {
                $error['email'] = "Sähköpostiosoite on jo käytössä.";
            }         
        }
    }

    // tark salasanat annettu ja keskennään samat JOS KAKSI SALASANAA!
    if (isset($formadata['salasana1']) && $formadata['salasana1'] &&
        isset($formadata['salasana2']) && $formadata['salasana2']) {
        if ($formadata['salasana1'] != $formdata['salasana2']) {
            $error['salasana'] = "Salasanasi eivät täsmää";
        } 
    }else {
        $error['salasana'] = "Syötä salasanasi kahteen kertaan.";
    }

    // Lisätään tiedot tietokantaan, jos edellä syötettyissä
    // tiedoissa ei ollut virheitä eli error-taulukosta ei
    // löydy virhetekstejä.
    if (!$error) {
        // Haetaan lomakkeen tiedot omiin muuttujiinsa.
        // Salataan salasana myös samalla
        $email = $formdata['email'];
        $salasana = password_hash($formadata['salasana1'], PASSWORD_DEFAULT);
        $nimi = $formadata['nimi'];

        // Lisätään henkilö tietokantaan. Jos lisäys onnistui,
        // tulee palautusarvona lisätyn henkilön id-tunniste.
        $idtili = lisaaTili($nimi,$email,$salasana);

        // Palautetaan JSON-tyyppinen taulukko, jossa:
        //  status   = Koodi, joka kertoo lisäyksen onnistumisen.
        //             Hyvin samankaltainen kuin HTTP-protokollan
        //             vastauskoodi.
        //             200 = OK
        //             400 = Bad Request
        //             500 = Internal Server Error
        //  id       = Lisätyn rivin id-tunniste.
        //  formdata = Lisättävän henkilön lomakedata. Sama, mitä
        //             annettiin syötteenä.
        //  error    = Taulukko, jossa on lomaketarkistuksessa
        //             esille tulleet virheet.

        // Tarkistetaan onnistuiko henkilön tietojen lisääminen.
        // Jos idhenkilo-muuttujassa on positiivinen arvo,
        // onnistui rivin lisääminen. Muuten liäämisessä ilmeni
        // ongelma.

        if ($idtili) {
            return [
                "status" => 200,
                "id" => $idtili,
                "data" => $formdata
            ];
        }else {
            return [
                "status" => 500,
                "data" => $formdata
            ];
        }
    }else {
        // Lomaketietojen tarkistuksessa ilmeni virheitä.
        return [
            "status" => 400,
            "data"   => $formdata,
            "error"  => $error
        ];
    }
}
        
?>