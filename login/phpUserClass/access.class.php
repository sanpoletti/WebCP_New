<?php
class flexibleAccess {
    var $dbHost = '10.22.0.253';
    var $dbUser = 'ficharub';
    var $dbPass = 'colo';
    var $dbName = 'titularDebo';
    var $dbTable = 'usuarios';

    var $tbFields = array(
        'userID' => 'userID',
        'login' => 'username',
        'pass' => 'passw',
        'email' => 'email',
        'active' => 'active',
        'group' => 'idGrupo',
        'nombre' => 'nombre'
    );

    var $hashFunc = 'sha1';
    var $userID = null;
    var $getUserData = array();
    var $isAuthorized = false;
    var $sessionVariable = 'usuario_id';
    var $remCookieName = 'usuario_cookie';
    var $remTime = 2592000;
    var $remCookieDomain = '';
    var $dbConn = false;

    function __construct($settings = '') {
        if (is_array($settings)) {
            foreach ($settings as $k => $v) {
                $this->{$k} = $v;
            }
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dbConn = sqlsrv_connect($this->dbHost, array(
            "Database" => $this->dbName,
            "UID" => $this->dbUser,
            "PWD" => $this->dbPass,
            "CharacterSet" => "UTF-8"
        ));

        if (!$this->dbConn) {
            $this->error(print_r(sqlsrv_errors(), true), __LINE__, true);
        }

        if (isset($_SESSION[$this->sessionVariable])) {
            $this->loadUser($_SESSION[$this->sessionVariable]);
        } elseif (isset($_COOKIE[$this->remCookieName])) {
            $cookie = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
            if (isset($cookie['uname']) && isset($cookie['password'])) {
                $this->login($cookie['uname'], $cookie['password']);
            }
        }
    }

    function login($uname, $password, $loadUser = true, $remember = false) {
        $uname = $this->escape($uname);
        $originalPassword = $password;
        $password = "'".sha1($password)."'";

        $res = $this->query("SELECT TOP 1 * FROM {$this->dbTable}
            WHERE {$this->tbFields['login']} = '$uname' AND {$this->tbFields['pass']} = $password", __LINE__);

        if (!sqlsrv_has_rows($res)) return false;

        if ($loadUser) {
            $this->getUserData = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
            $this->userID = $this->getUserData[$this->tbFields['userID']];
            $_SESSION[$this->sessionVariable] = $this->userID;
            $_SESSION['nombre'] = $this->getUserData[$this->tbFields['nombre']] ?? '';
            $_SESSION['username'] = $this->getUserData[$this->tbFields['login']] ?? '';
            $_SESSION['idGrupo'] = $this->getUserData[$this->tbFields['group']] ?? null;

            $this->cargarPermisos($_SESSION['idGrupo']);

            if ($remember) {
                $cookie = base64_encode(serialize(array('uname' => $uname, 'password' => $originalPassword)));
                setcookie($this->remCookieName, $cookie, time() + $this->remTime, '/', $this->remCookieDomain);
            }
        }
        return true;
    }

    function cargarPermisos($idGrupo) {
        $_SESSION['permisos'] = [];

        if (!$idGrupo) return;

        $sql = "SELECT idpermiso FROM dbo.grupos WHERE idgrupo = ?";
        $stmt = sqlsrv_query($this->dbConn, $sql, array($idGrupo));

        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $_SESSION['permisos'][] = trim($row['idpermiso']);
            }
        } else {
            $this->error(print_r(sqlsrv_errors(), true), __LINE__, true);
        }
    }

    function tienePermiso($permiso) {
        return isset($_SESSION['permisos']) && in_array($permiso, $_SESSION['permisos']);
    }

    function logout() {
        unset($_SESSION[$this->sessionVariable]);
        unset($_SESSION['permisos']);
        unset($_SESSION['username']);
        unset($_SESSION['idGrupo']);
        setcookie($this->remCookieName, '', time() - 3600, '/', $this->remCookieDomain);
        $this->userID = null;
        $this->getUserData = array();
    }

   /* function is_loaded() {
        return isset($this->userID);
    }
*/
    function is_loaded() {
        return isset($_SESSION[$this->sessionVariable]) && !empty($this->userID);
    }
    
    
    
    function loadUser($userID) {
        $res = $this->query("SELECT TOP 1 * FROM {$this->dbTable} WHERE {$this->tbFields['userID']} = '".$this->escape($userID)."'", __LINE__);
        if (!sqlsrv_has_rows($res)) return false;

        $this->getUserData = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
        $this->userID = $userID;
        $_SESSION[$this->sessionVariable] = $this->userID;

        $_SESSION['username'] = $this->getUserData[$this->tbFields['login']] ?? '';
        $_SESSION['idGrupo'] = $this->getUserData[$this->tbFields['group']] ?? null;
        $this->cargarPermisos($_SESSION['idGrupo']);

        return true;
    }

    function getUserData($field) {
        return $this->getUserData[$field] ?? false;
    }

    function query($sql, $line = 'Unknown') {
        $res = sqlsrv_query($this->dbConn, $sql);
        if (!$res) {
            $this->error(print_r(sqlsrv_errors(), true), $line, true);
        }
        return $res;
    }

    function escape($str) {
        return str_replace("'", "''", $str);
    }

    function error($error, $line = '', $die = false) {
        echo "<div style='border:1px solid red; padding:5px;'><strong>Error en línea $line:</strong> $error</div>";
        if ($die) die();
    }
    function getAvailableGroups() {
        $grupos = [];
        $sql = "SELECT idgrupo, idpermiso FROM grupos";
        
        $stmt = sqlsrv_query($this->dbConn, $sql);
        
        if ($stmt === false) {
            $this->error(print_r(sqlsrv_errors(), true), __LINE__, true);
        }
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $grupos[] = [
                'idgrupo' => $row['idgrupo'],
                'nombre' => $row['idpermiso']
            ];
        }
        
        return $grupos;
    }
    
    public function insertUser($data) {
        echo "<p>🔍 Entrando a insertUser()</p>";
        
        $sql = "INSERT INTO {$this->dbTable}
        (username, passw, email, active, idGrupo, nombre)
        VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = array(
            $data['username'],         // CUIT
            $data['passw'],            // SHA1 Hashed password
            $data['email'],            // Correo electrónico
            1,                         // active: siempre 1
            $data['idGrupo'],          // ID del grupo (admin, user, etc)
            $data['nombre']            // Nombre completo
        );
        /*
        echo "<pre>🛠️ Query generado:\n$sql</pre>";
        echo "<pre>📦 Parámetros:\n";
        print_r($params);
        echo "</pre>";
        */
        $stmt = sqlsrv_query($this->dbConn, $sql, $params);
        
        if ($stmt === false) {
            echo "<pre>❌ Error SQL:\n";
            print_r(sqlsrv_errors(), true);
            echo "</pre>";
            return false;
        }
        
        echo "<p>✅ Usuario insertado correctamente.</p>";
        return true;
    }
    
    
    
    
    
}
?>
