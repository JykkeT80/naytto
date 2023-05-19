<?php $this->layout('template', ['title' => 'Tulosta tiedot']); ?>

<h1>Tietojen tulostus tietokannasta</h1>

<p>Tilinpäätöstiedot ja sijoitustiedot</p>

<div class='tulosta'>
<?php
require_once MODEL_DIR . 'funktiot.php'; #model vai controller???
require_once MODEL_DIR . 'tulosta.php';

echo "Valitse vertailtavat yritykset";

$firmat = haeTiedot();
?>

<form method="post" action="">
    <?php
    foreach ($firmat as $firma) { #checkbox täpätyt listana 'nimi[]' jotta toimii foreach loopissa.
        echo "<input type='checkbox' name='nimi[]' value='$firma[nimi]'> $firma[nimi] <br>"; 
    }
    ?>
<input type="submit" value="Tulosta" name="submit">
</form>

<?php
echo "<br>";

$nimet = array();
if (isset($_POST['submit'])) { #nappia painettu
    if ($_POST['nimi']) {   #jos ruutu on täpätty
        foreach ($_POST['nimi'] as $yritys) {
            $valitut = haeYritys($yritys);
        
            $osTuottoLista = array();  #kasataan kaikkien yritysten ekat osaketuotot PER OSAKE
            $osTuotto€ = array();
            $osTuottoPros = array();
            $maara = array();
        
            foreach ($valitut as $arvo) {
                $liikevoitto = liikevoitto($arvo['liikevaihto'], $arvo['materiaalit'], $arvo['henkilosto'], $arvo['poistot'], $arvo['muutkulut']);
                $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $arvo['rahoitus']);
                $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $arvo['verot']);
                $osaketuotto = osaketuotto($tilikaudenVoitto, $arvo['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
                array_push($osTuottoLista,$osaketuotto);
                $omatOsakkeetAlussa = osakkeetAlussa($arvo['sijoitus'], $arvo['osakehinta']); #sijoiitus/osakehinta
                $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $arvo['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
                $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
                $tuottoPros = $tulos[1]; #toinen indeksi
                array_push($osTuotto€, $tuotto€); #eurotuotto listalle
                array_push($osTuottoPros, $tuottoPros); #%tuotto listalle
                $uudetOsakkeet = tuottoVuosittain($tuotto€, $arvo['osakehinta']); #lasketaan uusien osakkeiden määrä
                array_push($maara, $uudetOsakkeet); #lisätään uusien osakkeiden määrä listalle
                $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä   
            }
            
            echo "TIEDOT OSAKKEISTA";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakkeiden kokonaismäärä kpl</td>";
            foreach ($valitut as $arvo) {
            echo "<td> $arvo[kokonaismaara] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakkeen hinta €/osake</td>";
            foreach ($valitut as $arvo) {
            echo "<td> $arvo[osakehinta] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Osakketuotto €/osake</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
            echo "<td>" . ROUND($osTuottoLista[$i],2) . "</td>";
            } 
            echo "</tr>";
            echo "</table>";
            echo "<br>";
        
            echo "SIJOITUSLASKURI";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Sijoitettava summa €</td>";
            foreach ($valitut as $arvo) {
            echo "<td>$arvo[sijoitus] </td>";
            } 
            echo "</tr>";

            echo "<tr>";
            echo "<td>Sijlituksella saadut osakkeet kpl</td>";
            foreach ($valitut as $arvo) {
            echo "<td>" . ROUND($arvo['sijoitus']/$arvo['osakehinta'],2) . "</td>";
            } 
            echo "</tr>";
            echo "</table>";
            echo "<br>";

            echo "SIJOITETUN PÄÄOMAN TUOTTO";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            foreach ($valitut as $arvo) {
            echo "<th> $arvo[nimi] </th>"; 
            }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Tuotto €</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
                echo "<td>" . ROUND($osTuotto€[$i],2) . "</td>";
                }
            echo "</tr>";

            echo "<tr>";
            echo "<td>Tuotto suhteessa sijoitukseen %</td>";
            for ($i=0; $i<COUNT($valitut); $i++) {
                echo "<td>" . ROUND($osTuottoPros[$i],2) . "</td>";
                }
            echo "</tr>";
            echo "</table>";
            echo "<br>";
            
        }
    }
}
    
?>
<!--
<form action="" method="POST">
<input type="submit" name="lisatiedot" value="Tulosta Lisätiedot">
</form>


<?php
/*
echo "<br>";
if ($_POST) {
    echo "TUOTTOJEN SIJOITUS VUOSI VUODELTA";
    echo "<hr>";
    echo "<br>";
    foreach ($tulos as $arvo) {
        $maara2 = array();
        $euro = array();
        $pros= array();

        $liikevoitto = liikevoitto($arvo['liikevaihto'], $arvo['materiaalit'], $arvo['henkilosto'], $arvo['poistot'], $arvo['muutkulut']);
        $voittoEnnenVeroja = voittoEnnenVeroja($liikevoitto, $arvo['rahoitus']);
        $tilikaudenVoitto = tilikaudenVoitto($voittoEnnenVeroja, $arvo['verot']);
        $osaketuotto = osaketuotto($tilikaudenVoitto, $arvo['kokonaismaara']); #tilikaudenvoitto/osakkeidenmaara
        array_push($osTuottoLista,$osaketuotto);
        $omatOsakkeetAlussa = osakkeetAlussa($arvo['sijoitus'], $arvo['osakehinta']); #sijoiitus/osakehinta
        $tulos = sipo($osaketuotto, $omatOsakkeetAlussa, $arvo['sijoitus']); #palauttaa listan $tulos jossa tuotto€ja tuottoPros
        $tuotto€ = $tulos[0]; #poimitaan listasta eka indeksi 
        $tuottoPros = $tulos[1]; #toinen indeksi
        $uudetOsakkeet = tuottoVuosittain($tuotto€, $arvo['osakehinta']); #lasketaan uusien osakkeiden määrä
        $yhtmaara = yhteismaara($omatOsakkeetAlussa, $uudetOsakkeet); #lasketaan sijoittajan osakkeiden yhteismäärä  

echo "<table>";
echo "<tr>";
    echo "<th>$arvo[nimi]</th>";
        for ($i=2; $i<6; $i++) {   #otsikot, nimi, vuodet
            echo "<th>$i. vuosi</th>"; 
        }
echo "</tr>";

echo "<tr>";
echo "<td>Edellisen vuoden tuotolla hankitut osakkeet (kpl)</td>";
for ($i=0; $i<4; $i++) {
    array_push($maara2, $uudetOsakkeet);
    echo "<td>" . ROUND($maara2[$i],2) . "</td>";
    $tulos = sipo($osaketuotto, $yhtmaara, $arvo['sijoitus']);
    array_push($euro, $tulos[0]);    # vai $tuotto€ = ROUND($tulos[0], 2); array_push($euro, $tuotto€);    
    array_push($pros, $tulos[1]);  #vai $tuottoPros= ROUND($tulos[1], 2); array_push($pros, $tuottoPros);
    $uudetOsakkeet = tuottoVuosittain($tulos[0], $arvo['osakehinta']); 
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