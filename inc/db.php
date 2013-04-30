<?php
define ('CR_SERVER_GONE_ERROR', 2006);
define ('CR_SERVER_LOST', 2013);
class db
{
    private $host = _DBHOST;
    private $user = _DBUSER;
    private $pass = _DBPASS;
    private $dbname = _DBNAME;

    private $dbh;
    private $error;

    private $stmt;

    public function __construct($nodb=0) {
        // Set DSN
        if ($nodb==0) {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname .';charset=UTF-8';
        } else {
            $dsn = 'mysql:host=' . $this->host . ';charset=UTF-8';
        }

        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        );
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch (PDOException $e) {
            if ($e->getCode()==CR_SERVER_GONE_ERROR || $e->getCode()==CR_SERVER_LOST) $this->__construct() ;
            $this->error = $e->getMessage();
        }

    }

    public function select_db ($db_name){
        $this->query("use ".$db_name);
        return $this->execute();
    }

    public function db_query($query, $params=array()) {
        //$all_vars_regex="/\\'\$([^\\']*)\\'/i";
/*
        $all_vars_regex="/\\'?\\\"?\\$([^\\'?\\\"\\s?]*)\\'?\\\"?/i"; //non escaped = \'?\"?\$([^\'?\"\s?]*)\'?\"?
        $vars=array();
        $v_match=preg_match_all($all_vars_regex,$query,$vars);
        if ($v_match) {
            $q=preg_replace($all_vars_regex, ":$1", $query);
            $this->query($q);
            $old_vars = $vars[0];
            $new_vars = $vars[1];
            for($i=0;$i<count($old_vars);$i++) {
                $this->bind(":".$new_vars[$i], $old_vars[$i]);
                //echo $new_vars[$i];
            }
        } else {
            $this->query($query);
        }
*/
        $this->query($query);
        if (isset($params) && !empty($params)) {
            foreach ($params as $par => $var) {
                $this->bind($par, $var);
            }
        }
        return $this->execute();
        //if (preg_match("/^(?:select)/i", $query)) {

        //}

    }

    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function resultset(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_BOTH);
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_BOTH);
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }

    public function endTransaction(){
        return $this->dbh->commit();
    }

    public function cancelTransaction(){
        return $this->dbh->rollBack();
    }

    public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
    }


}
