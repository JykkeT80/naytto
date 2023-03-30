<?php

require_once HELPERS_DIR . 'DB.php';
function haeTiedot() {
    return DB::run('SELECT * FROM sijoitus;')->fetchAll();
}

?>