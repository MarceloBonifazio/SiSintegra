<?php

class RaizController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->view->baseUrl = $this->_request->getBaseUrl();
    	$this->_session = new Application_Model_SessaoUsuario();
    	$this->_session->iniciaSessao("my_session","session");
    }

    public function indexAction()
    {
      $_session = $this->_session->getSessao("session");
      $this->view->usuario =  $_session->usuario;
   	  if($_session->usuario == 'adm'){
   	  	$consultas = new Application_Model_Consulta();
   	  	$lista = $consultas->listar(1, "");
   	  	$this->view->listaConsulta = $lista;
   	  }elseif ($_session->usuario == ''){
   	  	$this->_redirect(index);
   	  }else{
   	  	$consultas = new Application_Model_Consulta();
   	  	$lista = $consultas->listar(0, $_session->id);
   	  	$this->view->listaConsulta = $lista;
   	  }    
    }

    public function removeAction()
    {
		$id = $this->_getParam('id', 0);
		$consulta = new Application_Model_Consulta();                 
		$consulta->deleteConsulta($id);
		$this->_helper->redirector(index);
    } 
    
    public function pesquisarAction()
    {
    	if($this->getRequest()->isPost())
    	{ 
    		$_session = $this->_session->getSessao("session");
    		$this->view->usuario =  $_session->usuario;
    		$cnpj = $this->getRequest()->getParam("se_cnpj","");
    		$base_url='http://www.sintegra.es.gov.br' ;
    		$endpoint='/resultado.php' ;
    		$user_data = array("num_cnpj"=>$cnpj,"botao"=>"Consultar");
    		$client = new Zend_Rest_Client($base_url);
    		$json = $this->extrairDados($response = $client->restPost($endpoint,$user_data));
    		
    		$data = array(
    				'idusuario' => $_session->id,
    				'cnpj' => $cnpj,
    				'resultado_json' => $json
    		);
    		
    		$consultas = new Application_Model_Consulta();
    		if($consultas->gravar($data))
    			$this->_helper->redirector(index);
    		else
    			echo $this->view->resp = "<h1><center>Erro!</center></h1>";
    	}
	}
	
	private function extrairDados($html)
	{
		$re = "/<div id=\"conteudo\"[^>]*>(.*?)<\\/div>/si";
		preg_match_all($re, $html, $matches);
		$html = $matches[0][0];
		$re = "/<table width=\"100%\" border=\"0\" cellspacing=\"[12]\" cellpadding=\"[12]\">[^>]*>(.*?)<\\/table>/si";
		preg_match_all($re, $html, $identificacao);
		
		if (empty($identificacao[0][0])) {
			return '{"sintegra_es": "CNPJ não existe em nossa base de dados"}';
		}
		
		$re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si";
		preg_match_all($re, $identificacao[0][0], $valores);
		$valores_indetificacao = str_replace('&nbsp;', '', array_map("strip_tags", $valores[0]));
		
		$re = "/<td (class=\"valor\"|width=\"30%\")[^>]*>(.*?)<\\/td>/si";
		preg_match_all($re, $identificacao[0][1], $valores);
		$valores_endereco = str_replace('&nbsp;', '', array_map("strip_tags", $valores[0]));
		
		$re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si";
		preg_match_all($re, $identificacao[0][2], $valores);
		$valores_infs_complementares = str_replace('&nbsp;', '', array_map("strip_tags", $valores[0]));
		preg_match_all($re, $identificacao[0][3], $valores);
		$valores_infs_complementares2 = str_replace('&nbsp;', '', array_map("strip_tags", $valores[0]));
		preg_match_all($re, $identificacao[0][5], $valores);
		$valores_infs_complementares3 = str_replace('&nbsp;', '', array_map("strip_tags", $valores[0]));
		$json = 
		'{"sintegra_es":
			{
				"identidade":
					{
						"cnpj":"' . $valores_indetificacao[0] . '",
						"inscricao_estadual":"' . $valores_indetificacao[1] . '",
						"razao_social":"' . $valores_indetificacao[2] . '"
					},
				"endereco":
					{
						"logradouro:":"' . $valores_endereco[0] . '",
						"numero":"' . $valores_endereco[1] . '",
						"complemento":"' . $valores_endereco[2] . '",
						"bairro":"' . $valores_endereco[3] . '",
						"municipio":"' . $valores_endereco[4] . '",
						"uf":"' . $valores_endereco[5] . '",
						"cep":"' . $valores_endereco[6] . '",
						"telefone":"' . $valores_endereco[7] . '"
					},
				"informacoes_complementares":
					{
						"atividade_economica":"' . $valores_infs_complementares[0] . '",
						"data_inicio_atividade":"' . $valores_infs_complementares[1] . '",
						"situacao_cadastral_vigente":"' . $valores_infs_complementares[2] . '",
						"data_desta_situacao_cadastral":"' . $valores_infs_complementares[3] . '",
						"regime_apuracao":"' . $valores_infs_complementares2[0] . '",
						"emitente_nfe_desde":"' . $valores_infs_complementares3[0] . '"
	
					}
			}
		}';
		return trim($json);
	}
}


