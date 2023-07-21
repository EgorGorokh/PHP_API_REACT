<?php

error_reporting(E_ALL);
ini_set('display_errors','1');


require "services/DB.php";
use services\DB;

require "Api.php";
use Api\Api;




require('controllers/PostsController.php');


$current_link=$_SERVER['REQUEST_URI'];




///handing quert string
if(str_contains($current_link,'?')){
$current_link=explode('?',$current_link);    //[0]
}
 



$urls=[
    '/ReactPHPAPI/api/posts'=>['PostsController@getPostsFromDatabase'],
   // '/ReactPHPAPI/api/searchResult'=>['PostsController@getSearchResults']
];
//check

$availableRoutes=array_keys($urls);

if(!in_array($current_link[0],$availableRoutes)){    //[0]
header('HTTP/1.0 404 Not Found');
exit;
}

\Api\Api::routing($current_link,$urls);











