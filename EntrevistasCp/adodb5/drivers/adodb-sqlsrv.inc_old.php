
<?php
/*
 V4.93 5 July 2005  (c) 2000-2005 John Lim (jlim@natsoft.com.my). All rights reserved.
 Released under both BSD license and Lesser GPL library license.
 Whenever there is any discrepancy between the two licenses,
 the BSD license will take precedence. See License.txt.

 MSSQL support via the Microsoft SQLSRV extension.
*/

include_once(ADODB_DIR."/drivers/adodb-mssql.inc.php");

class ADODB_sqlsrv extends ADODB_mssql {
    var $databaseType = "sqlsrv";
    var $connectStmt = "sqlsrv_connect";
    var $hasTop = true;
    var $hasInsertID = true;
    var $substr = "substring";
    var $length = "len";
    var $metaColumnsSQL = "select * from information_schema.columns where table_name='%s'";

function _connect($argHostname, $argUsername, $argPassword, $argDatabasename, $newconnect = false) {
    $connectionInfo = array(
        "UID" => $argUsername,
        "PWD" => $argPassword,
        "Database" => $argDatabasename,
        "CharacterSet" => "UTF-8"
    );

    $this->_connectionID = sqlsrv_connect($argHostname, $connectionInfo);

    if ($this->_connectionID === false) {
        $errors = sqlsrv_errors();
        $errorMsg = "";
        if ($errors !== null) {
            foreach ($errors as $error) {
                $errorMsg .= "SQLSTATE: " . $error['SQLSTATE'] . ", Code: " . $error['code'] . ", Message: " . $error['message'] . "\n";
            }
        }
        die("Error de conexión SQL Server: \n" . $errorMsg);
    }

    return true;
}




    function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename) {
        return $this->_connect($argHostname, $argUsername, $argPassword, $argDatabasename);
    }

    function ServerInfo() {
        $arr['description'] = 'SQLSRV Driver';
        return $arr;
    }
}
?>
