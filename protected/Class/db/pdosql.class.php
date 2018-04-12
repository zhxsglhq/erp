<?php
class pdosql {
    public static $dbconfig;
    public static $dbtype;
    public static $dbhost;
    public static $dbport;
    public static $dbname;
    public static $dbuser;
    public static $dbpass;
    public static $charset;
    public static $stmt = null;
    public static $DB = null;
    public static $connect = false; // 是否長连接
    public static $debug = false;
    private static $parms = array ();


    public function __construct() {
        self::$dbconfig=config('DB');
        self::$dbtype = self::$dbconfig['DB_TYPE'];
        self::$dbhost = self::$dbconfig['DB_HOST'];
        self::$dbport = self::$dbconfig['DB_PORT'];
        self::$dbname =  self::$dbconfig['DB_NAME'];
        self::$dbuser =  self::$dbconfig['DB_USER'];
        self::$dbpass =  self::$dbconfig['DB_PWD'];
        self::$connect =  self::$dbconfig['DB_PCONNECT'];
        self::$charset =  self::$dbconfig['DB_CHARSET'];
        self::connect ();
        self::$DB->setAttribute ( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true );
        self::$DB->setAttribute ( PDO::ATTR_EMULATE_PREPARES, true );
        self::execute ( 'SET NAMES ' . self::$charset );
    }

    public function __destruct() {
        self::close ();
    }



    public function connect() {
        try {
            self::$DB = new PDO ( self::$dbtype . ':host=' . self::$dbhost . ';port=' . self::$dbport . ';dbname=' . self::$dbname, self::$dbuser, self::$dbpass, array (
                    PDO::ATTR_PERSISTENT => self::$connect
            ) );
			//self::$DB = new PDO (pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass);
        } catch ( PDOException $e ) {
          exit(json_encode('Database connection failed!'));
        }
    }


    public function close() {
        self::$DB = null;
    }


    public function quote($str) {
        return self::$DB->quote ( $str );
    }


    public function now() {
        return date ( "Y-m-d H:i:s" );
    }

    public function getTablesName() {
        self::$stmt = self::$DB->query ( 'SHOW TABLES FROM ' . self::$dbname );
        $result = self::$stmt->fetchAll ( PDO::FETCH_NUM );
        self::$stmt = null;
        return $result;
    }


    public function getFields($table) {
        self::$stmt = self::$DB->query ( "DESCRIBE $table" );
        $result = self::$stmt->fetchAll ( PDO::FETCH_ASSOC );
        self::$stmt = null;
        return $result;
    }


    public function getLastId() {
        return self::$DB->lastInsertId ();
    }


    public function autocommit() {
        self::$DB->beginTransaction ();
    }


    public function commit() {
        self::$DB->commit ();
    }


    public function rollback() {
        self::$DB->rollback ();
    }


    public function execute($sql) {
        self::getPDOError ( $sql );
        return self::$DB->exec ( $sql );
    }


    private function getCode( $args) {
        $code = '';
        if (is_array ( $args )) {
            foreach ( $args as $k => $v ) {
                $code .= "`$k`='$v',";
            }
        }
        $code = substr ( $code, 0, - 1 );
        return $code;
    }


    private function _fetch($sql, $type) {
        $result = array ();
        self::$stmt = self::$DB->query ( $sql );
        self::getPDOError ( $sql );
        self::$stmt->setFetchMode ( PDO::FETCH_ASSOC );
        switch ($type) {
            case '0' :
                $result = self::$stmt->fetch ();
                break;
            case '1' :
                $result = self::$stmt->fetchAll ();
                break;
            case '2' :
                if ($sql) {
                    $result = self::$stmt->fetchColumn ();
                } elseif (self::$stmt) {
                    $result = self::$stmt->rowCount ();
                } else {
                    $result = 0;
                }
                break;
        }
        self::$stmt = null;
        return $result;
    }


    private function count($pagesize, &$page, &$pagecount, &$recordcount) {
        // 获取記录行数,計算頁数,以及重新檢查當前頁碼
        $pagecount = ceil ( $recordcount / $pagesize );
        if ($pagecount > 0) {
            if ($page > $pagecount) {
                $page = $pagecount;
            } elseif ($page < 1) {
                $page = 1;
            }
        } else {
            $page = 1;
        }
    }




