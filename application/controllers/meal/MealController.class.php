<?php


class MealController{
	public function httpGetMethod(Http $http, array $queryFields){
        if(array_key_exists('id', $queryFields) == true){
            if(ctype_digit($queryFields['id']) == true){
                $mealModel = new MealModel();
				$meal = $mealModel->find($queryFields['id']);
				$http->sendJsonResponse($meal);
            }
        }

        $http->redirectTo('/');
	}
}