<?php

error_reporting(E_ALL & ~ E_NOTICE);
ini_set('display_errors', 1);
date_default_timezone_set('CET');
require('Utils.php');

//soonweb
if (!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) {
    ini_set("session.cookie_secure", true);
    ini_set("session.cookie_httponly", true);
} else {
    ini_set("session.cookie_secure", false);
    ini_set("session.cookie_httponly", false);
}
// defines the web root
define('WEB_ROOT', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/index.php')));
// define('WEB_ROOT', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/index.php')-4));



// defindes the path to the files
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
// defines the cms path
define('CMS_PATH', ROOT_PATH . '/lib/base/');

// starts the session
session_start();

// includes the system routes. Define your own routes in this file
include(ROOT_PATH . '/config/routes.php');

/**
 * Standard framework autoloader
 * @param string $className
 */
function autoloader($className) {
    // controller autoloading
    if (strlen($className) > 10 && substr($className, -10) == 'Controller') {
        if (file_exists(ROOT_PATH . '/app/controllers/' . $className . '.php') == 1) {
            require_once ROOT_PATH . '/app/controllers/' . $className . '.php';
        }
    }
    else {
        if (file_exists(CMS_PATH . $className . '.php')) {
            require_once CMS_PATH . $className . '.php';
        }
        else if (file_exists(ROOT_PATH . '/lib/' . $className . '.php')) {
            require_once ROOT_PATH . '/lib/' . $className . '.php';
        }
        else {
            require_once ROOT_PATH . '/app/models/'.$className.'.php';
        }
    }
}

// activates the autoloader
spl_autoload_register('autoloader');


$path_source =  '\\\\srvs2hmedia\\S2hGroup';

$path_dest = '../../upload';


$path_source = Utils::getConf()['import']['path_source'];
$path_dest = Utils::getConf()['import']['path_dest'];


function copyDirectory($item, $path_source, $path_dest,$parent_id = 1)
{
    if (!is_dir($path_source))
        return;

    $items = scandir($path_source);

    $directoryModel   = new Directories();

    foreach ($items as $item) {
        if($item == '.' || $item == '..')
            continue;
        $path = $path_source . "\\$item";

        if (is_dir($path)) {
            //create directory
            if(!file_exists($path_dest.DIRECTORY_SEPARATOR.$item)) {
                mkdir($path_dest . DIRECTORY_SEPARATOR . $item);
            }

            // add directory to bdd if not exists
            $directory = $directoryModel->fetchOneByNameAndParent($item, $parent_id);
            if(!$directory){
                //create
                $newParentId = $directoryModel->save(array('name'=>$item,'parentid'=>$parent_id, "path" => $path_dest . DIRECTORY_SEPARATOR . $item));
            }else{
                $newParentId = $directory->id;
            }

            copyDirectory( $item, $path, $path_dest."\\$item", $newParentId);
        }
    }
}

$directoryModel   = new Directories();
$ret = $directoryModel->init();

if($ret) {
    $dir = $directoryModel->fetchOneByNameAndParent("ROOT");
    copyDirectory('upload', $path_source, $path_dest ,$dir->id);
}


/*
 * nested tree cleaner, but, not for jsTree >___<"
 *
function copyDirectory($item, $path_source, $path_dest, $lft = 1 )
{
    $rgt = $lft;

    if (!is_dir($path_source))
        return $rgt; // rien passé

    $items = scandir($path_source);

    $directoryModel   = new Directories();

    foreach ($items as $item) {
        if($item == '.' || $item == '..')
            continue;
        $path = $path_source . "\\$item";

        if (is_dir($path)) {

            $lft = $rgt;

            //create directory
            if(!file_exists($path_dest.DIRECTORY_SEPARATOR.$item)) {
                mkdir($path_dest . DIRECTORY_SEPARATOR . $item);
            }

            $lft++;
            //echo "for item => $item : lft = $lft \r\n";
            $rgt = copyDirectory($item, $path, $path_dest."\\$item", $lft );

            $rgt++;

            //create
            $directoryModel->save(array('name'=>$item,'lft'=>$lft,'rgt'=> $rgt, "path" => $path_dest . DIRECTORY_SEPARATOR . $item));
            $lft++;
        }
    }
    return $rgt;
}


$directoryModel   = new Directories();
$ret = $directoryModel->init();

if($ret) {
    $dir = $directoryModel->fetchOneByNameAndParent("ROOT");
    $rgt = copyDirectory('upload', $path_source, $path_dest ,1);
    $dir->rgt = ++$rgt;
    $ar = json_decode(json_encode($dir), true);
    $directoryModel->save($ar);
}

*/