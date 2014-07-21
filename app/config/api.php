<?php

// API Settings

return array(

	// api.settings
	
	'settings' => array(
			
		'base_url' => ( 
						(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
	  					|| (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
					  ) ? 
					  'https://cloudwalkers-api.local/' : 'http://cloudwalkers-api.local/',
			
		'storage_url' => ( 
						(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
	  					|| (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
					  ) ? 
					  'https://cloudwalkers-storage.local/' : 'http://cloudwalkers-storage.local/',
		
		'cdn_remote' => 'http://images.cloudwalkers.be/',
			
		'cdn_local' => '/tmp_images/'
			
	)


);