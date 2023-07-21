<?php

namespace Api;

class Api
{
    public static function routing($current_link, $urls)
    {
        try {

            foreach($urls as $index=>$url) 
            {
                if($index == $current_link) {
                    continue;
                }
                //getting
                $routeElement=explode('@', $url[0]);
                $className=$routeElement[0];
                $function=$routeElement[1];


                //check
                if(!file_exists("controllers/".$className.".php")) {
                    var_dump('Controller not found');
                    return 'Controller not found';
                }
                $class="api\controllers\\$className";
                $object=new $class();
                //var_dump($class,$object);
               
                $object->$function();
            }
        } catch(\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
