<?php

class Tree
{
	
	public static function rebuild_tree($parent, $left) {
        // the right value of this node is the left value + 1
        $right = $left+1;

        // get all children of this node
        $result = mysql_query('SELECT title FROM tree '.
            'WHERE parent="'.$parent.'";');
        while ($row = mysql_fetch_array($result)) {
            // recursive execution of this function for each
            // child of this node
            // $right is the current right value, which is
            // incremented by the rebuild_tree function
            $right = rebuild_tree($row['title'], $right);
        }

        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value
        mysql_query('UPDATE tree SET lft='.$left.', rgt='.
            $right.' WHERE title="'.$parent.'";');

        // return the right value of this node + 1
        return $right+1;
    }
}

