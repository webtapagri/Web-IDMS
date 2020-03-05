<?php 

if (App::environment('local')) {

	return [
		
		// 'uri'	=> 'http://app.tap-agri.com/mobileinspection/ins-msa-hectarestatement/',
		// 'uri'	=> 'http://http://msadev.tap-agri.com/api/v1.0/master/',
		'uri'	=> 'http://tap-ldapdev.tap-agri.com/master/',
		
		
	];


}  else{
	return [
		'uri'	=> 'http://tap-ldap.tap-agri.com/master/',
		
		
	];
}