<?php


class BookingController{
	public function httpGetMethod(Http $http){
		$userSession = new UserSession();
		if($userSession->isAuthenticated() == false){
			$http->redirectTo('/user/login');
		}
	}

	public function httpPostMethod(Http $http, array $formFields){
        $userSession = new UserSession();
        if($userSession->isAuthenticated() == false{
            $http->redirectTo('/user/login');
        }

        $userId = $userSession->getUserId();

        $bookingModel = new BookingModel();
		$bookingModel->create
		(
			$userId,
			$formFields['bookingYear'].'-'.
                $formFields['bookingMonth'].'-'.
				$formFields['bookingDay'],
		    $formFields['bookingHour'].':'.
                $formFields['bookingMinute'],
			$formFields['numberOfSeats']
		);

		$http->redirectTo('/');
	}
}