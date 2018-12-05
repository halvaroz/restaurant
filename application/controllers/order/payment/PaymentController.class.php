<?php


class PaymentController{
	public function httpGetMethod(Http $http, array $queryFields){
        $userSession = new UserSession();
        if($userSession->isAuthenticated() == false){
            $http->redirectTo('/user/login');
        }

		if(array_key_exists('id', $queryFields) == true){
			if(ctype_digit($queryFields['id']) == true){
                $orderModel = new OrderModel();
				$order = $orderModel->find($queryFields['id']);

                $customerModel = new UserModel();
                $customer = $customerModel->find($order['User_Id']);

				return
				[
                    'customer'   => $customer,
					'order'      => $order,
					'orderLines' => $orderModel->findOrderLines($queryFields['id']),
				];
			}
		}

        // En cas d'erreur, redirection vers la page d'accueil.
		$http->redirectTo('/');
	}

	public function httpPostMethod(Http $http){
        /*
         * Ici il y aurait le code qui implémente un paiement PayPal ou avec une banque.
         */

		// Redirection vers la page de succès du processus de paiement.
		$http->redirectTo('/order/payment/success');
	}
}