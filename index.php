<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'autoload.php';

class Router
{
    private $routes = [];
    private $prefix;

    public function __construct($prefix = '')
    {
        $this->prefix = trim($prefix, '/');
    }

    public function addRoute($uri, $controllerMethod)
    {
        $this->routes[trim($uri, '/')] = $controllerMethod;
    }

    public function route($url)
    {
        // Suppression du préfixe du début de l'URL
        if ($this->prefix && strpos($url, $this->prefix) === 0) {
            $url = substr($url, strlen($this->prefix) + 1);
        }

        // Suppression des barres obliques en trop
        $url = trim($url, '/');

        // Vérification de la correspondance de l'URL à une route définie
        foreach ($this->routes as $route =>$controllerMethod) {
            //verif des parties de l'URL
            $routeParts = explode('/',$route);
            $urlParts = explode('/',$url);

            if(count($urlParts) == count($routeParts)){
                $params = [];
                $isMatch = true;
                foreach ($routeParts as $index=>$part){
                    if(preg_match('/^{\w+}$/',$part)){
                        $params[] = $urlParts[$index];
                    }elseif($part !== $urlParts[$index]){
                        $isMatch=false;
                        break;
                    }
                }
            
                if($isMatch){

                
                    // Extraction du nom du contrôleur et de la méthode
                    list($controllerName, $methodName) = explode('@', $controllerMethod);

                    // Instanciation du contrôleur et appel de la méthode
                    $controller = new $controllerName();
                    call_user_func_array([$controller,$methodName],$params);
                    return;
                }
            }
        } 
        // Gestion des erreurs (page 404, etc.)
        require_once('views/404.php');
    }
}

// Instanciation du routeur
$router = new Router('R3PHP/TaskManagerMVC');

// Ajout des routes
$router->addRoute('', 'HomeController@index'); // Pour la racine
$router->addRoute('tasks', 'TaskController@index'); // Pour la racine
$router->addRoute('about','AboutController@index');
$router->addRoute('tasks/{id}','TaskController@perTask');

// Appel de la méthode route
$router->route(trim($_SERVER['REQUEST_URI'], '/'));
