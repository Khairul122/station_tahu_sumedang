<?php
session_start();
require_once 'koneksi.php';

function autoload($className) {
    if (strpos($className, 'Model') !== false) {
        $file = 'models/' . $className . '.php';
    } elseif (strpos($className, 'Controller') !== false) {
        $file = 'controllers/' . $className . '.php';
    }
    
    if (isset($file) && file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('autoload');

function loadView($viewName, $data = []) {
    extract($data);
    $viewFile = 'views/' . $viewName . '.php';
    if (file_exists($viewFile)) {
        include $viewFile;
    }
}

function loadTemplate($templateName) {
    $templateFile = 'template/' . $templateName . '.php';
    if (file_exists($templateFile)) {
        include $templateFile;
    }
}

$controller = $_GET['controller'] ?? 'Auth';
$action = $_GET['action'] ?? 'index';

$controllerName = ucfirst($controller) . 'Controller';

if (class_exists($controllerName)) {
    $controllerInstance = new $controllerName();
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        http_response_code(404);
        echo "Action tidak ditemukan: $action di $controllerName";
    }
} else {
    http_response_code(404);
    echo "Controller tidak ditemukan: $controllerName";
}
?>