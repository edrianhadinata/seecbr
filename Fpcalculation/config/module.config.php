<?php

//module.config.php
//configuration file

return array(
	'controllers'=>array(
		'invokables'=>array(
			'Fpcalculation\Controller\Fpcalculation'=>'Fpcalculation\Controller\FpcalculationController',
		),
	),
	
	
	'router'=>array(
		'routes'=>array(
			'fpcalculation'=>array(
				'type'=>'segment',
				'options'=>array(
					'route'=>'/fpcalculation[/:action][/:id]',
					'constraints'=>array(
						'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
						'id'=>'[0-9]',
					),
					'defaults'=>array(
						'controller'=>'Fpcalculation\Controller\Fpcalculation',
						'action'=>'index',
					),
				),
			),
			
		),
	),
	
	'view_manager'=>array(
		'template_path_stack'=>array(
			'Fpcalculation'=>__DIR__ .'/../view',
		),
	),
	
);
	
	

?>