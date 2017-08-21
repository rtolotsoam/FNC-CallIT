<?php
define('MSSQL_LOGIN',  'easy');
define('MSSQL_PASS',  'e@sy1234');
define('MSSQL_HOST',  '192.168.10.63');
define('MSSQL_PORT',  '1433');
define('MSSQL_DB',  'easy');

$link = mssql_connect(MSSQL_HOST . ':' . MSSQL_PORT, MSSQL_LOGIN, MSSQL_PASS) or die("Impossible de se connecter à la base Easyphone");
mssql_select_db(MSSQL_DB);
if(!$link) {
    die('Erreur de connexion à MSSQL');
}

?>