    public function add($table, $args) {
        $sql = "INSERT INTO `$table` SET ";
        $code = self::getCode ( $args );
        $sql .= $code;
        return self::execute ( $sql );
    }


    public function update($table, $args, $where) {
        $code = self::getCode ( $args );
        $sql = "UPDATE `$table` SET ";
        $sql .= $code;
        $sql .= " Where $where";
        return self::execute ( $sql );
    }


    public function delete($table, $where) {
        $sql = "DELETE FROM `$table` Where $where";
        return self::execute ( $sql );
    }


    public function fetOne($table, $field = '*', $where = false) {
        $sql = "SELECT {$field} FROM `{$table}`";
        $sql .= ($where) ? " WHERE $where" : '';
        return self::_fetch ( $sql, $type = '0' );
    }

    public function fetAll($table, $field = '*', $where = false, $orderby = false, $limit = false) {
        $sql = "SELECT {$field} FROM `{$table}`";
        $sql .= ($where) ? " WHERE $where" : '';
        $sql .= ($orderby) ? " ORDER BY $orderby" : '';
        $sql .= ($limit) ? " limit $limit" : '';
		//echo $sql;
        return self::_fetch ( $sql, $type = '1' );
    }

    public function getOne($sql) {
       return self::_fetch ( $sql, $type = '0' );
    }

    public function getAll($sql) {
        return self::_fetch ( $sql, $type = '1' );
    }

    public function scalar($sql, $fieldname) {
        $row = self::_fetch ( $sql, $type = '0' );
        return $row [$fieldname];
    }

    public function fetRowCount($table, $field = '*', $where = false) {
        $sql = "SELECT COUNT({$field}) AS num FROM `$table`";
        $sql .= ($where) ? " WHERE $where" : '';
        return self::_fetch ( $sql, $type = '2' );
    }


    public function getRowCount($sql) {
        return self::_fetch ( $sql, $type = '2' );
    }


    public function fetPageAll($table, $field = '*', $where = false, $orderby = false, $pagesize, &$page, &$pagecount, &$recordcount) {
        $sql = "SELECT {$field} FROM `{$table}`";
        $sql .= ($where) ? " WHERE $where" : '';
        //echo $sql;
        return self::getPageAll ( $sql,$table, $orderby, $pagesize, $page, $pagecount, $recordcount );
    }


    public function getPageAll($sql,$table, $orderby = false, $pagesize, &$page, &$pagecount, &$recordcount) {
        $sqlcount = "select count(1) as `recordcount` from ($sql) as t;";
        $recordcount = self::scalar ( $sqlcount, 'recordcount' );

        self::count ( $pagesize, $page, $pagecount, $recordcount );
        $start = ($page - 1) * $pagesize;
        $sql .= ($orderby) ? " ORDER BY $orderby" : '';
        $sql .= " limit $start,$pagesize";
        $setsql ="SELECT * FROM `{$table}` a JOIN (".$sql.") b ON a.".$table."_Id = b.".$table."_Id";
		return self::_fetch ( $setsql, $type = '1' );
    }




    public function pramGetOne($sql, $input_parameters) {
        return self::_pramfetch ( $sql, $input_parameters, $type = '0' );
    }

    public function pramGetAll($sql, $input_parameters) {
        return self::_pramfetch ( $sql, $input_parameters, $type = '1' );
    }

    public function pramExecute($sql, $input_parameters) {
        return self::_pramfetch ( $sql, $input_parameters, $type = '2' );
    }

    public function pramScalar($sql, $input_parameters, $fieldname) {
        $row = self::_pramfetch ( $sql, $input_parameters, $type = '0' );
        return $row [$fieldname];
    }

    private function _pramfetch($sql, $input_parameters, $type) {
        $result = array ();
        self::$stmt = self::$DB->prepare ( $sql );
        self::getPDOError ( $sql );
        self::$stmt->execute ( $input_parameters );
        self::getSTMTError ( $sql );
        self::$stmt->setFetchMode ( PDO::FETCH_ASSOC );
        switch ($type) {
            case '0' :
                $result = self::$stmt->fetch ();
                break;
            case '1' :
                $result = self::$stmt->fetchAll ();
                break;
            case '2' :
                if (self::$stmt) {
                    $result = self::$stmt->rowCount ();
                } else {
                    $result = 0;
                }
                break;
        }
        self::$stmt = null;
        return $result;
    }




