<?php
	
	//namefile : AuthControllerServiceFactory.php
	
	namespace Login\Factory\Controller;
	
	use Zend\ServiceManager\FactoryInterface;
	use Zend\ServiceManager\ServiceLocatorInterface;
	use Login\Controller\AuthController;
	
	class AuthControllerServiceFactory implements FactoryInterface
	{
		public function createService(ServiceLocatorInterface $serviceLocator)
		{
			$authService = $serviceLocator->getServiceLocator()->get('AuthService');
			$controller = new AuthController($authService);
			return $controller;
		}
	
	}
	


?>