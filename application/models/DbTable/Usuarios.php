<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
    
    function login($usuario, $senha){
        $usuario = $this->fetchRow("usuario = '$usuario'");
        if($usuario){
            $senha = $this->fetchRow("senha = '$senha'");
            //echo var_dump($usuario->id);
            if($senha){
                return $usuario->id;
            }else
                return "Senha incorreta";    
        }else   
            return "Usuário não existe";
    }
}

