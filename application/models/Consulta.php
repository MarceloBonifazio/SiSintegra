<?php

class Application_Model_Consulta extends Zend_Db_Table_Abstract
{
    protected $_name = "sintegra";
	
	public function listar($var, $idusuario)
	{
		if($var){
			$select = $this->select()
			->from(array('s' => 'sintegra'), array('s.*'))
			->join(array('u' => 'usuario'),'s.idusuario = u.id',array('u.usuario', 'u.senha'))
			->setIntegrityCheck(false);
			return $this->fetchAll($select);
		}else{
			$select = $this->select()
			->from(array('s' => 'sintegra'), array('s.*'))
			->join(array('u' => 'usuario'),'s.idusuario = u.id',array('u.usuario', 'u.senha'))
			->where('u.id = ?',$idusuario)
			->setIntegrityCheck(false);
			return $this->fetchAll($select);
		}
	}
	
	public function deleteConsulta($id)
	{
		$this->delete('id ='.$id);
	}
	
    public function gravar($data)
    {
        return $this->insert($data);
    }
}