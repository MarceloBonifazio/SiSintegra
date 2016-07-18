<?php

class Application_Model_SessaoUsuario
{
    function iniciaSessao($nome_global, $nome_sessao){
        $_session = new Zend_Session_Namespace($nome_global);
        Zend_Registry::set($nome_sessao, $_session);
    }
    function getSessao($nome_sessao){
   		$_session = Zend_Registry::get($nome_sessao);
   		return $_session;
    }
}

