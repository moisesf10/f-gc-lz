<?php

namespace Gauchacred\controller;



//use \controller\Controller as Controller;
//use \library\php\Blowfish as Blowfish;

use Gauchacred\model\Banco as Banco;
use Gauchacred\model\Entidade as Entidade;
use Gauchacred\model\ContaBancariaCliente as ContaBancariaCliente;
use Gauchacred\model\Cliente as ClienteModel;
use Gauchacred\model\Agenda as Agenda;
use Gauchacred\library\php\Utils as Utils;
use Gauchacred\model\Usuario as Usuario;
use Gauchacred\library\php\ApiFile;
use Gauchacred\model\Telemarketing;
/**
 * @author moises
 * @version 1.0
 * @created 13-set-2015 23:08:59
 */
class Cliente extends Controller
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
        
    }
    
    public function pesquisar()
    {
        
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever')
           )
            \Application::print404();
        $this->setView('clientes/index');
        
        
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes_todos', 'ler')  )
            $qualquerCliente = false;
        else
            $qualquerCliente = true;
        
        
        $cliente = new ClienteModel();
        $result = $cliente->carregar('%', 10, 1, 'desc', $qualquerCliente);
            if ($result !== false)
                $this->setParams('cliente', $result);
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenio', $result);
        
        $usuario = new Usuario();
        $result = $usuario->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        
          $this->showContents();
    }
    
    public function carregarPesquisa(){
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever')
           )
        {
            echo json_encode(array('success'=>false, 'message'=>'Usuário sem permissão'));
            exit;
        }
        
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes_todos', 'ler')  )
            $qualquerCliente = false;
        else
            $qualquerCliente = true;
        
        $cpf = ($_REQUEST['cpf'] != '')? $_REQUEST['cpf'] : null;
        $nome = ($_REQUEST['nome'] != '') ? $_REQUEST['nome'] : null;
        $convenio = ($_REQUEST['convenio'] != '') ? $_REQUEST['convenio'] : null;
        $nascimentoInicial = ($_REQUEST['nascimentoinicial'] != '') ? Utils::formatStringDate( $_REQUEST['nascimentoinicial'], 'd/m/Y','Y-m-d') : null;
        $nascimentoFinal = ($_REQUEST['nascimentofinal'] != '') ? Utils::formatStringDate( $_REQUEST['nascimentofinal'], 'd/m/Y','Y-m-d') : null;
        $mes = ($_REQUEST['mes'] != '') ? $_REQUEST['mes'] : null;
        $limit = ($_REQUEST['limit'] != '') ? $_REQUEST['limit'] : null;
        $idUsuario = ($_REQUEST['usuario'] != '') ? $_REQUEST['usuario'] : null;
       
        $cliente = new ClienteModel();
          $result = $cliente->getResumo($cpf, $nome, $idUsuario, $convenio, $nascimentoInicial, $nascimentoFinal, $mes, $limit, $qualquerCliente);
        
        if($result == false)
            $return = array();
        else
            $return = $result;
        
        echo json_encode($return);
        
    }
    
    
	public function cadastrar()
	{
         if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever')
           )
            \Application::print404();
        
        $this->setView('clientes/criar_cadastro');
        
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes_todos', 'ler')  )
            $qualquerCliente = false;
        else
            $qualquerCliente = true;
        
        $banco = new Banco();
        $result = $banco->listarBancos();
        if ($result !== false)
            $this->setParams('bancos', $result);
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenio', $result);
        
        $contaBancaria = new ContaBancariaCliente();
        $result = $contaBancaria->listarTipos();
        if ($result !== false)
            $this->setParams('tiposdecontas', $result);
        
        
        $cpf = (\Application::getUrlParams(0) == null) ? null : Utils::formatCpfHowString(\Application::getUrlParams(0));
        $cliente = new ClienteModel();
        if ($cpf != null)
        {
            $result = $cliente->carregar($cpf, 1, 1, 'desc', $qualquerCliente);
            if ($result !== false)
                $this->setParams('cliente', $result);
            else
                 $this->setParams('cliente', null);
        }
        else
            $this->setParams('cliente', null);
        
        
        $this->showContents();
	}
    
    
    
    
    
    
    
    
    public function salvarCliente()
    {
        
       if ( ! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'escrever')   )
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }

        $cpf = $_REQUEST['cpf'];
        $observacoes =  urldecode($_REQUEST['observacoes']);
        $dados = json_decode($_REQUEST['dados'], true);
        $dados['dados']['observacoes'] = $observacoes;
        $fileDescriptor = (empty($_REQUEST['descricaoFile'])) ? null : json_decode( $_REQUEST['descricaoFile']);
        
    
        if (! is_array($dados) || count($dados) < 1)
        {
            $json['success'] = false;
             $json['message'] = 'Parâmetros inválidos. Contate o suporte.';
             echo json_encode($json);
            exit;
        }
        
        
        // tenta gravar arquivos
        
        $moved = true;
        $json = null;
        $json['filename'] = '';
        $json['error'] = '';
        $filesMoved = array();
        if (is_array($_FILES))
        {
            $directoryFiles =  \Application::getFilesPath() . DS . 'arquivos' . DS . 'clientes';
            // checa se o diretório de OS existe
            if (! is_dir($directoryFiles))
                mkdir($directoryFiles);
            
              $filesMoved = array();
            $pos = 0;
            foreach ($_FILES as $i => $value)
            {
                $aux = explode('.',$value['name']);
                $extension = array_pop($aux);
                $nomeSistema = ApiFile::normalizeName( implode('.',$aux) ) . '_' . date('YmdHis') . '.' . $extension  ; 
               
                $moved = move_uploaded_file($value['tmp_name'],  $directoryFiles . DS . $nomeSistema  );
               
                if ($moved === false)
                {
                    $json['filename'] = $value['name'];
                    $json['error'] = ApiFile::moveUploadedErrorDescript($value['error']);
                    // apaga todos os arquivos que tiveram sucesso para ser movido
                    foreach($filesMoved as $pos => $file)
                        @unlink($file['path']);
                    break;
                }else
                {
                    
                    $descricao = (! empty($fileDescriptor[$pos])) ? urldecode( $fileDescriptor[$pos]) : null;
                    array_push($filesMoved, array('path' => $directoryFiles. DS . $nomeSistema, 'nomeSistema' => $nomeSistema, 'nome' => $value['name'], 'mime' =>  $value['type'], 'descricao' => $descricao ));
                }
                $pos++;
            }
        }else
            $filesMoved = null;
        

        if (trim($dados['dados']['nascimento'])  !== '')
            $dados['dados']['nascimento'] = Utils::formatStringDate( $dados['dados']['nascimento'], 'd/m/Y', 'Y-m-d');  
        $cliente = new ClienteModel();
        
        if (trim($cpf)  ===  ''  )
            $result = $cliente->salvar($dados, null, $filesMoved);
        else
            $result = $cliente->salvar($dados, $cpf, $filesMoved);
        
        if ($result === false)
        {
            // Apaga os arquivos
            foreach($filesMoved as $pos => $file)
               @unlink($file['path']);
            
            switch($cliente->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este CPF'; break;
                default: $json['message'] = 'Não foi possível salvar o registro: '. $cliente->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['cpf'] = $result;
        }
        
       // $json['success'] = true;
       // $json['cpf'] = '079.144.546-13';
        echo json_encode($json);
        
    }
    
    public function baixarArquivoCliente()
    {
        $params = \Application::getUrlParams();
        
        $fileName = (! empty($params['filename'])) ? urldecode($params['filename']) : null;
        $path = \Application::getFilesPath() . DS . 'arquivos' . DS . 'clientes'. DS . $fileName;
        if (! file_exists($path ))
            \Application::print404();
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        ob_clean();
        flush();
        readfile($path);
        exit;
        
        
    }
    
    public function deletarArquivoCliente()
    {
        if (! \Application::isAuthorized(ucfirst('clientes') , 'admin_cadastro_clientes', 'remover') )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuário não autorizado'));
            exit;
        }
        
        $params = $_REQUEST;
        $id = (isset($params['id'])) ? $params['id'] : null;
        $fileName = (isset($params['filename'])) ? $params['filename'] : null;
        $path = \Application::getFilesPath() . DS . 'arquivos' . DS . 'clientes'. DS . urldecode($fileName);
        
        if ( $id == null )
        {
            echo json_encode(array('success' => false, 'message' => 'Parâmetro incorreto'));
            exit;
        }
        
        $cliente = new ClienteModel();
        $result = $cliente->removerArquivoCliente($id);
        
        if ($result === false)
        {
            
            switch($cliente->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este CPF'; break;
                default: $json['message'] = 'Não foi possível salvar o registro: '. $cliente->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
            if (file_exists($path ))
                @unlink($path);
        }
        
        echo json_encode($json);
        exit;
    }
    
    
    
    // *********************************************
    // AGENDA
    //
    
    public function listarAgenda()
    {
          if (! \Application::isAuthorized(ucfirst('clientes') , 'agenda', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'agenda', 'escrever')
           )
            \Application::print404();
        
        $this->setView('agenda/index');
        
       
        
        $agenda = new Agenda();
        if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'ler') || \Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'escrever') )
            $result = $agenda->listarAgenda(null, true, 10);
        else
            $result = $agenda->listarAgenda(null, false, 10);
        if ($result !== false)
            $this->setParams('agenda', $result);
        
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenio', $result);
        
        $this->showContents();
        
    }
    
    public function cadastrarAgenda()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'ler')  
                    && ! \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'escrever')
           )
            \Application::print404();
        
        $this->setView('agenda/cadastrar');
        
        $result = '';
        $id = (\Application::getUrlParams(0) === null) ? null : \Application::getUrlParams(0);
        $agenda = new Agenda();
        
        if ($id !== null)
        {
            if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'ler') || \Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'escrever'))
            {
                if (strlen($id) < 11)
                    $result = $agenda->listarAgenda($id, true);
                
                else
                {
                    // pesquisa pelo CPF
                    $id = Utils::formatCpfHowString($id);
                    $result = $agenda->listarAgenda(null, true, 1, $id);
                }
            } else
            {
                if (strlen($id) < 11)
                    $result = $agenda->listarAgenda($id);
                else
                {
                    $id = Utils::formatCpfHowString($id);
                    $result = $agenda->listarAgenda(null, false, 1, $id);
                }
            }
        }else
            $result = false;
        
      
      
        if ($result !== false)
            $this->setParams('agenda', $result);
        
        $entidade = new Entidade();
         $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenios', $result);
        
         $this->showContents();
        
    }
    
    public function salvarAgenda()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'escrever'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
       // $id = (! isset($_REQUEST['id']) || trim($_REQUEST['id']) == '') ? null : $_REQUEST['id'];
        //$nome = $_REQUEST['nome'];
        $observacoes = $_REQUEST['observacoes'];
        $dados = json_decode($_REQUEST['dados'], true);
        $dados['observacoes'] = $observacoes;
        
        $agenda = new Agenda();
        
        if (count($dados) < 1)
        {
             $json['success'] = false;
             $json['message'] = 'Parâmetros mal informados';
             echo json_encode($json);
            exit;
        }
        
        // concatena hora com data e formata para MariaDB
        $dados['dataLigacao'] = $dados['dataLigacao'] . ' ' . $dados['horaLigacao'];
        $dados['dataLigacao'] = Utils::formatStringDate($dados['dataLigacao'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        // formata data de nascimento
        $dados['dataNascimento'] = Utils::formatStringDate($dados['dataNascimento'], 'd/m/Y', 'Y-m-d');
        
       
        
        if (! isset($dados['id']))
            $result = $agenda->salvar( $dados);
        else
            $result = $agenda->salvar($dados, $dados['id']);
        
        
        if ($result === false)
        {
            $json['success'] = false;
            switch($agenda->getMysqlError())
            {
                case 1062 : $json['message'] = 'Não foi possível salvar o registro. Já existe um registro cadastrado para este código'; break;
                default: $json['message'] = 'Não foi possível salvar o registro. Codigo: '. $agenda->getMysqlError(); break;
            }
            
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
    }
    
    
    public function apagarAgenda()
    {
        if (! \Application::isAuthorized(ucfirst(strtolower('clientes')) , 'agenda', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $id = trim($_REQUEST['id']);
        
        $agenda = new Agenda();
        
        if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'remover') )
                $result = $agenda->excluir($id, true);
            else
                $result = $agenda->excluir($id);
         
       
         
        if ($result === false)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. Codigo: '. $agenda->getMysqlError();
        }else if ($result === 1)
        {
            $json['success'] = false;
            $json['message'] = 'Não foi possível remover o registro. Este cadastro pertence a outro usuário';
        }else
        {
             $json['success'] = true;
             $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    public function pesquisarAgenda()
    {
          if (! \Application::isAuthorized(ucfirst('clientes') , 'agenda', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'agenda', 'escrever')
           )
            \Application::print404();
        
        $this->setView('agenda/index');
        
       // '&cpf='+cpf+'&nome='+nome+'&convenio='+convenio+'&agendamentoinicial='+agendamentoinicial+'&agendamentofinal='+agendamentofinal+ '&status='+status+'&limit=100000'
        
        $cpf = ($_REQUEST['cpf'] == '') ? null : $_REQUEST['cpf'];
        $nome = ($_REQUEST['nome'] == '') ? null : $_REQUEST['nome'];
        $convenio = ($_REQUEST['convenio'] == '') ? null : $_REQUEST['convenio'];
        $dataInicio = ($_REQUEST['agendamentoinicial'] == '') ? null : Utils::formatStringDate($_REQUEST['agendamentoinicial'], 'd/m/Y', 'Y-m-d');
        $dataFim = ($_REQUEST['agendamentofinal'] == '') ? null : Utils::formatStringDate($_REQUEST['agendamentofinal'], 'd/m/Y', 'Y-m-d');
        $status = ($_REQUEST['status'] == '') ? null : $_REQUEST['status'];
        $limit = ($_REQUEST['limit'] == '') ? 10 : $_REQUEST['limit'];
        
        $agenda = new Agenda();
        if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'ler') || \Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'escrever') )
            $result = $agenda->listarAgenda(null, true, $limit, $cpf, $nome, $convenio, $dataInicio, $dataFim, $status);
        else
            $result = $agenda->listarAgenda(null, false, $limit, $cpf, $nome, $convenio, $dataInicio, $dataFim, $status);
        if ($result !== false)
            $json = $result;
        else
            $json = array();
        
        
        
       echo json_encode($json);
        
    }
    
    
    // *********************************************
    // IMPORTAR CLIENTE VIA ARQUIVO EXCEL
    //
    
    public function importarCliente()
    {
          if (! \Application::isAuthorized(ucfirst('clientes') , 'importar_cliente', 'escrever')
           )
            \Application::print404();
        
        $this->setView('clientes/importar');
        
       
        /*
        $agenda = new Agenda();
        if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'ler') || \Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'escrever') )
            $result = $agenda->listarAgenda(null, true);
        else
            $result = $agenda->listarAgenda();
        if ($result !== false)
            $this->setParams('agenda', $result);
        
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenio', $result);
        */
        
        $cliente = new ClienteModel();
        $result = $cliente->listarLogImportacao();
        if ($result !== false)
            $this->setParams('logs', $result);
        
        $this->showContents();
        
    }
    
    public function uploadImportarCliente()
    {
        ini_set('max_execution_time', 300);
        
          if (! \Application::isAuthorized(ucfirst('clientes') , 'importar_cliente', 'escrever')
           )
          {
              echo json_encode(array('success' => false, 'message' => 'Usuário sem autorização'));
              exit;
          }
       
        
        
        
        
        $objReader = new \PHPExcel_Reader_Excel2007();
       // var_dump($objReader); exit;
        
        $file = (isset($_FILES['arquivo'])) ? $_FILES['arquivo'] : null;
        
        if ($file == null)
          {
              echo json_encode(array('success' => false, 'message' => 'Nenhum arquivo enviado'));
              exit;
          }
        
        
        
        
        
        
        // move para pasta fixa;
        
        $name = $file['name'];
        $name = \Gauchacred\library\php\ApiFile::normalizeName($name);
        $name = uniqid(). '_' . $name; 
        $path = \Application::getFilesPath(). '/arquivos/clientes/' .$name ;
        
        $moved = move_uploaded_file($file['tmp_name'],  $path  );
               
        if ($moved === false)
        {
            $json['success'] = false;
            $json['message'] = Gauchacred\library\php\ApiFile::moveUploadedErrorDescript($file['error']);
            // apaga arquivo
            //@unlink($path . $name);
            echo json_encode($json);
            exit;
            
        }
        
        if ($file['type'] == 'application/vnd.ms-excel')
            $objReader = new \PHPExcel_Reader_Excel2003XML();
        else if ($file['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            $objReader = new \PHPExcel_Reader_Excel2007();
        else
        {
             echo json_encode(array('success' => false, 'message' => 'Não foi possível abrir o arquivo para a leitura. tente salvar como .xls ou .xlsx'));
              exit;
        }
        
        
        // Obtem os bancos cadastrados
        $b = new Banco();
        $bancos = $b->listarBancos();
        
        $cbc = new ContaBancariaCliente();
        $tipoConta = $cbc->listarTipos();
        
        $e = new Entidade();
        $entidades = $e->listarEntidades();
        
        $u = new Usuario();
        $usuarios = $u-> listarUsuarios();
        
        
        // Carrega arquivo
		$objPHPExcel = $objReader->load($path);
        // Ativa SHEET 1
		$workSheet = $objPHPExcel->getSheet(0);
        
        // Obtem o nome da máxima coluna. ex:. A; B; E; M;
		$nameMaxColumn = $workSheet->getHighestColumn();
		// Obtem o numero da máxima coluna de acordo com o nome
		$qtdeColumn = \PHPExcel_Cell::columnIndexFromString($nameMaxColumn)-1;
		// Linha acima pode obter celulas que não foram preenchidas, algoritimo abaixo valida isto;
		for ($col = 0; $col <= $qtdeColumn; $col++)
		  if (trim($workSheet->getCellByColumnAndRow($col, 1)->getValue()) == '')
		  {
				
				for ($a = $col+1; $a <= $qtdeColumn; $a++)
				  if (trim($workSheet->getCellByColumnAndRow($a, 1)->getValue()) != '')
				  {
				  $dados['indicaSucesso'] = 'false';
				  $dados['mensagem'] = 'A linha '. ($col+1) . ' n&atilde;o possui t&iacute;tulo ou est&aacute; mesclada. Favor Corrija';
				  echo json_encode($dados);
				  exit;
			  	  }
		  }
       
        if (ord(strtolower($nameMaxColumn)) != 121)
        {
            $dados['indicaSucesso'] = 'false';
            $dados['message'] = 'As colunas não batem com o modelo. O modelo é: '. PHP_EOL .'Nome; Convenio; Rua; Bairro; cidade; estado; cep; telefone1; referencia1; telefone2; referencia2; celular; referencia3; email; NB; Matricula; Senha; CPF; atividade; nascimento; Data do Cadastro; Banco; agencia; conta; Tipo de Conta;
 ';
				  echo json_encode($dados);
				  exit;
        }
        
       
        
        // PEGA O CONTEUDO
        $dados = array();
        $error = array();
        foreach ($workSheet->getRowIterator() as $i => $row)
		  if ($i > 1)
		  {
			 $d = array();
              
             $d['nome'] = $workSheet->getCell('A'. $i)->getValue();
             
              $d['rua'] = $workSheet->getCell('C'. $i)->getValue();
              $d['bairro'] = $workSheet->getCell('D'. $i)->getValue();
              $d['cidade'] = $workSheet->getCell('E'. $i)->getValue();
              $d['estado'] = $workSheet->getCell('F'. $i)->getValue();
              $d['cep'] = $workSheet->getCell('G'. $i)->getValue();
              $d['telefone1'] = $workSheet->getCell('H'. $i)->getValue();
              $d['referencia1'] = $workSheet->getCell('I'. $i)->getValue();
              $d['telefone2'] = $workSheet->getCell('J'. $i)->getValue();
              $d['referencia2'] = $workSheet->getCell('K'. $i)->getValue();
              $d['telefone3'] = $workSheet->getCell('L'. $i)->getValue();
              $d['referencia3'] = $workSheet->getCell('M'. $i)->getValue();
              $d['email'] = $workSheet->getCell('N'. $i)->getValue();
              $d['nb'] = $workSheet->getCell('O'. $i)->getValue();
              $d['matricula'] = $workSheet->getCell('P'. $i)->getValue();
              $d['senha'] = $workSheet->getCell('Q'. $i)->getValue();
              $d['cpf'] = $workSheet->getCell('R'. $i)->getValue();
              
              
              
              // Procura ID do Usuario
              $key = false;
              foreach($usuarios as $a => $value)
              {
                //  echo 'Usuario: '. strtolower(trim($workSheet->getCell('S'. $i)->getValue())) . ' - ' . strtolower($value['email']) . '<br>';
                  if (strtolower(trim($workSheet->getCell('S'. $i)->getValue())) == strtolower($value['email'])    )
                  {
                      $key = $a; 
                      break;
                     
                  }
             }
              if ($key === false)
              {
                 // array_push($error, array('row' => $i, 'message' => 'Usuário não encontrado. Verifique a coluna ATIVIDADE, deve conter o e-mail do usuário'));
                  $workSheet->setCellValue('Z'. $i, 'Usuário não encontrado. Verifique a coluna ATIVIDADE, deve conter o e-mail do usuário');
                  $d['idUsuario'] = null;   
              }
              else
                  $d['idUsuario'] = $usuarios[$key]['id'];
              
              
              
              $d['atividade'] = $workSheet->getCell('S'. $i)->getValue();
              // data nascimento
              //$formatCell = $workSheet->getStyle('T'. $i)->getNumberFormat()->getFormatCode();
              //$workSheet->getStyle('T' . $i)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2)
              $d['nascimento'] = date('Y-m-d',\PHPExcel_Shared_Date::ExcelToPHP($workSheet->getCell('T'. $i)->getValue()));
                  //->toFormattedString ($workSheet->getCell('T'. $i)->getValue(),$formatCell);                                         
              //$workSheet->getCell('T'. $i)->getValue();
              $d['created'] = date('Y-m-d',\PHPExcel_Shared_Date::ExcelToPHP($workSheet->getCell('U'. $i)->getValue())); 
              // banco ... o formato é: 001 - Banco do Basil
              $aux = explode('-',$workSheet->getCell('V'. $i)->getValue());
              if (isset($aux[0]))
                  $aux[0] = trim($aux[0]);
              else
                  $aux[0] = '';
              if (isset($aux[1]))
                  $aux[1] = trim($aux[1]);
              else
                  $aux[1] = '';
              // verifica se existe o banco através do código ou nome
              if($aux[0] != '')
                $keyCodigo = array_search($aux[0], array_column($bancos, 'codigo'));
              else
                  $keyCodigo = false;
              
              $d['adicionarBanco'] = true;
              // se encontrou o código
              if ($keyCodigo !== false)
              {
                  $d['codigoBanco'] = $bancos[$keyCodigo]['codigo'];
                  $d['nomeBanco'] = $bancos[$keyCodigo]['nome'];
                  $d['idBanco'] = $bancos[$keyCodigo]['nome'];
                  $d['adicionarBanco'] = false;
                  
              }else
              {
                  // tenta encontrar pelo nome
                  if($aux[1] != '')
                    $keyNome = array_search($aux[1], array_column($bancos, 'nome'));
                  else
                      $keyNome = false;
                  
                  if ($keyNome !== false)
                  {
                      $d['codigoBanco'] = $bancos[$keyNome]['codigo'];
                      $d['nomeBanco'] = $bancos[$keyNome]['nome'];
                      $d['idBanco'] = $bancos[$keyNome]['id'];
                      $d['adicionarBanco'] = false;
                  }else
                  {
                      $d['codigoBanco'] = $aux[0];
                      $d['nomeBanco'] = $aux[1];
                      $d['adicionarBanco'] = true;
                  }
              }
              
              
              
              $d['agencia'] = $workSheet->getCell('W'. $i)->getValue();
              $d['conta'] = $workSheet->getCell('X'. $i)->getValue();
              
              
              //var_dump(array_map('strtolower',$tipoConta));
              // Tenta Encontrar o ID do Tipo de Conta
            
              $key = false;
              foreach($tipoConta as $a => $value)
                  if (strtolower(trim($workSheet->getCell('Y'. $i)->getValue())) == strtolower($value['descricao'])    )
                  {
                      $key = $a; 
                      break;
                     
                  }
              
              
              //$key = array_search(strtolower(trim($workSheet->getCell('Y'. $i)->getValue())), array_column($tipoConta, 'descricao'));
              if ($key === false)
              {
                  $d['adicionarTipoConta'] = true;
                  $d['descricaoTipoConta'] = trim($workSheet->getCell('Y'. $i)->getValue());
              }else
              {
                  $d['adicionarTipoConta'] = false;
                  $d['idTipoConta'] = $tipoConta[$key]['id'];
                  $d['descricaoTipoConta'] = trim($workSheet->getCell('Y'. $i)->getValue());
              }
              
              // Tenta Encontrar o ID do Convênio
              $key = array_search(trim($workSheet->getCell('B'. $i)->getValue()), array_column($entidades, 'nome'));
              if ($key === false)
              {
                  $d['adicionarEntidade'] = true;
                  $d['nomeEntidade'] = trim($workSheet->getCell('B'. $i)->getValue());
              }else
              {
                  $d['adicionarEntidade'] = false;
                  $d['idEntidade'] = $entidades[$key]['id'];
                  $d['nomeEntidade'] = trim($workSheet->getCell('B'. $i)->getValue());
              }
              
              
               $d['nomeArquivo'] = $file['name'];
               
              
              
              // adiciona no array Final
              array_push($dados, $d);
              
        
			  
		  }
        
        
        $workSheet->setCellValue('Z1', 'ERROS DE IMPORTAÇÃO');
        
        //$rendererName = \PHPExcel_Settings::PDF_RENDERER_DOMPDF;
       // $rendererLibrary = 'Dompdf';
        //$rendererLibraryPath = \Application::getIndexPath(). '/library/php/dompdf/src/' . $rendererLibrary . '.php';
         //\PHPExcel_Settings::setPdfRenderer($rendererName,$rendererLibraryPath);
                                            
        //if (! \PHPExcel_Settings::setPdfRenderer($rendererName,$rendererLibraryPath))
          //  die( 'Please set the $rendererName and $rendererLibraryPath values' .  PHP_EOL .   ' as appropriate for your directory structure'  );
        $cliente = new ClienteModel();
       
        
        
        foreach($dados as $i => $value)
            if (isset($value['idUsuario']) && ! empty(trim($value['nome'])) && ! empty(trim($value['cpf']))   )
            {
                 $error = array();
               $result = $cliente->importar($value);
                if ($result === false)
                {
                    $codeError = $cliente->getMysqlError();
                    switch($codeError)
                    {
                        case 1061: 
                            $workSheet->setCellValue('Z'. ($i + 2), 'O CPF já possui cadastro');
                            break;
                        case 1062: 
                            $workSheet->setCellValue('Z'. ($i + 2), 'O CPF já possui cadastro');
                            break;
                        default:
                            $workSheet->setCellValue('Z'. ($i + 2), 'Erro não esperado. Analise corretamente a planilha. Codigo: '. $codeError);
                            break;
                    }
                    $clienteInserido = false;
                    continue;
                }else
                    $clienteInserido = true;
                    
                
                
                // Adiciona banco ao cadastro de bancos caso necessário
                if ($value['adicionarBanco'] === true    && trim($value['nomeBanco']) != ''    )
                {
                    $result = $b-> salvar(trim($value['codigoBanco']), trim($value['nomeBanco']), 1);
                    if ($result !== false)
                            $dados[$i]['idBanco'] = $result;
                   // else
                          //  array_push($error, 'O banco não pode ser inserido pois o conteúdo está vazio');
                }
                                                                                   
                if (isset($dados[$i]['idBanco']))
                       $result = $b->salvar($value['codigoBanco'], $value['nomeBanco'], 1, $dados[$i]['idBanco']); 
                else if (! isset($dados[$i]['idBanco']))
                {
                     $workSheet->setCellValue('Z'. ($i + 2), 'Não foi possível cadastrar o banco');
                    continue;
                }
                
                // insere Tipo Conta Bancaria
                if (isset($value['idTipoContaBancaria']) && ! empty($value['descricaoTipoContaBancaria'])  )
                {
                     $result =   $cbc->salvarTipos($value['descricaoTipoContaBancaria']);
                     if ($result !== false)
                         $dados[$i]['idTipoContaBancaria'] = $result;
                }
                // cadastra o banco do cliente
                if ( ! empty($dados[$i]['idTipoContaBancaria']) )
                {
                    $return = $cbc->salvarContaCliente($value['cpf'], $dados[$i]['idBanco'], $dados[$i]['idTipoContaBancaria'], $value['agencia'], $value['conta'],  $id);
                }
                
                if (!empty(trim($value['telefone1'])))
                    $cliente->salvarTelefone($value['cpf'], $value['telefone1'], $value['referencia1']);
                if (!empty(trim($value['telefone2'])))
                     $cliente->salvarTelefone($value['cpf'], $value['telefone2'], $value['referencia2']);
                if (!empty(trim($value['telefone3'])))
                     $cliente->salvarTelefone($value['cpf'], $value['telefone3'], $value['referencia3']);
                
                
                if ($value['adicionarEntidade'] === true && ! empty($value['nomeEntidade']))
                {
                   $result = $e->salvar($value['nomeEntidade']);
                    if ($result !== false)   
                        $dados[$i]['idEntidade'] = $result;
                    else
                        $dados['idEntidade'] = null;
                }else if (empty($dados[$i]['nomeEntidade']))
                    $dados[$i]['idEntidade'] = null;
                    
                if (isset($dados[$i]['idEntidade'])   )
                    $cliente->salvarNb($value['cpf'], $dados[$i]['idEntidade'], $value['nb'], $value['matricula'], $value['senha']);
                
				// SALVA OS TELEFONES
				if (trim($value['telefone1']) != '')
					$cliente->salvarTelefone($value['cpf'], $value['telefone1'], $value['referencia1']);
				if (trim($value['telefone2']) != '')
					$cliente->salvarTelefone($value['cpf'], $value['telefone2'], $value['referencia2']);
				if (trim($value['telefone3']) != '')
					$cliente->salvarTelefone($value['cpf'], $value['telefone3'], $value['referencia3']);
				
                
                
            }
        
        
        $cliente->salvarLogImportacao($file['type'], $file['name'], $name);
        
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
       // $objWriter = new \PHPExcel_Writer_PDF($objPHPExcel); 
        $objWriter->save($path);
        //$objPHPExcel->save($path);
        
        echo json_encode(array('success' => true));
        
    }
    
    
      public function apagarCliente()
    {
        if (! \Application::isAuthorized(ucfirst('clientes') , 'clientes', 'remover'))
        {
             $json['success'] = false;
             $json['message'] = 'Usuário sem autorização';
             echo json_encode($json);
            exit;
        }
        
        $cpf = trim($_REQUEST['cpf']);
        
        $cliente = new ClienteModel();
        
        $result = $cliente->excluir($cpf);
         
       
         
        if ($result === false)
        {
            $json['success'] = false;
            switch($cliente->getMysqlError())
            {
                case 1169: $json['message'] = 'Não foi possível remover o registro. Existem contratos para este cadastro'; break;
                case 1217: $json['message'] = 'Não foi possível remover o registro. Existem contratos para este cadastro'; break;
                case 1451: $json['message'] = 'Não foi possível remover o registro. Existem contratos para este cadastro'; break;
                case 1452: $json['message'] = 'Não foi possível remover o registro. Existem contratos para este cadastro'; break;
                default: $json['message'] = 'Não foi possível remover o registro. '. $cliente->getMysqlError(); break;
                    
            }
            
            
        }else 
        {
             $json['success'] = true;
            // $json['id'] = $result;
        }
        
        echo json_encode($json);
        exit;
        
    }
    
    
    
    // *********************************************
    // TELEMARKETING
    //
    
    public function telemarketing()
    {
          if (! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')
           )
            \Application::print404();
        
        $this->setView('telemarketing/index');
        
       
        
        $agenda = new Agenda();
        if (\Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'ler') || \Application::isAuthorized(ucfirst('clientes') , 'agenda_todos', 'escrever') )
            $result = $agenda->listarAgenda(null, true, 10);
        else
            $result = $agenda->listarAgenda(null, false, 10);
        if ($result !== false)
            $this->setParams('agenda', $result);
        
        
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenio', $result);
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->listarImportacao();
        if ($result !== false)
            $this->setParams('telemarketing', $result);
        
        $this->showContents();
        
    }
    
    
    public function telemarketingClienteEmAndamento()
    {
          if (! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'ler')  
                    && ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')
           )
            \Application::print404();
        
        $this->setView('telemarketing/clientes_em_andamento');
        
        $id = \Application::getUrlParams(0);
        
        if ($id == null)
            \Application::print404();
        
        $telemarketing = new Telemarketing();
        
        $result = $telemarketing->distribuirFoco($id, $_SESSION['userid'] );
       
        $processadorFoco = null;
        
        switch( ((int)$result))
        {
            //case -3: $processadorFoco = 'NÂO EXISTEM USUARIOS PARA SORTEAR'; break;
            case -2: $processadorFoco = 'NÂO FOI POSSIVEL OBTER O LOCK DA TABELA DE CLIENTES. A DISTRIBUIÇÃO DE FOCO NÃO FOI REALIZADA'; break;
            case -1: $processadorFoco = (\Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever'))? 'NÃO EXISTEM CLIENTES PARA DISTRIBUIR FOCO' : null; break;
            case 0: $processadorFoco = 'ERRO INTERNO NA DISTRIBUIÇÃO DE FOCO'; break;
        }
        
        if ($processadorFoco !== null)
            $this->setParams('processadorfoco', $processadorFoco);
        
        $result = $result = $telemarketing->listarClienteFoco($id, $_SESSION['userid'] );
        if (is_array($result) && count($result) > 0 )
            $this->setParams('listafoco', $result[0]);
        
        $result = $telemarketing->listarStatus();
        if ($result !== false)
            $this->setParams('listastatus', $result);
        
        $this->showContents();
        exit;
    }
    
    
    public function telemarketingImportarClientes()
    {
          if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
            \Application::print404();
        
        $this->setView('telemarketing/importar_clientes');
        
       
        $entidade = new Entidade();
        $result = $entidade->listarEntidades();
        if ($result !== false)
            $this->setParams('convenios', $result);
        
        $usuarios = new Usuario();
        $result = $usuarios->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        $this->showContents();
        
    }
    
    
    public function telemarketingAtribuirUsuarios()
    {
          if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
            \Application::print404();
        
        $this->setView('telemarketing/atribuir_usuarios');
        
        $id = \Application::getUrlParams(0);
        
        if ($id == null)
            \Application::print404();
        
       
        $telemarketing = new Telemarketing();
        
        $result = $telemarketing->listarImportacao($id);
        if (! is_array($result) || count($result) == 0 )
            \Application::print404();
           
        
        $result = $telemarketing->listarUsuariosSorteio($id);
        if ($result !== false)
            $this->setParams('usuariossorteio', $result);
      
        
        $usuario = new Usuario();
        $result = $usuario->listarUsuarios();
        if ($result !== false)
            $this->setParams('usuarios', $result);
        
        $this->showContents();
       
        
    }
    
    
    
    
    public function telemarketingSalvarArquivoImportacao()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuario sem permissão'));
            exit;
        }
        
        
        $nomeImportacao = (! empty($_REQUEST['nomeimportacao'])) ? $_REQUEST['nomeimportacao'] : null;
        $convenio = (! empty($_REQUEST['convenio'])) ? $_REQUEST['convenio'] : null;
        $usuarios = json_decode( $_REQUEST['usuarios']);
        $file = (! empty($_FILES['file'])) ? $_FILES['file'] : null;
        
        if (! is_array($file))
        {
            echo json_encode(array('success' => 'false', 'message' => 'Não foi enviado um arquivo. Favor escolha um arquivo para importar'));
            exit;
        }else
            if ($file['type'] != 'application/vnd.ms-excel' && $file['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $file['type'] != 'text/x-csv' )
            {
                echo json_encode(array('success' => false, 'message' => 'O tipo do arquivo é inválido. Escolha um arquivo do tipo CSV ou EXCEL'));
                exit;
            }
        
        
        
        $dadosArquivo = $this->processaArquivoTelemarketing($file);
        
        if ($dadosArquivo['imported'] == false)
        {
            echo json_encode(array('success' => false, 'message' => 'Não foi possível realizar o upload do arquivo. ' . $dados['error']  ));
            exit;
        }
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->importar($nomeImportacao, $convenio, $usuarios, $dadosArquivo);
        
        if ($result == true)
            $response = array('success' => true, 'message' => 'Importado com sucesso'  );
        else
            $response = array('success' => false, 'message' => 'Ocorreram erros ao salvar importação. ' . $telemarketing->getMysqlError()  );
        
        echo json_encode($response);
        exit;
        
    }
    
    public function telemarketingSalvarAtribuicaoUsuario()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuario sem permissão'));
            exit;
        }
        
        
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        $usuarios = json_decode( $_REQUEST['usuarios']);
   
        
        if ($id == null)
        {
            echo json_encode(array('success' => false, 'message' => 'O ID da importação não foi definido.'  ));
            exit;
        }
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->atribuirUsuarios($id, $usuarios);
        
        if ($result == true)
            $response = array('success' => true, 'message' => 'Salvo com sucesso'  );
        else
            $response = array('success' => false, 'message' => 'Ocorreram erros ao salvar importação. ' . $telemarketing->getMysqlError()  );
        
        echo json_encode($response);
        exit;
        
    }
    
    
    public function telemarketingSalvarClienteEmAndamento()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'ler')   )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuario sem permissão'));
            exit;
        }
        
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        $tipoCliente = (! empty($_REQUEST['tipocliente'])) ? $_REQUEST['tipocliente'] : null;
        $status = (! empty($_REQUEST['status'])) ? $_REQUEST['status'] : null;
        $observacoes = (! empty($_REQUEST['observacoes'])) ? $_REQUEST['observacoes'] : null;
        $dataLigacao = (! empty($_REQUEST['dataligacao'])) ? Utils::formatStringDate( $_REQUEST['dataligacao'], 'd/m/Y','Y-m-d') : null;
        $dataLigacao = ($dataLigacao == null) ? null : $dataLigacao . ' ' . ((empty($_REQUEST['horaligacao'])) ? '00:00:00' : $_REQUEST['horaligacao']);
        
     
        $telefones = (! empty($_REQUEST['telefones'])) ? json_decode($_REQUEST['telefones'], true) : null;
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->salvarCliente($id, $tipoCliente, $status, $observacoes, $dataLigacao, $telefones);
        
        if ($result == false)
        {
            $response = array('success' => false, 'message' => 'Não foi possível salvar o registro. '. $telemarketing->getMysqlError());
        }else
            $response =  array('success' => true, 'message' => 'Registro salvo com sucesso');
        
        echo json_encode($response);
        exit;
        
    }
    
    
    public function telemarketingReprocessar()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuario sem permissão'));
            exit;
        }
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->reprocessar($id);
        
         if ($result == false)
        {
            $response = array('success' => false, 'message' => 'Não foi possível reprocessar a importação. '. $telemarketing->getMysqlError());
        }else
            $response =  array('success' => true, 'message' => 'Importação reprocessada com sucesso');
        
        echo json_encode($response);
        exit;
    }
    
    public function telemarketingExcluir()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
        {
            echo json_encode(array('success' => false, 'message' => 'Usuario sem permissão'));
            exit;
        }
        $id = (! empty($_REQUEST['id'])) ? $_REQUEST['id'] : null;
        
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->excluir($id);
        
         if ($result == false)
        {
            $response = array('success' => false, 'message' => 'Não foi possível excluir a importação. '. $telemarketing->getMysqlError());
        }else
            $response =  array('success' => true, 'message' => 'Importação excluida com sucesso');
        
        echo json_encode($response);
        exit;
    }
    
    public function telemarketingDownload()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
            \Application::print404();
        
        $id = \Application::getUrlParams(0);
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->listarImportacao($id);
        
        if (isset($result[0]['systemFileName'])  && file_exists( \Application::getIndexPath(). '/arquivos/telemarketing/'.  $result[0]['systemFileName'])  )
        {
            $arquivo = \Application::getIndexPath(). '/arquivos/telemarketing/'.  $result[0]['systemFileName'];
             switch(strtolower(substr(strrchr(basename($arquivo),"."),1))){
                 case "pdf": $tipo="application/pdf"; break;
                 case "exe": $tipo="application/octet-stream"; break;
                 case "zip": $tipo="application/zip"; break;
                 case "doc": $tipo="application/msword"; break;
                 case "xls": $tipo="application/vnd.ms-excel"; break;
                 case "ppt": $tipo="application/vnd.ms-powerpoint"; break;
                 case "gif": $tipo="image/gif"; break;
                 case "png": $tipo="image/png"; break;
                 case "jpg": $tipo="image/jpg"; break;
                 case "mp3": $tipo="audio/mpeg"; break;
                 case "xls": $tipo=" application/vnd.ms-excel"; break;
                 case "xlsx": $tipo="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
                 case "csv": $tipo="text/csv"; break;
                 case "php": // deixar vazio por seurança
                 case "htm": // deixar vazio por seurança
                 case "html": // deixar vazio por seurança
              }
              header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
              header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
              header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
              readfile($arquivo); // lê o arquivo
              exit; // aborta pós-ações   
            
        }else
            \Application::print404();
        
    }
    
    
    public function telemarketingRelatorio()
    {
        if ( ! \Application::isAuthorized(ucfirst('clientes') , 'telemarketing', 'escrever')   )
            \Application::print404();
        
        $id = \Application::getUrlParams(0);
        if ($id == null)
            \Application::print404();
        
        $telemarketing = new Telemarketing();
        $result = $telemarketing->listarRelatorio($id);
        
        if (! is_array($result))
            \Application::print404();
        

        
        $objPHPExcel = new \PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Gaucha Cred");
        $objPHPExcel->getProperties()->setLastModifiedBy("Gaucha Cred");
        $objPHPExcel->getProperties()->setTitle("Relatório Telemarketing");
        $objPHPExcel->getProperties()->setSubject("Relatório Telemarketing");
        $objPHPExcel->getProperties()->setDescription("Relatório gerado pelo sistema Gaucha Cred.");

        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Importação Telemarketing');
        
        $titulo = array('Usuarios','E-mail','Clientes Com Foco', 'Clientes Trabalhados','Clientes Pendentes', 'Sem Interesse', 'Não Atendeu', 'Número Errado', 'Agendar', 'Margem Negativa',
                        'Não Contatado', 'Data de Início', 'Última Entrada na Base');
        
        // SETA O CABEÇALHO
        $sheet->fromArray($titulo, NULL, 'A1');
        $sheet->fromArray($result, NULL, 'A2');
        
        // redirect output to client browser 
        header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet'); 
        header('Content-Disposition: attachment;filename="Importação Telemarketing.xlsx"'); 
        header('Cache-Control: max-age=0'); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('php://output');
        
        exit;
    }
    
    
    private function processaArquivoTelemarketing($file)
    {
        
        $type = $file['type'];
        $fileName = $file['name'];
        $fileExtension = array_pop((explode('.', $fileName)));
        $systemFileName = uniqid(). '_.' . $fileExtension;
        
        $directory = \Application::getIndexPath() . '/arquivos/telemarketing';
        
        // se não existir diretorio de arquivos cria ele
        
        if (! is_dir($directory))
            mkdir($directory);
        
        $moved = move_uploaded_file($file['tmp_name'],  $directory . '/'. $systemFileName);
        if ($moved === false)
        {
            $return['imported'] = false;
            $return['error'] = ApiFile::moveUploadedErrorDescript($value['error']);
        }else
        {
            $return['imported'] = true;
            $return['error'] = '';
            $return['$type'] = $type;
            $return['fileName'] = $fileName;
            $return['systemFileName'] = $systemFileName;
            $return['head'] = array();
            $return['content'] = array();
            
            
            // Instancia PHPEXCEL
            $objPHPExcel = null;
            switch($type)
            {
                case 'application/vnd.ms-excel': $objReader = new \PHPExcel_Reader_Excel2003XML(); break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': $objReader = new \PHPExcel_Reader_Excel2007(); break;
                case 'text/x-csv': $objPHPExcel = \PHPExcel_IOFactory::load($directory . '/' . $systemFileName ); break;
            }
            
            
            // Carrega arquivo se tiver um tipo de leitor definido
            if ($objPHPExcel === null )
                $objPHPExcel = $objReader->load($directory . '/' . $systemFileName );
            // Ativa SHEET 1
            $sheet = $objPHPExcel->getSheet(0);

            // Obtem o nome da máxima coluna. ex:. A; B; E; M;
            $nameMaxColumn = $sheet->getHighestColumn();
            // Obtem o numero da máxima coluna de acordo com o nome
            $qtdeColumn = \PHPExcel_Cell::columnIndexFromString($nameMaxColumn)-1;
            
            // obtem  a posição dos cabeçalhos
            for ($i = 0; $i <= $qtdeColumn; $i++)
            {
                $col = \PHPExcel_Cell::stringFromColumnIndex($i);
                $value = strtolower($sheet->getCell($col . '1')->getValue()) ;
                array_push($return['head'], $value );
            }
            
            
            
             
            foreach ($sheet->getRowIterator() as $row => $rowLine)
              if ($row > 1)
              {
                  $dataRow = null;
                  $dataRow['telefones'] = array();
                  foreach($return['head'] as $col => $head)
                  {
                      
                      if (! empty(trim($head)))
                      {
                          if (stripos($head, 'cpf') !== false )
                              $dataRow['dados']['cpf'] = $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue();
                          if (stripos($head, 'nome') !== false )
                              $dataRow['dados']['nome'] = $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue();
                          if (stripos($head, 'nascimento') !== false )
                              $dataRow['dados']['nascimento'] = date('Y-m-d',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue()));
                          if (stripos($head, 'convenio') !== false )
                              $dataRow['dados']['convenio'] = $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue();
                          if (stripos($head, 'extra') !== false )
                              $dataRow['dados']['dadosExtra'] = $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue();
                          if (stripos($head, 'telefone') !== false )
                          {
                              $value = ltrim($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($col). $row)->getValue());
                              if (! empty($value))
                                  array_push($dataRow['telefones'], $value );
                          }
                              
                      }
                    
                   }
                  if (! empty($dataRow['dados']['cpf']))
                     array_push($return['content'], $dataRow );
              }
                    
                
        } // fim $moved
        
        
        return $return;
        
    }

	

}

?>