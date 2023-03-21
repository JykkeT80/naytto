<?php $this->layout('template', ['title' => 'Hae tiedot']); ?>

<h1>Tietojen tulostus tietokannasta</h1>

<p>Tilinpäätöstiedot ja sijoitustiedot</p>

<div class='hae'>
<?php
require_once MODEL_DIR . 'funktiot.php'; #model vai controller???

$osTuottoLista = array();  #kasataan kaikkien yritysten ekat osaketuotot PER OSAKE
$osTuotto€ = array();
$osTuottoPros = array();
$maara = array();
foreach ($hae as $haku) {
    $pituus = COUNT($hae); #lasketaan montako yritystä tietokannassa
    $liikevoitto = liikevoitto($haku['liikevaihto'], $haku['materiaalit'], $haku['henkilosto'], $haku['poistot'], $haku['muutkulut']);
    $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $haku['rahoitus']);
    $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $haku['verot']);
    $osaketuotto = osaketuotto($tilikaudenVoitto, $haku['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
    array_push($osTuottoLista,$osaketuotto);
    $omatOsakkeetAlussa = osakkeetAlussa($haku['sijoitus'], $haku['osakehinta']); #sijoiitus/osakehinta
    $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $haku['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
    $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
    $tuottoPros = $tulos[1]; #toinen indeksi
    array_push($osTuotto€, $tuotto€); #eurotuotto listalle
    array_push($osTuottoPros, $tuottoPros); #%tuotto listalle
    $uudetOsakkeet = tuottoVuosittain($tuotto€, $haku['osakehinta']); #lasketaan uusien osakkeiden määrä
    array_push($maara, $uudetOsakkeet); #lisätään uusien osakkeiden määrä listalle
    $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä   
}
    echo "TIEDOT OSAKKEISTA";
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    foreach ($hae as $haku) {
    echo "<th> $haku[nimi] </th>"; 
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Osakkeiden kokonaismäärä kpl</td>";
    foreach ($hae as $haku) {
    echo "<td> $haku[kokonaismaara] </td>";
    } 
    echo "</tr>";

    echo "<tr>";
    echo "<td>Osakkeen hinta €/osake</td>";
    foreach ($hae as $haku) {
    echo "<td> $haku[osakehinta] </td>";
    } 
    echo "</tr>";

    echo "<tr>";
    echo "<td>Osakketuotto €/osake</td>";
    for ($i=0; $i<$pituus; $i++) {
    echo "<td>" . ROUND($osTuottoLista[$i],2) . "</td>";
    } 
    echo "</tr>";
    echo "</table>";
    echo "<br>";

    echo "SIJOITUSLASKURI";
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    foreach ($hae as $haku) {
    echo "<th> $haku[nimi] </th>"; 
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Sijoitettava summa €</td>";
    foreach ($hae as $haku) {
    echo "<td>$haku[sijoitus] </td>";
    } 
    echo "</tr>";

    echo "<tr>";
    echo "<td>Sijlituksella saadut osakkeet kpl</td>";
    foreach ($hae as $haku) {
    echo "<td>" . ROUND($haku['sijoitus']/$haku['osakehinta'],2) . "</td>";
    } 
    echo "</tr>";
    echo "</table>";
    echo "<br>";

    echo "SIJOITETUN PÄÄOMAN TUOTTO";
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    foreach ($hae as $haku) {
    echo "<th> $haku[nimi] </th>"; 
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Tuotto €</td>";
    for ($i=0; $i<$pituus; $i++) {
        echo "<td>" . ROUND($osTuotto€[$i],2) . "</td>";
        }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Tuotto suhteessa sijoitukseen %</td>";
    for ($i=0; $i<$pituus; $i++) {
        echo "<td>" . ROUND($osTuottoPros[$i],2) . "</td>";
        }
    echo "</tr>";
    echo "</table>";
    echo "<br>";
?>

<form action="" method="POST">
<input type="submit" name="lisatiedot" value="Tulosta Lisätiedot">
</form>

<?php

if ($_POST) {
    echo "TUOTTOJEN SIJOITUS VUOSI VUODELTA";
    echo "<br>";
    foreach ($hae as $haku) {
        $maara2 = array();
        $euro = array();
        $pros= array();

        $liikevoitto = liikevoitto($haku['liikevaihto'], $haku['materiaalit'], $haku['henkilosto'], $haku['poistot'], $haku['muutkulut']);
        $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $haku['rahoitus']);
        $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $haku['verot']);
        $osaketuotto = osaketuotto($tilikaudenVoitto, $haku['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
        array_push($osTuottoLista,$osaketuotto);
        $omatOsakkeetAlussa = osakkeetAlussa($haku['sijoitus'], $haku['osakehinta']); #sijoiitus/osakehinta
        $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $haku['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
        $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
        $tuottoPros = $tulos[1]; #toinen indeksi
        $uudetOsakkeet = tuottoVuosittain($tuotto€, $haku['osakehinta']); #lasketaan uusien osakkeiden määrä
        $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä  

echo "<table>";
echo "<tr>";
    echo "<th>$haku[nimi]</th>";
        for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
            echo "<th>$i. vuosi</th>"; 
        }
echo "</tr>";

echo "<tr>";
echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
for ($i=0; $i<4; $i++) {
    array_push($maara2, $uudetOsakkeet);
    echo "<td>" . ROUND($maara2[$i],2) . "</td>";
    $tulos = sipo($osaketuotto, $yhtmaara, $haku['sijoitus']);
    array_push($euro, $tulos[0]);    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
    array_push($pros, $tulos[1]);  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
    $uudetOsakkeet = tuottoVuosittain($tulos[0], $haku['osakehinta']); 
    $yhtmaara += $uudetOsakkeet;
}
echo "</tr>";

echo "<tr>";
echo "<td>Osakkeiden yhteismäärä (kpl)</td>"; 
$yht = $omatOsakkeetAlussa;
for ($i=0; $i<4; $i++) {
    $yht += $maara2[$i];
    echo "<td>" . ROUND($yht,2) . "</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Tuotto (€)</td>";
for ($i=0; $i<4; $i++) {
    echo "<td>" . ROUND($euro[$i],2) . "</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Tuotto suhteessa alkusijoitukseen (%)</td>";
for ($i=0; $i<4; $i++) {
    echo "<td>" . ROUND($pros[$i],2) . "</td>";
}
echo "</tr>";
echo "</table>"; 
}
}
?>


<?php
/*
$euro = array();
$pros= array();
    echo "<tr>";
    echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
    for ($i=0; $i<4; $i++) {
        echo "<td>$maara[$i]</td>";
        $tulos = sipo($osaketuotto, $yhtmaara, $haku['sijoitus']);
        array_push($euro, ROUND($tulos[0],2));    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
        array_push($pros, ROUND($tulos[1],2));  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
        $uudetOsakkeet = tuottoVuosittain($tulos[0], $haku['osakehinta']); 
        $yhtmaara += $uudetOsakkeet;
    }
    echo "</tr>"; 
    /*foreach ($hae as $haku) {
        echo "<table>";
        echo "<tr>";
        echo "<th>$haku[nimi]</th>";
            for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
                echo "<th>$i. vuosi</th>"; 
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
            for ($i=0; $i<4; $i++) {
                echo "<td>$maara[$i]</td>";
                $tulos = sipo($osaketuotto, $yhtmaara, $sijoitus);
                array_push($euro, ROUND($tulos[0],2));    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
                array_push($pros, ROUND($tulos[1],2));  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
                $uudetOsakkeet = tuottoVuosittain($tulos[0], $osakehinta); 
                $yhtmaara += $uudetOsakkeet;

            }
            echo "</tr>";
}}
     #array_push($maara, ROUND($uudetOsakkeet,2));

    /*
    #TÄSTÄ ALKAA
    $maara = array(); #uusien osakkeiden määrä
    echo "<table>";
    echo "<tr>";
    foreach ($hae as $haku) {
        echo "<th>$haku[nimi]</th>";
        for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
            echo "<th>$i. vuosi</th>"; 
        }
    }
    echo "</tr>";

    $maara= array();
    $euro =array();
    $pros = array();

    echo "<tr>";
    echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
    foreach ($hae as $haku) {
    for ($i=0; $i<4; $i++) {
    array_push($maara, ROUND(tuottoVuosittain($osTuotto€[$i], $haku['osakehinta']),2));  #lisätään listaan uusien osakkeiden määrä
    echo "<td>$maara[$i]</td>";
    $tulos = sipo($osaketuotto, $yhtmaara, $sijoitus); #lasketaan uudet tuottoluvut osakkeiden määrän muututtua
    $uudetOsakkeet = tuottoVuosittain($tulos[0], $osakehinta); #lasketaan uusien osakkeiden määrä
    $yhtmaara += $uudetOsakkeet;  #sijoittajan osakkeiden yhteistmäärä
    }}
    /*
    }
    foreach ($hae as $haku) {
        echo "<th>$haku[nimi]</th>";
        for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
            echo "<th>$i. vuosi</th>"; 
        }
    }
    echo "</tr>";

    $maara= array();
    $euro =array();
    $pros = array();

    echo "<tr>";
    echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
    foreach ($hae as $haku) {
    for ($i=0; $i<4; $i++) {
    array_push($maara, ROUND(tuottoVuosittain($osTuotto€[$i], $haku['osakehinta']),2));  #lisätään listaan uusien osakkeiden määrä
    echo "<td>$maara[$i]</td>";
    $tulos = sipo($osaketuotto, $yhtmaara, $sijoitus); #lasketaan uudet tuottoluvut osakkeiden määrän muututtua
    $uudetOsakkeet = tuottoVuosittain($tulos[0], $osakehinta); #lasketaan uusien osakkeiden määrä
    $yhtmaara += $uudetOsakkeet;  #sijoittajan osakkeiden yhteistmäärä
    }
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Osakkeiden yhteismäärä (kpl)</td>";
    foreach ($hae as $haku) {
    $yht = $osakkeetAlussa;
    for ($i=0; $i<4; $i++) {  #iteroidaan uusien osakkeiden määärä vuosittain
    $yht += $maara[$i];
    echo "<td>" . ROUND($yht,2). "</td>";
    }
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Tuotto (€)</td>"; #iteroidaan tuotto€ vuosittain
    for ($i=0; $i<4; $i++) {
    echo "<td>$osTuotto€[$i]</td>";
    }
    echo "</tr>";

    echo "<tr>";
    echo "<td>Tuotto suhteessa alkusijoitukseen (%)</td>";
    for ($i=0; $i<4; $i++) {  #iteroidaan tuottoPros vuosittain
    echo "<td>$osTuottoPros[$i]</td>";
    }
    echo "</tr>";
    echo "</table>"; 
    echo "<br>";

/*

echo "<table>";
echo "<tr>";
echo "<th></th>";
for ($i=2; $i<6; $i++) {
    echo "<th>$i. vuosi</th>"; 
}
echo "</tr>";

$maara = array();
$euro = array();
$pros= array();
echo "<tr>";
echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
for ($i=0; $i<4; $i++) {
    array_push($maara, ROUND($uudetOsakkeet,2));
    echo "<td>$maara[$i]</td>";
    $tulos = sipo($osaketuotto, $yhtmaara, $sijoitus);
    array_push($euro, ROUND($tulos[0],2));    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
    array_push($pros, ROUND($tulos[1],2));  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
    $uudetOsakkeet = tuottoVuosittain($tulos[0], $osakehinta); 
    $yhtmaara += $uudetOsakkeet;
}
"</tr>";

echo "<tr>";
echo "<td>Osakkeiden yhteismäärä (kpl)</td>"; 
$yht = $osakkeetAlussa;
for ($i=0; $i<4; $i++) {
    $yht += $maara[$i];
    echo "<td>$yht</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Tuotto (€)</td>";
for ($i=0; $i<4; $i++) {
    echo "<td>$euro[$i]</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td>Tuotto suhteessa alkusijoitukseen (%)</td>";
for ($i=0; $i<4; $i++) {
    echo "<td>$pros[$i]</td>";
}
echo "</tr>";
echo "</table>"; 
*/
?>

