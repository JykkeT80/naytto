<?php

if (isset($_POST['laheta'])) {
    $data = siistiTiedot($_POST['laheta']);
    if (is_numeric($data)) {
        lisaaT();
        else 
        echo "error message";
    }
}