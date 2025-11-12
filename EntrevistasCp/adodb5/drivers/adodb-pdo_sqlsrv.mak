<?php
/*
 * ADOdb driver for Microsoft SQL Server using the native sqlsrv extension.
 * Compatible con PHP 7+ y 8+.
 */

include_once(ADODB_DIR . '/drivers/adodb-mssqlnative.inc.php');

class ADODB_sqlsrv extends ADODB_mssqlnative {
    var $databaseType = 'sqlsrv';
    var $dataProvider = 'sqlsrv';

    function __construct() {
        parent::__construct();
    }
}
