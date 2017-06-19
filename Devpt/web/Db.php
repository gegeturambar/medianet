<?php

class Db
{
    protected $DBH;
    protected $STH;

    private $c = null;
    private $p = array();

    private static $_instance = null;

    private function __Construct()
    {
		// print_r(\PDO::getAvailableDrivers());
		
        $resources = Application::getInstance()->getResources();
		
        // Load the database configuration
        $connection_options = array(
            'driver'    => $resources->database->driver,
            'host'      => $resources->database->host,
            'dbname'    => $resources->database->name,
            'user'      => $resources->database->user,
            'password'  => $resources->database->password
        );
		
        $this->DBH = new \PDO("{$connection_options['driver']}:server={$connection_options['host']};", $connection_options['user'], $connection_options['password']);
        $this->DBH->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_UTF8);
    }

    // Transaction
    public function begin(){
        $this->DBH->beginTransaction();
    }

    public function commit(){
        $this->DBH->commit();
    }

    public function rollback(){
        $this->DBH->rollBack();
    }

    public function query($sql, array $parameters = array())
    {
        try {
            $this->c = $sql;
            $this->p = $parameters;

            $this->STH = $this->DBH->prepare($sql);
            $this->STH->execute($parameters);
        } catch(\PDOException $e) {
            echo $e->getMessage();
        } catch (Exception $e) {
          echo $e->getMessage();
        }

        return $this;
    }

    public function getInsertId() {
        return $this->DBH->lastInsertId();
    }

    public function fetchAll()
    {
        return $this->STH->fetchAll();
    }

    public function fetchRow()
    {
        $rows = $this->fetchAll();

        if(empty($rows[0]))
            return false;
        return $rows[0];
    }

    public function getQueryString()
    {
        $string = $this->c;
        $data = $this->p;

        $indexed=$data==array_values($data);
        foreach($data as $k=>$v) {
            if(is_string($v)) $v="'$v'";
            if($indexed) $string=preg_replace('/\?/',$v,$string,1);
            else $string=str_replace(":$k",$v,$string);
        }
        return $string;
    }

    public function paginate()
    {
        $pos = strrpos(strtolower($this->c), 'limit');
        $exp = substr($this->c, $pos + 5);
        $exp = explode(',', $exp);

        $offset = trim($exp[0]);
        $range = trim($exp[1]);

        $rows = $this->fetchAll();

        $count_query = "SELECT FOUND_ROWS() AS totalRows";
        $count = $this->query($count_query)->fetchRow();

        $current = ($offset/$range) + 1;

        $pages = array();
        $total = $count['totalRows'];
        $last = ceil($total/$range);

        $next = $current >= $last ? $last : $current + 1;
        $previous = $current - 1;
        $page_number = 5;

        if($page_number < $last) {
            for($i = 0; $i < $page_number; $i++) {
                if($current + $page_number/2 <= $last && $current - $page_number/2 >= 1) {
                    $pages[] = $current - 2 + $i;
                } elseif($current + $page_number/2 > $last) {
                    $pages[] = $last - $page_number + $i;
                } else {
                    $pages[] = 1 + $i;
                }
            }
        } else {
            for($i = 0; $i < $last; $i++) {
                $pages[] = 1 + $i;
            }
        }

        $paginator = array();
        $paginator['first'] = 1;
        $paginator['last'] = $last;
        $paginator['current'] = $current;
        $paginator['previous'] = $previous;
        $paginator['next'] = $next;
        $paginator['pages'] = $pages;
        $paginator['total'] = $total;

        return array('rows' => $rows, 'paginator' => $paginator);
    }

    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }
}