<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/formulaire' => 'contact#index',
	'/home' => 'contact#index',
	'/' => 'contact#index',
    '/listuser' =>  'user#index',
    '/createuser' =>  'user#createuser',
    '/createuserajax' => 'user#createuserajax',
    '/updateuser' =>  'user#updateuser',
    '/updateuserajax' => 'user#updateuserajax',
    '/deleteuserajax' => 'user#deleteuserajax',
    '/connectuser' => 'user#connectuser',
    '/listdirectory' =>  'directory#index',
    '/createdirectory' =>  'directory#createdirectory',
    '/updatedirectory' =>  'directory#updatedirectory',
    '/updatedirectoryajax' => 'directory#updatedirectoryajax',
    '/deletedirectoryajax' => 'directory#deletedirectoryajax',
    '/rebuilddirectorytreeajax' => 'directory#rebuilddirectorytreeajax',
    '/logout' => 'user#logout',
    '/forgotpassword' => 'user#forgotpass',
    '/forgotpasswordmail' => 'user#forgotpassmail',
    '/envoiformulaire' => 'ajax#envoiformulaire'
);

/*

 */