<?php

class MicroKernel{
    private $applicationPath;
    private $configuration;
    private $controllerPath;

    public function __construct(){
        $this->applicationPath = realpath(ROOT_PATH.'/application');
        $this->configuration   = new Configuration();
        $this->controllerPath  = null;
    }

    public function bootstrap(){
        spl_autoload_register([ $this, 'loadClass' ]);

        $this->configuration->load('database');
        $this->configuration->load('library');

        error_reporting(E_ALL);
        set_error_handler(function($code, $message, $filename, $lineNumber){
            throw new ErrorException($message, $code, 1, $filename, $lineNumber);
        });

        return $this;
    }

    public function loadClass($class){
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        if(substr($class, -10) == 'Controller'){
            $filename = "$this->controllerPath/$class.class.php";
        }
        else if(substr($class, -4) == 'Form'){
            $filename = "$this->applicationPath/forms/$class.class.php";
        }
        elseif(substr($class, -5) == 'Model'){
            $filename = "$this->applicationPath/models/$class.class.php";
        }
        else{
            $filename = "$this->applicationPath/classes/$class.class.php";
        }

        if(file_exists($filename) == true){
            include $filename;
        }
        else{
            if($this->configuration->get('library', 'autoload-chain', false) == false){
                throw new ErrorException(
                    "La classe <strong>$class</strong> ne se trouve pas ".
                    "dans le fichier<br><strong>$filename</strong>"
                );
            }
        }
    }

    public function run(FrontController $frontController){
        try{
            ob_start();

            $requestPath = $frontController->buildContext($this->configuration);

            $this->controllerPath = "$this->applicationPath/controllers$requestPath";

            $frontController->run();
            $frontController->renderView();

            ob_end_flush();
        }
        catch(Exception $exception){
            ob_clean();

            $frontController->renderErrorView(
                implode('<br>',[
                    $exception->getMessage(),
                    "<strong>Fichier</strong> : ".$exception->getFile(),
                    "<strong>Ligne</strong> : ".$exception->getLine()
                ])
            );
        }
    }
}