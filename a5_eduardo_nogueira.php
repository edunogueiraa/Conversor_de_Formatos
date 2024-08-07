<?php

/*Integrantes
    Eduardo Nogueira da Silva
    Pedro Henrique Dutra
*/

if (sizeof($argv) != 2) {
    echo "Uso: php converter.php (arquivo.json | arquivo.xml)\n";
    exit(1);
}

if (pathinfo($argv[1], PATHINFO_EXTENSION) == 'json') {

}