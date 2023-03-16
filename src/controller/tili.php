<?php

# tarkistetaan lomaakkeella syötettyjen tietojen oikeellisuus
# email oikeaa muotoa ja salasanat täsmää

function lisaaTili ($formdata) {
    #tuodaaan uusi_tili funktiot, joilla voidaan lisätä tili tk:aan
    require_once  (MODEL_DIR . 'uusi_tili.php');
    #alustetaan virhetaulukko, palautuu tyhjänä tai virheillä täytettynä
    $error = [];

    #lomaketietojen tarkistus, jos muoto ei ole oikea, virhelistaan virhekuvaus
    #jos kaikki läpi virhelista lopus tyhjä

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

        // Lisätään henkilö tietokantaan. Jos lisäys onnistui,
        // tulee palautusarvona lisätyn henkilön id-tunniste.
        $idhenkilo = lisaaTili($email,$salasana);
        #lisääTIlil jos ei salasanaa vaan kertäkayttötunnus?? vai kuinka

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

        if ($id) {
            return [
                "status" => 200,
                "id" => $id,
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