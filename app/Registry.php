<?
ini_set('display_errors',1);
error_reporting(E_ALL);

function d($arr) {
    print_r('<pre>');
    print_r($arr);
    print_r('</pre>');
}

function autoload($classname) {
    $classname = str_replace("\\", DIRECTORY_SEPARATOR, $classname);
    $filename = $_SERVER['DOCUMENT_ROOT'].'/app/Class/'.$classname .'.php';
    
    include_once $filename;
}

// регистрируем загрузчик
spl_autoload_register('autoload');

use Finance\Controller;

if(isset($_REQUEST['url'])) {
    $url = $_REQUEST['url'];

    if($url !== NULL){
        $controller = new Controller();
        if(is_callable(array($controller, $url)))
            $controller->$url();
    }
}