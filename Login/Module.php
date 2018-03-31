<?php
	
	//Module.php
	
	namespace Login;
	
	use Zend\Mvc\ModuleRouteListener;
	use Zend\Mvc\MvcEvent;
	
	class Module
	{
	
		public function onBootstrap(MvcEvent $e)
		{
			/*$eventManager        = $e->getApplication()->getEventManager();
			$moduleRouteListener = new ModuleRouteListener();
			$moduleRouteListener->attach($eventManager);
			$eventManager->attach('route', array($this, 'checkSession'));
			*/
			 $em = $e->getApplication()->getEventManager();
			$em->attach('route', array($this, 'checkSession'));
		}
		
		public function checkSession(MvcEvent $e)
		{
			
			$sm = $e->getApplication()->getServiceManager();
			if ( ! $sm->get('AuthService')->getStorage()->getSessionManager()
				->getSaveHandler()->read($sm->get('AuthService')->getStorage()->getSessionId())) {
				if ($e->getRouteMatch()->getParam('controller') != 'Login\Controller\Auth') {
					//$e->getRouteMatch()->setParam('controller', 'Login\Controller\Auth');
					 return $e->getTarget()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e)  {
                    $controller = $e->getTarget();
                    $controller->redirect()->toRoute('auth');
                }, -11);
				}
			}
			
			      
			$userku = \Zend\Json\Json::decode($sm->get('AuthService')->getStorage()->getSessionManager()->getSaveHandler()->read($sm->get('AuthService')->getStorage()->getSessionId()), true);
			$viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
			$viewModel->myUser=$userku;
			
		}
		
		public function getConfig()
		{
			return include __DIR__ . '/config/module.config.php';
		}
		
		public function getAutoloaderConfig()
		{
			return array(
				'Zend\Loader\StandardAutoloader'=>array(
					'namespaces'=>array(
						__NAMESPACE__ => __DIR__ . '/src/'. str_replace('\\','/', __NAMESPACE__),
					),
				),
			);
		}
		
		
	}

?>