<?php


class BasketController{
	public function httpPostMethod(Http $http, array $formFields){
        $userSession = new UserSession();
        if($userSession->isAuthenticated() == false){
            $http->redirectTo('/user/login');
        }

        if(array_key_exists('basketItems', $formFields) == false){
            $formFields['basketItems'] = [];
        }

        return
        [
            '_raw_template' => true,   
            'basketItems'   => $formFields['basketItems']
        ];
	}
}