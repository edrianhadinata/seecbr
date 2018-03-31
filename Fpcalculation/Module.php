<?php
	//module.php
	
	namespace Fpcalculation;
	
	use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
	use Zend\ModuleManager\Feature\ConfigProviderInterface;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	use Fpcalculation\Model\Fpcalculation;
	use Fpcalculation\Model\FpcalculationTable;
	
	class Module implements AutoloaderProviderInterface,ConfigProviderInterface
	{
		public function getAutoloaderConfig()
		{
			return array(
				'Zend\Loader\ClassMapAutoloader'=>array(
					__DIR__ .'/autoloader_classmap.php',
				),
				'Zend\Loader\StandardAutoloader'=>array(
					'namespaces'=>array(
						__NAMESPACE__ =>__DIR__ .'/src/'. __NAMESPACE__,
					),
				),
			);
		}
		
		public function getServiceConfig()
		{
			return array(
				'factories'=>array(
					'Fpcalculation\Model\FpcalculationTable'=>function($sm){
						$tableGateway=$sm->get('FpcalculationTableGateway');
						$table=new FpcalculationTable($tableGateway);
						return $table;
					},
					'FpcalculationTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype = new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new Fpcalculation());
						return new TableGateway('answer',$dbAdapter,null,$resultSetPrototype);
					},
				),
			);
		}
		
		public function getConfig()
		{
			return include __DIR__ .'/config/module.config.php';
		}
		
		
		
	}	
	
	

?>