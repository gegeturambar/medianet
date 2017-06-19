<?php

/**
 * Created by PhpStorm.
 * User: jthibout
 * Date: 22/03/2017
 * Time: 10:06
 */
class Directories extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setTable('directory');
        $this->_setPrefixe('');
    }

    public function fetchOneByNameAndParent($name,$parentid = 0){
        $sql = 'select * from ' . $this->_table;
        $sql .= ' where '.$this->_prefixe.'name = ?';
        $sql .= ' and '.$this->_prefixe.'parentid = ?';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($name,$parentid));

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAllButOne($id = null,$order="id",$join=false)
    {
        if(is_null($id)){
            return false;
        }
        if($join) {
            $sql = 'select t1.*, t2.name AS parentname from ' . $this->_table . ' AS t1 LEFT JOIN ' . $this->_table . ' AS t2 ON ( t1.parentid = t2.id ) ';
            $sql .= " WHERE t1.id <> ? ";
            $sql .= $order ? ' ORDER BY ' . $order : '';

            $statement = $this->_dbh->prepare($sql);
            $statement->execute(array($id));

            return $statement->fetchAll(PDO::FETCH_OBJ);
        }else{
            $sql = 'select * from ' . $this->_table . " WHERE id <> ? ";
            $sql .= $order ? ' ORDER BY ' . $order : '';

            $statement = $this->_dbh->prepare($sql);
            $statement->execute(array($id));
            return $statement->fetchAll(PDO::FETCH_OBJ);

        }
    }

    public function fetchAll($order="id",$join=false)
    {
        if($join) {
            $sql = 'select t1.*, t2.name AS parentname from ' . $this->_table . ' AS t1 LEFT JOIN ' . $this->_table . ' AS t2 ON ( t1.parentid = t2.id ) ';
            $sql .= $order ? ' ORDER BY ' . $order : '';

            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_OBJ);
        }else{
            return parent::fetchAll($order);
        }
    }

    public function fetchAllByAccess($access = 'USER', $order = null){
        $sql = 'select t1.* from ' . $this->_table . ' AS t1 WHERE t1.access = ? ';
        $sql .= $order ? ' ORDER BY ' . $order : '';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($access));

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function init(){
        $sql = "TRUNCATE TABLE $this->_table;ALTER TABLE $this->_table AUTO_INCREMENT = 1;";
        $sql .= "INSERT INTO $this->_table (name,parentid,path,access,lft,rgt) VALUES (?,?,?,?,?,?);";
        $base_path = "../../upload";
        $options = array("ROOT",0,$base_path,"ADMIN",1,2);
        $statement = $this->_dbh->prepare($sql);
        $ret = $statement->execute($options);
        return $ret;
    }


    public function rebuild_tree($parent, $left) {
        // if root does not exists, create it
        $rootDir = $this->fetchOne(0); // todo
        if(!$rootDir){
            // get all values
            $directories = $this->fetchAll();
            $sql = "TRUNCATE TABLE $this->_table;ALTER TABLE $this->_table AUTO_INCREMENT = 1;";
            $sql .= "INSERT INTO $this->_table (name,parentid,path,access) VALUES (?,?,?,?);";
            $base_path = "../../upload";
            $options = array("ROOT",0,$base_path,"ADMIN");

            $corresp = array();
            foreach($directories as $dir){
                $sql .= "INSERT INTO $this->_table (name,path,access) VALUES (?,?,?);";

                $parentid = is_null($dir->parentid) ? 1 : $this->parentid;
                $options = array_merge($options,array($dir->name, $parentid,$dir->path,$dir->access));
            }
            $statement = $this->_dbh->prepare($sql);
            $ret = $statement->execute($options);

            var_dump($ret);die();

        }
die('huù');
        // the right value of this node is the left value + 1
        $right = $left+1;

        $sql = "SELECT id FROM $this->_table WHERE parent_id = ?";
        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($parent));
        // get all children of this node

        while ($row = $statement->fetchObject()) {
            // recursive execution of this function for each
            // child of this node
            // $right is the current right value, which is
            // incremented by the rebuild_tree function
            $right = rebuild_tree($row->id, $right);
        }

        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value
        $sql = "UPDATE $this->_table SET lft = ?, rgt = ? WHERE id= ?";
        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($left,$right,$parent));

        // return the right value of this node + 1
        return $right+1;
    }
}