    public function pramadd($parameter, $variable, $data_type, $length) {
        array_push ( self::$parms, array (
                $parameter,
                $variable,
                $data_type,
                $length
        ) );
    }

    public function pramclear() {
        self::$parms = array ();
    }

    public function procGetOne($sql) {
        return self::_procfetch ( $sql, $type = '0' );
    }

    public function procGetAll($sql) {
        return self::_procfetch ( $sql, $type = '1' );
    }

    public function procExecute($sql) {
        return self::_procfetch ( $sql, $type = '2' );
    }

    public function getReturn() {
        return self::scalar ( "select @ireturn AS iReturn", "iReturn" );
    }

    private function _procfetch($sql, $type) {
        $result = array ();
        self::$stmt = self::$DB->prepare ( $sql );
        self::getPDOError ( $sql );
        foreach ( self::$parms as $pram ) {
            self::$stmt->bindParam ( $pram [0], $pram [1], $pram [2], $pram [3] );
        }
        self::$stmt->execute ();
        self::getSTMTError ( $sql );
        self::$stmt->setFetchMode ( PDO::FETCH_ASSOC );
        switch ($type) {
            case '0' :
                $result = self::$stmt->fetch ();
                break;
            case '1' :
                // do{
                // $result[] = self::$stmt->fetchAll();
                // } while (self::$stmt->nextRowset());
                $result = self::$stmt->fetchAll ();
                self::$stmt->closeCursor ();
                break;
            case '2' :
                if (self::$stmt) {
                    $result = self::$stmt->rowCount ();
                } else {
                    $result = 0;
                }
                break;
        }
        self::$stmt = null;
        return $result;
    }


    public function procExecOut() {
        // $colour = 'red';
        self::$stmt = self::$DB->prepare ( 'CALL puree_fruit(?)' );
        self::$stmt->bindParam ( 1, $colour, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000 );
        self::$stmt->execute ();
        print ("After pureeing fruit, the colour is: $colour") ;
    }





    public function setDebugMode($mode = true) {
        return ($mode == true) ? self::$debug = true : self::$debug = false;
    }


    private function getPDOError($sql) {
        self::$debug ? self::errorfile ( $sql ) : '';
        if (self::$DB->errorCode () != '00000') {
            $info = (self::$stmt) ? self::$stmt->errorInfo () : self::$DB->errorInfo ();
            echo (self::sqlError ( 'mySQL Query Error', $info [2], $sql ));
            exit ();
        }
    }
    private function getSTMTError($sql) {
        self::$debug ? self::errorfile ( $sql ) : '';
        if (self::$stmt->errorCode () != '00000') {
            $info = (self::$stmt) ? self::$stmt->errorInfo () : self::$DB->errorInfo ();
            echo (self::sqlError ( 'mySQL Query Error', $info [2], $sql ));
            exit ();
        }
    }


    private function errorfile($sql) {
        //echo $sql . '<br />';
        $errorfile = './dberrorlog.php';
        $sql = str_replace ( array (
                "\n",
                "\r",
                "\t",
                "  ",
                "  ",
                "  "
        ), array (
                " ",
                " ",
                " ",
                " ",
                " ",
                " "
        ), $sql );
        if (! file_exists ( $errorfile )) {
            $fp = file_put_contents ( $errorfile, "<?PHP exit('Access Denied'); ?>\n" . $sql );
        } else {
            $fp = file_put_contents ( $errorfile, "\n" . $sql, FILE_APPEND );
        }
    }


    private function sqlError($message = '', $info = '', $sql = '') {
        //echo $error;
        $html .= '<style type="text/css">ol {margin:0px;padding:0px;} .w {width:800px;margin:100px auto;padding:0px;border:1px solid #cccccc;background-color:#ffffff;} .h {padding:8px;background-color:#ffffcc;} li {height:auto;padding:5px;line-height:22px;border-top:1px solid #efefef;list-style:none;overflow:hidden;}</style>';
        $html .= '<div class="w"><ol>';
        if ($message) {
            $html .= $sql;'<div class="h">语句错误！</div>';
        }
        $html .= '<li>Date: ' . date ( 'Y-n-j H:i:s', time () ) . '</li>';
        $html .= '</ol></div>';
        return $html;
    }

}
?>
