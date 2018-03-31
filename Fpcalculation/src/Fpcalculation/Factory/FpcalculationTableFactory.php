<?php
	
	namespace Fpcalculation\Factory;
	
	use Fpcalculation\Model\FpcalculationTable;
	use Zend\ServiceManager\FactoryInterface;
	use Zend\ServiceManager\ServiceLocatorInterface;
	
	class FpcalculationTableFactory implements FactoryInterface
	{
		public function createService(ServiceLocatorInterface $serviceLocator)
		{
			$realServiceLocator=$serviceLocator->getServiceLocator();
			$postService=$realServiceLocator->get('Zend\Db\Adapter\AdapterInterface');
			return new FpcalculationTable($postService);
		}
	}
	
?>