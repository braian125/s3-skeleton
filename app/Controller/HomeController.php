<?php
namespace App\Controller;
use Slim\Views\Twig as View;
use App\Models\Premio;

class HomeController extends Controller
{
	
	public function index($request, $response)	
	{

		$premio = Premio::find(1);
		var_dump($premio->nombre_premio);
		die();
		return $this->view->render($response, 'home.twig');		
	}
}