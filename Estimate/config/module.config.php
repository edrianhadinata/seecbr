<?php
	
	return array(
		
		'controllers'=>array(
			'invokables'=>array(
				'Estimate\Controller\Estimate'=>'Estimate\Controller\EstimateController',
			),
		),
		
		
		'view_manager'=>array(
			'template_path_stack'=>array(
				'estimate'=> __DIR__ .'/../view',
			),
		),
		
	);
	
	

?>