<?php

class AutentificaController extends Zend_Controller_Action
{
    private $_session = null;
    private $_usuarios = null;    
    public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
      	$this->_usuarios = new Application_Model_DbTable_Usuarios();
      	$this->_usuarios = new Application_Model_DbTable_Usuarios();
      	$this->_session = new Application_Model_SessaoUsuario();
      	$this->_session->iniciaSessao("my_session","session");
    }

    public function indexAction()
    {
        // action body
    }

    public function loginDataAction()
    {
        if( $this->getRequest()->isXmlHttpRequest() ){
                $this->_helper->viewRenderer->setNoRender();
			    $usuario = $this->_request->getPost("usuario");
            	$senha = $this->_request->getPost("senha");
            	$autentifica = $this->_usuarios->login($usuario, $senha);
            	if(($autentifica != "Senha incorreta")&&($autentifica != "Usuário não existe")){
            		$_session = $this->_session->getSessao("session");
            		$_session->usuario= $usuario;
            		$_session->id= $autentifica;
            		
            		echo 1;
            	}
            	else
            		echo $autentifica;
        }   
    }

    public function logoutDataAction()
    {
        $_session = $this->_session->getSessao("session");
        $_session->usuario = '';
        $this->_redirect('/index');
    }


}





