<?php
namespace App\Controller\Auth;
use App\Controller\Controller;
use App\Models\User;

class AuthController extends Controller
{
	public function signin($request, $response)	
	{		
		return $this->view->render($response, 'auth/layout.twig');		
	}

	public function postSignin($request, $response)
	{
		var_dump($request->getParams());
	}
}