<?php

namespace models;

class Routing
{

    public static function getRoutingType($hasArgv)
    {

    }

    public function execute()
    {
        $paths = explode('/', ltrim($_SERVER["PATH_INFO"], '/'));

        list($className, $method) = $paths;

        $method = explode('-', $method);

        for($i=1;$i<=count($method);$i++) {
            if (isset($method[$i])) {
                $method[$i] = ucfirst($method[$i]);
            }
        }

        $method = implode('', $method);


        $class = 'controllers\\' . ucfirst($className) . 'Controller';

        try {
            if (!class_exists($class)) {
                $class = "modules\\" . $className . "\controllers\\" . ucfirst($className) . 'Controller';
            }
        }catch(\Exception $e){
            $class = "modules\\" . $className . "\controllers\\" . ucfirst($className) . 'Controller';
        }

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {

            $data = json_decode(file_get_contents('php://input'), true);

        } elseif ($_SERVER["REQUEST_METHOD"] == 'GET') {
            $data = parse_url($_SERVER["REQUEST_URI"]);
            parse_str($data['query'], $params);
            $data = $params;
        }

        try {

            $obj = new $class($_SERVER["REQUEST_METHOD"], $method);

            return json_encode(call_user_func_array([$obj,$method],$data));

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
