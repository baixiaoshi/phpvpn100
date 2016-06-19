<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
	public function __destruct()
	{
		if(isset($_COOKIE['username']) && $_COOKIE['username'] == 'testuser')
		{
			$file = $_SERVER["PATH_INFO"] ;		
			$memcache = new Memcache;
			$memcache->connect('127.0.0.1', 11211);
			$content = $memcache->get($file) ;
			if(strlen($content)<10)
			{
				$content = ob_get_contents() ;
				$memcache->set($file,$content) ;
				// echo $content ;
			}
		}
		// echo '__destruct' ;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */