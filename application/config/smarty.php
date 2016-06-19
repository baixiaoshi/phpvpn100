<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'smarty_template_dir'	=>	APPPATH.'views/templates',
	'smarty_compile_dir'	=>	APPPATH.'views/templates_c',
	'left_delimiter'	=>	'<{',
	'right_delimiter'	=>  '}>',
	'debugging'			=> false,
	'caching'			=> false,
	'cache_dir'			=>      APPPATH.'views/cache_dir'
	);