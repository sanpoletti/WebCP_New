<?php
/**
 * Modern SQL Server driver for ADOdb using sqlsrv extension
 * Compatible with PHP 7+ and PHP 8+
 */

if (!defined('ADODB_DIR')) die();

class ADODB_sqlsrv extends ADOConnection {
    var $databaseType = 'sqlsrv';
    var $hasInsertID = true;
    var $hasAffectedRows = true;
    var $fmtDate = "'Y-m-d'";
    var $fmtTimeStamp = "'Y-m-d H:i:s'";
    var $replaceQuote = "''";
    var $charSet = 'UTF-8';

    function _connect($argHostname, $argUsername, $argPassword, $argDatabasename, $newconnect = false) {
        $connectionInfo = array(
            "UID" => $argUsername,
            "PWD" => $argPassword,
            "Database" => $argDatabasename,
            "CharacterSet" => $this->charSet
        );
        $this->_connectionID = sqlsrv_connect($argHostname, $connectionInfo);
        if (!$this->_connectionID) {
            $this->_errorMsg = $this->_getLastError();
            return false;
        }
        return true;
    }

    function _query($sql, $inputarr = false) {
        $stmt = sqlsrv_query($this->_connectionID, $sql);
        if ($stmt === false) {
            $this->_errorMsg = $this->_getLastError();
        }
        return $stmt;
    }

    function _close() {
        return sqlsrv_close($this->_connectionID);
    }

    function _fetch($rs) {
        return sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC);
    }

    function _getLastError() {
        $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
        if ($errors) {
            $msg = '';
            foreach ($errors as $error) {
                $msg .= "SQLSTATE: " . $error['SQLSTATE'] . "; Code: " . $error['code'] . "; Message: " . $error['message'] . "\n";
            }
            return $msg;
        }
        return "Unknown SQLSRV error.";
    }

    function ErrorMsg() {
        return $this->_errorMsg;
    }

    function Insert_ID($table = '', $column = '') {
        $rs = $this->Execute("SELECT SCOPE_IDENTITY() AS insert_id");
        return $rs ? $rs->fields['insert_id'] : false;
    }

    function Affected_Rows() {
        return sqlsrv_rows_affected($this->_queryID);
    }
}

class ADORecordSet_sqlsrv extends ADORecordSet {
    var $databaseType = "sqlsrv";

    function __construct($queryID, $mode = false) {
        if ($mode === false) $mode = ADODB_FETCH_ASSOC;
        $this->fetchMode = $mode;
        $this->_queryID = $queryID;
        $this->_inited = false;
        $this->fields = array();
        $this->EOF = false;
    }

    function FetchRow() {
        if (!$this->_queryID) {
            $this->EOF = true;
            return false;
        }
        $row = sqlsrv_fetch_array($this->_queryID, SQLSRV_FETCH_ASSOC);
        if ($row === null || $row === false) {
            $this->EOF = true;
            return false;
        }
        $this->fields = $row;
        return $row;
    }

    function Close() {
        if ($this->_queryID) {
            sqlsrv_free_stmt($this->_queryID);
            $this->_queryID = false;
        }
        return true;
    }
}
?>
