<?php

class IndexController extends Zend_Controller_Action
{
	private $_session = null;
    public function init()
    {
        /* Initialize action controller here */
    	$this->view->baseUrl = $this->_request->getBaseUrl();
    	$this->_session = new Application_Model_SessaoUsuario();
    	$this->_session->iniciaSessao("my_session","session");
    }

    public function indexAction()
    {
       $_session = $this->_session->getSessao("session");
       if($_session->usuario != '')
            $this->_redirect(raiz);
    }


}

