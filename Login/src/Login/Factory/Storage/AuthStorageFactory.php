<?php
	//AuthStorageFactory.php
	
	namespace Login\Factory\Storage;
	
	use Zend\ServiceManager\FactoryInterface;
	use Zend\ServiceManager\ServiceLocatorInterface;
	use Login\Storage\AuthStorage;
	
	class AuthStorageFactory implements FactoryInterface
	{
		public function createService(ServiceLocatorInterface $serviceLocator)
		{
			$storage = new AuthStorage('my_storage_namespace');
			$storage->setServiceLocator($serviceLocator);
			$storage->setDbHandler();
			return $storage;
		}
	}
	
?>