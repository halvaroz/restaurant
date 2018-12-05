<?php

class FrontController{
    private $http;
    private $viewData;


    public function __construct(){
        $this->http = new Http();

        $this->viewData =[
            'template'  => null,
            'variables' =>
            [
                'requestUrl' => $_SERVER['SCRIPT_NAME'],
                'wwwUrl'     => str_replace('index.php', 'application/www', $_SERVER['SCRIPT_NAME'])
            ]
        ];
    }

    public function buildContext(Configuration $configuration){
        $filters = $configuration->get('library', 'intercepting-filters', array());

        foreach($filters as $filterName){
            if(empty($filterName) == true){
                continue;
            }

            $filterName = $filterName.'Filter';
            $filter = new $filterName();

            if ($filter instanceof InterceptingFilter){
                $this->viewData['variables'] = array_merge
                (
                    $this->viewData['variables'],
                    (array) $filter->run($this->http, $_GET, $_POST)
                );
            }
        }

        return $this->http->getRequestPath();
    }

    public function renderErrorView($_fatalErrorMessage){
        extract($this->viewData['variables'], EXTR_OVERWRITE);

        include 'ErrorView.phtml';
        die();
    }

    public function renderView(){
        $this->viewData['template'] = WWW_PATH.
            $this->http->getRequestPath().DIRECTORY_SEPARATOR.
            $this->http->getRequestFile().'View.phtml';

        if(array_key_exists('_form', $this->viewData['variables']) == true){
            if($this->viewData['variables']['_form'] instanceof Form){
              
                $form = $this->viewData['variables']['_form'];

                if($form->hasFormFields() == false){
                    $form->build();
                }

                $this->viewData['variables'] = array_merge(
                    $this->viewData['variables'],
                    $form->getFormFields()
                );

                $this->viewData['variables']['errorMessage'] = $form->getErrorMessage();
            }

            unset($this->viewData['variables']['_form']);
        }

 
        extract($this->viewData['variables'], EXTR_OVERWRITE);

        if(array_key_exists('_raw_template', $this->viewData['variables']) == true){
            unset($this->viewData['variables']['_raw_template']);
            include $this->viewData['template'];
        }
        else{
            include WWW_PATH.'/LayoutView.phtml';
        }
    }

    public function run(){
        $controllerClass = $this->http->getRequestFile().'Controller';

        if(ctype_alnum($controllerClass) == false){
            throw new ErrorException
            (
                "Nom de contrôleur invalide : <strong>$controllerClass</strong>"
            );
        }

        $controller = new $controllerClass();


        if($this->http->getRequestMethod() == 'GET'){
            $fields = $_GET;
            $method = 'httpGetMethod';
        }
        else{
            $fields = $_POST;
            $method = 'httpPostMethod';
        }

        if(method_exists($controller, $method) == false){
            throw new ErrorException
            (
                'Une requête HTTP '.$this->http->getRequestMethod().' a été effectuée, '.
                "mais vous avez oublié la méthode <strong>$method</strong> dans le contrôleur ".
                '<strong>'.get_class($controller).'</strong>'
            );
        }

        $this->viewData['variables'] = array_merge(
            $this->viewData['variables'],
            (array) $controller->$method($this->http, $fields)
        );
    }
}