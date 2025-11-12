<?php
// ADOdb driver para SQL Server con el controlador 'sqlsrv'
include_once(ADODB_DIR . '/drivers/adodb-mssqlnative.inc.php');

class ADODB_sqlsrv extends ADODB_mssqlnative {
    var $databaseType = 'sqlsrv';
    var $dataProvider = 'sqlsrv';

    function __construct() {
        parent::__construct();
    }
}
