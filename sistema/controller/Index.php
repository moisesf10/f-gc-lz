<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Index extends Controller
{

	
    function __construct()
	{
        parent::__construct();
        //print_r($password = Blowfish::crypt('froodo')); exit;
	}



	/**
	 * Executado caso nenhum ação tenha sido passada para o controlador
	 * @access public
	 * @abstract
	 * @return void
	 */
	public function ActionDefault()
	{
        $this->setView('index/index');
        $this->setHeaderInclude(false);
        $this->setFooterInclude(false);
        $this->showContents();
	}

	

}

?>