<?php

class Application_Model_Usuarios extends Zend_Db_Table_Abstract
  {
      protected $_name = "usuario";
      public function gravar($data)
      {
          return $this->insert($data);
      }
	  
      public function pesquisar($data)
      {
          $select = $this->select()->from('usuario')->columns('senha')->where('usuario = ?', $data);
          return $this->fetchAll($select);
      }
  }