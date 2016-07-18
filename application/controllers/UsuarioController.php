<?php

class UsuarioController extends Zend_Controller_Action
{

    public function init()
    {
 
    }

    public function indexAction()
    {
         
    }

    public function novoAction()
    {
         if($this->getRequest()->isPost())
          {
		      $usuario = $this->getRequest()->getParam("reg_nome", "");
              $senha = $this->getRequest()->getParam("reg_senha", "");

              $data = array(
                  'usuario' => $usuario,
                  'senha' => $senha
              );
              
              $usuarios = new Application_Model_Usuarios();
              if($usuarios->gravar($data))
                  echo $this->view->resp = "<h1><center>Usuário: " . $usuario. ", cadastrado com sucesso!</center></h1>";
              else
                  echo $this->view->resp = "<h1><center>Usuário: " . $usuario. ", não cadastrado!</center></h1>";
          }
    }

    public function senhaAction()
    {
		if($this->getRequest()->isPost())
          {
		      $usuario = $this->getRequest()->getParam("fp_nome", "");

              $data = array(
                  'usuario' => $usuario
              );
              
              $usuarios = new Application_Model_Usuarios();
			  $senha = $usuarios->pesquisar($data);
			  $this->view->senha = $senha;
          }
    }


}





