<?php

session_name("SSID"); // Altera o nome do identificador de sessão gerado pelo PHP
session_start();
define('WWW_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);


//PHPEXCel
//include 'library\php\PHPExcel\PHPExcel.php';
include 'library/php/PHPExcel/PHPExcel/Autoloader.php';
//include (\Application::getIndexPath(). '/library/php/dompdf/autoload.inc.php');



class Gauchacred
{
    const PREFIX = 'Gauchacred';

    /**
     * Register the autoloader
     */
    public static function register()
    {
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Autoloader
     *
     * @param string
     */
    public static function autoload($class)
    {

   //     echo $class;
  // echo '<pre>'; var_dump( spl_autoload_functions( ) ); echo '</pre>';
        $prefixLength = strlen(self::PREFIX);
        if (0 === strncmp(self::PREFIX, $class, $prefixLength)) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $prefixLength));
            $file = realpath(__DIR__ . (empty($file) ? '' : DIRECTORY_SEPARATOR) . $file . '.php');
            if (file_exists($file)) {
                require_once $file;
            }else
                \Application::print404();
        }
    }
}

Gauchacred::register();


/*

spl_autoload_register(function ($class) {
    echo $class;
   echo '<pre>'; var_dump( spl_autoload_functions( ) ); echo '</pre>';
    if ((strpos($class, 'PHPExcel') !== 0) && (strpos($class, 'Dompdf') !== 0))
    {
	   $class = WWW_ROOT. DS . str_replace('\\', DS, $class . '.php');
        if (! file_exists($class))
        {
            //throw new Exception("Arquivo '$class' n&atilde;o encontrado");
           // echo $class;
            Application::print404();
        }
        require_once($class);
    }
});
*/

/**
* Classe Principal. Executado em toda chamada ao site
*
* @author vivaFC
* @version 1.0
* @copyright VIVAFC © 2015, moisés, ludimila.
* @access public
* @package vivaFC
* @subpackage Aplicação
*/
class Application
{
	/**
    * Nome do sistema.
    * @access private
    * @static
    * @name $filesPath
    */

    private static $systemName;

    /**
    * Armazena o caminho absoluto do diretório onde os arquivos estão.
    * @access private
    * @static
    * @name $filesPath
    */
    private static $filesPath;
    /**
    * Armazena o caminho absoluto do diretório da aplicação.
    * @access private
    * @static
    * @name $indexPath
    */
	private static $indexPath;
    /**
    * Armazena o endereço de localização do Banco de Dados (SGBD)
    * @access private
    * @static
    * @name $hostDataBase
    */
	private static $hostDataBase;
    /**
    * Armazena o nome da Base de Dados
    * @access private
    * @static
    * @name $nameDataBase
    */
	private static $nameDataBase;
    /**
    * Armazena o Login de acesso ao Banco de Dados
    * @access private
    * @static
    * @name $loginDataBase
    */
	private static $loginDataBase;
    /**
    * Armazena a senha de acesso ao Banco de Dados
    * @access private
    * @static
    * @name $passwordDataBase
    */
	private static $passwordDataBase;
    /**
    * Armazena o tipo de protocolo utilizado na conexão com o site. (HTTP | HTTPS)
    * @access private
    * @static
    * @name $protocol
    */
	private static $protocol;
    /**
    * Armazena a URL ao qual o site está respondendo
    * @access private
    * @static
    * @name $urlSite
    */
	private static $urlSite;
    /**
    * Armazena a URL de acesso aos arquivos do diretório em $filesPath.
    * @access private
    * @static
    * @name $urlFiles
    */
	private static $urlFiles;
    /**
    * Armazena o nome do controlador da aplicação (MVC)
    * @access private
    * @static
    * @name $nameController
    */
	private static $nameController;
    /**
    * Armazena o nome da ação a ser utilizada pelo controlador
    * @access private
    * @static
    * @name $nameAction
    */
	private static $nameAction;
    /**
    * Armazena as variáveis  passados pela URL
    * @access private
    * @static
    * @name $urlParams
    */
	private static $urlParams;




	/**
    * Headers enviados na requisição
    * @access private
    * @static
    * @name $headers
    */
	private static $headers;


	/**
    * O caminho do diretório para o arquivo de log de execução de query
    * @access private
    * @static
    * @name $mysqlLogPath
    */
	private static $mysqlLogPath;

    /**
    * Armazena as informações passadas na requisição através de POST ou GET
    * @access private
    * @static
    * @name $request
    */
	private static $request = null;

    /**
    * Contém informações sobre os recursos autorizados para o usuário
    * @access private
    * @static
    * @name $request
    */
    private static $permissions = array();

	public function __construct()
	{


	}

    /**
    * Configura o nome do sistema
    * @access public
    * @static
    * @param string $name Nome do sistema.
    * @return void
    */

    public static function setSystemName($name)
    {
        self::$systemName = $name;
    }

    /**
    * Recupera o nome do sistema
    * @access public
    * @static
    * @return string Nome do sistema
    */

    public static function getSystemName()
    {
        return self::$systemName;
    }

	/**
    * Inicia a aplicação
    * @access public
    * @static
    * @return void
    */
	public static function startApplication()
	{
		self::setProtocol();
		self::setUrlSite();
		self::setController();
     	self::setHeaders();
        self::setRequest();
        self::initPermissions();

	}




    /**
    * Configura o caminho absoluto (path) do diretório dos arquivos
    * @access public
    * @static
    * @param string $path Caminho absoluto para o diretório dos arquivos.
    * @return void
    */
	public static function setFilesPath($path)
	{
		self::$filesPath = $path;
	}

    /**
    * Configura o caminho absoluto (path) do diretório da aplicação
    * @access public
    * @static
    * @param string $path Caminho absoluto para o diretório da aplicação.
    * @return void
    */

	public static function setIndexPath($path)
	{
		self::$indexPath = $path;
	}

    /**
    * Configura o endereço do SGBD
    * @access public
    * @static
    * @param string $host Caminho absoluto para o diretório dos arquivos.
    * @return void
    */

	public static function setHostDataBase($host)
	{
		self::$hostDataBase = $host;
	}

    /**
    * Configura o nome da Base de Dados
    * @access public
    * @static
    * @param string $name Nome da Base de Dados.
    * @return void
    */

	public static function setNameDataBase($name)
	{
		self::$nameDataBase = $name;
	}

    /**
    * Configura o login de acesso ao Banco de Dados
    * @access public
    * @static
    * @param string $login Nome de login de acesso a base de dados
    * @return void
    */

	public static function setLoginDataBase($login)
	{
		self::$loginDataBase = $login;
	}

    /**
    * Configura a senha de acesso a Base de dados
    * @access public
    * @static
    * @param string $password Senha de acesso a base de dados
    * @return void
    */

	public static function setPasswordDataBase($password)
	{
		self::$passwordDataBase = $password;

	}



    /**
    * Configura o nome do controlador
    * @access private
    * @static
    * @return void
    */


	private static function setController()
	{
		$aux =  str_replace('#','',preg_replace('/[\/]$|^[\/]/', '', $_SERVER['REQUEST_URI']));

		$aux = ($aux != '')? explode('/',$aux) : '';

		if ($_SERVER['SERVER_ADDR'] == '::1'  || $_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST'] == 'localhost' )
		  if (is_array($aux))
		     array_shift($aux);

		if ((! is_array($aux)) || (count($aux) < 1))
		{
			// Define o controlador padrão
			self::$nameController = 'Index';
			self::$nameAction =  'actionDefault';
		}else
		{
			self::$nameController = '';
			//adapta o nome do controlador para um nome de classe.
			//Substitui os hifens do nome do controlador para transformar em camelCase
			//Exemplo: o nome agenda-formatura se tornaria a classe AgendaFormatura
			$arrayCC = explode('-',$aux[0]);
			foreach($arrayCC as $i => $value)
			{
				self::$nameController .= ucfirst(strtolower($value));
			}

			//adapta o nome do método

			self::$nameAction = '';
			if (count($aux) > 1)
			{
				$arrayCC = explode('-',$aux[1]);
				foreach($arrayCC as $i => $value)
				{
					if ($i == 0)
						self::$nameAction .= strtolower($value);
					else
						self::$nameAction .= ucfirst(strtolower($value));
				}
			}else
				self::$nameAction = 'actionDefault';


			//remove os controladores do array para ficar somente os parametros GET
			for ($i = 0; $i < 2; $i++)
			   if (is_array($aux) && count($aux) > 0)
			      array_shift($aux);
		    // se $aux continuar sendo um vetor é porque existe parâmetros

            $params = array();
            if (is_array($aux))
                foreach($aux as $i => $value)
                    if (strpos($value, '?') === false )
                        array_push($params, $value);
                    else
                       break;


            if (count($_GET) > 0)
                foreach($_GET as $i => $value)
                    $params[$i] = $value;


			self::$urlParams = (is_array($params))? $params : NULL;

		}
	}


	/**
    * Configura o protocolo utilizado na conexão. (HTTP|HTTPS)
    * @access private
    * @static
    * @return void
    */
	private static function setProtocol()
	{

        self::$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';

	}

    /**
    * Configurado a url utilizada pela aplicação
    * @access private
    * @static
    * @return void
    */

	private static function setUrlSite()
	{
		self::$urlSite = self::$protocol . '://'. $_SERVER['SERVER_NAME'];
	}

	/**
    * Configurado a url utilizada pera arquivos
    * @access public
    * @static
	* @param $url Uri para os arquivos do site. Não informar o protocolo, pois será obtido o da conexão
    * @return void
    */

	public static function setUrlFiles($url)
	{
		self::$urlFiles = self::$protocol . '://' . $url;
	}


	/**
    * Configura os headers da requisição
    * @access private
    * @static
    * @return void
    */
	private static function setHeaders()
	{

		$headersExclude = array (
			'MIBDIRS', 'MYSQL_HOME','OPENSSL_CONF','PHP_PEAR_SYSCONF_DIR','PHPRC','TMP','HTTP_HOST','HTTP_ACCEPT',
			'CONTENT_LENGTH','HTTP_EXPECT','CONTENT_TYPE','PATH','SystemRoot','COMSPEC',
			'PATHEXT', 'WINDIR', 'SERVER_SIGNATURE', 'SERVER_SOFTWARE', 'SERVER_NAME','SERVER_ADDR',
			'SERVER_PORT', 'REMOTE_ADDR', 'DOCUMENT_ROOT', 'REQUEST_SCHEME', 'CONTEXT_PREFIX',
			'CONTEXT_DOCUMENT_ROOT', 'SERVER_ADMIN', 'SCRIPT_FILENAME', 'REMOTE_PORT', 'GATEWAY_INTERFACE',
			'SERVER_PROTOCOL', 'REQUEST_METHOD', 'QUERY_STRING', 'REQUEST_URI','SCRIPT_NAME',
			'PHP_SELF','REQUEST_TIME_FLOAT','REQUEST_TIME'
		);

		$headers = array();

		foreach ($_SERVER as $key => $value)
			if (! in_array($key, $headersExclude))
			{
				$v = strtolower(str_replace('HTTP_','',$key));
				$headers[$v] = (! is_string($value)) ? $value : strtolower($value);

			}

		self::$headers = $headers;

	}

    /**
    * Configura o request
    * @access private
    * @static
    * @return void
    */

    private static function setRequest()
    {
        if (isset($_POST) && count($_POST) > 0)
        {
            self::$request['metodo'] = 'POST';
            foreach($_POST as $i => $value)
                self::$request[strtolower($i)]['value'] = $value;


        }
        if (isset($_GET) && count($_GET) > 0)
        {
            self::$request['metodo'] = 'GET';
            foreach($_GET as $i => $value)
                self::$request[strtolower($i)] = $value;

        }else
            if (isset($_REQUEST) && count($_REQUEST) > 0)
            {
                self::$request['metodo'] = 'REQUEST';
                foreach($_REQUEST as $i => $value)
                    self::$request[strtolower($i)] = $value;

            }
    }



    /**
    * Configura as permissões de uso do usuário
    * @access private
    * @static
    * @return void
    */

    public static function initPermissions()
    {


        $connection = self::getNewDataBaseInstance();
        $query = "
                select distinct
                gr.id as 'idgruporecurso',
                gr.descricao as 'nomegruporecurso',
                r.id as 'idrecurso', r.nome as 'nomerecurso', r.descricao as 'descricaorecurso', r.pagina, r.indicamenu, r.nomemenu,
                a.id as 'idautorizacao', a.ler, a.escrever, a.remover, r.tagicon

                from gruporecurso gr
                  inner join recurso r on r.gruporecurso_id = gr.id
                  inner join autorizacoes a on a.recursos_id = r.id
                  inner join perfil p on p.id = a.perfil_id
                  inner join perfilusuario pu on pu.perfil_id = p.id


                where (a.ler = 1 or a.escrever = 1 or a.remover = 1) and pu.usuarios_id = ?
                order by gr.descricao, r.nome
        ";


         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('i', $_SESSION['userid']);
            if ($stm->execute())
            {
                $stm->bind_result($idGrupoRecurso, $nomeGrupoRecurso, $idRecurso, $nomeRecurso, $descricaoRecurso, $pagina, $indicaMenu, $nomeMenu, $idAutorizacao, $ler, $escrever, $remover, $tagIcon);

                 while ($stm->fetch()) {
                     $p['idGrupoRecurso'] = $idGrupoRecurso;
                     $p['nomeGrupoRecurso'] = $nomeGrupoRecurso;
                     $p['idRecurso'] = $idRecurso;
                     $p['nomeRecurso'] = $nomeRecurso;
                     $p['descricaoRecurso'] = $descricaoRecurso;
                     $p['pagina'] = $pagina;
                     $p['indicaMenu'] = (bool) $indicaMenu;
                     $p['nomeMenu'] = $nomeMenu;
                     $p['idAutorizacao'] = $idAutorizacao;
                     $p['ler'] = (bool) $ler;
                     $p['escrever'] = (bool) $escrever;
                     $p['remover'] = (bool) $remover;
                     $p['tagIcon'] = $tagIcon;

                     if (! array_key_exists(Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso),  self::$permissions))
                        self::$permissions[Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso)][$nomeRecurso] = $p;
                     else
                         self::$permissions[Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso)][$nomeRecurso] = $p;
                 }
            }

        }
         else
        {
            self::setMysqlLogQuery('Classe Application; Método initPermissions; Mysql '. $connection->error);
        }

      // echo '<pre>'; var_dump(self::$permissions); exit;

       // return $return;


    }


    /**
    * Retorna as permissões do usuário
    * @access public
    * @static
    * @return array
    */

    public static function getPermissions()
    {
        return self::$permissions;
    }



    /**
    * Retorna se o usuário está autorizado para determinado recurso
    * @access public
    * @static
    * @return boolean
    */

    public static function isAuthorized($nomeGrupoRecurso, $nomeRecurso, $permissao)
    {
        $return = false;
        if (is_array(self::$permissions))
            if (array_key_exists(Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso), self::$permissions))
                if (array_key_exists($nomeRecurso, self::$permissions[$nomeGrupoRecurso]))
                    if (
                           (self::$permissions[Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso)][$nomeRecurso]['ler'] === true && strtolower($permissao) == 'ler') ||
                            (self::$permissions[Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso)][$nomeRecurso]['escrever'] == true && strtolower($permissao) == 'escrever') ||
                            (self::$permissions[Gauchacred\library\php\Utils::semAcentos($nomeGrupoRecurso)][$nomeRecurso]['remover'] == true && strtolower($permissao) == 'remover')
                       )
                    {
                        $return = true;
                    }
        return $return;
    }



    /**
    * Configura o request
    * @access private
    * @static
    * @return void
    */

    public static function getRequest($position = null)
    {

        if ($position != null)
        {
            if (isset(self::$request[$position]))
                return self::$request[$position];
        }else
            return self::$request;

    }


    /**
    * Verifica se a chamada é do tipo WebService
    * @access public
    * @static
    * @return boolean
    */

    public static function isRequestWs()
    {
        if (isset(self::$headers['requestws']) && self::$headers['requestws'] == 'sim')
            return true;
        else
            return false;
    }


	/**
    * Obtém o headers da requisição
    * @access public
    * @static
	* @param $key (OPCIONAL) Nome da chave do header que deseja obter o valor
    * @return array|string Retorna um array com o conjunto de valores ou o valor especificado pela chave $key
    */

	public static function getHeaders($key = null)
	{
		if ($key == null)
			return self::$headers;
		else
		{
			$key = strtolower($key);
			return 	self::$headers[$key];
		}
	}



    /**
    * Configura o path onde ficara o arquivo de log do mysql
    * @access public
    * @static
    * @return void
    */

    public function setMysqlLogPath($path)
    {
        self::$mysqlLogPath = $path;
    }



    /**
    * Gera logs de query do MYSQL
    * @access public
    * @static
    * @return void
    */

    public static function setMysqlLogQuery($mensagem)
    {

        if (!  file_exists(self::$mysqlLogPath))
               mkdir(self::$mysqlLogPath);

          $data = date('d/m/Y H:i:s');
          $path = self::$mysqlLogPath . '/mysql.query.log';
          $numLinhas =  (! file_exists($path)) ? 0 : count(file($path));
          if ($numLinhas > 500)
                $file = fopen($path, 'w+');
          else
                $file = fopen($path, 'a+');
          $escreve = fwrite($file, $data. " " . $mensagem. "\r\n");
          fclose($file);

    }





    /**
    * Retorna o caminho absoluto (path) do diretório dos arquivos
    * @access public
    * @static
    * @return String contendo o path do diretório dos arquivos
    */

	public static function getFilesPath()
	{
		return self::$filesPath;
	}

    /**
    * Retorna o caminho absoluto (path) do diretório da aplicação.
    * @access public
    * @static
    * @return String contendo o path do diretório da aplicação.
    */

	public static function getIndexPath()
	{
		return self::$indexPath;
	}

    /**
    * Retorna o protocolo usado na conexão
    * @access public
    * @static
    * @return String contendo o protocolo usado na conexão.
    */

	public static function getProtocol()
	{
		return self::$protocol;
	}

    /**
    * Retorna a url de acesso a aplicação
    * @access public
    * @static
    * @return String contendo a URL de acesso a aplicação.
    */

	public static function getUrlSite()
	{
		return self::$urlSite;
	}

    /**
    * Retorna a URL de acesso ao arquivos em $filesPath
    * @access public
    * @static
    * @return String contendo a URL de acesso aos arquivos
    */

	public static function getUrlFiles()
	{
		return self::$urlFiles;
	}

    /**
    * Retorna o nome do controlador
    * @access public
    * @static
    * @return String Contendo o nome do controlador invocado
    */

	public static function getNameController()
	{
		return self::$nameController;
	}

    /**
    * Retorna o nome do método a ser invovado pela aplicação
    * @access public
    * @static
    * @return String contendo o nome do método do controlador a ser invocado.
    */

	public static function getNameAction()
	{
		return self::$nameAction;
	}

    /**
    * Retorna as variáveis passadas por GET
    * @access public
    * @static
	* @param position (OPICIONAL) Posição que deseja obter o valor.
    * @return array
    */

	public static function getUrlParams($position = null)
	{
		if ($position !== null)
        {
            if (isset(self::$urlParams[$position]))
                return self::$urlParams[$position];
            else
                return null;
        }
		else
			return self::$urlParams;
	}

    /**
    * Retorna o endereço de conexão com o Banco de Dados (SGBD)
    * @access public
    * @static
    * @return String
    */

	public static function getHostdataBase()
	{
		return self::$hostDataBase;
	}

    /**
    * Retorna o nome da Base de Dados configurada
    * @access public
    * @static
    * @return void
    */

	public static function getNameDataBase()
	{

		return self::$nameDataBase;
	}

    /**
    * Retorna o login de acesso ao SGBD
    * @access public
    * @static
    * @return String
    */

	public static function getLoginDataBase()
	{
		return self::$loginDataBase;
	}

    /**
    * Retorna a senha de acesso ao SGBD
    * @access public
    * @static
    * @return String
    */

	public static function getPasswordDataBase()
	{
		return self::$passwordDataBase;
	}



    /**
    * Retorna um objeto de conexão ao Banco de Dados
    * @access public
    * @static
    * @return objeto mysqli
    */

    public static function getNewDataBaseInstance()
    {
        if (self::getHostdataBase() == '')
		   throw new Exception("Host do banco de dados não definido");
        if (self::getNameDataBase() == '')
		   throw new Exception("Base de dados não definida");

        $mysqli = new mysqli(self::GetHostDataBase(), self::GetLoginDataBase(), self::GetPasswordDataBase(),self::GetNameDataBase());
        if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
        //$charset = $mysqli->character_set_name();
        if (!$mysqli->set_charset('utf8mb4'))
        {
            printf("VivaFC n&atilde;o conseguiu setar o padr&atilde;o de caractere para utf8: %s\n", $mysqli->error);
            exit;
         }
        return $mysqli;

    }


    public static function print404()
    {
          require_once  self::getIndexPath(). DS. 'view'. DS . 'head.php';
         require_once self::getIndexPath(). DS.'templates'. DS . 'erro' . DS . '404.php';
           require_once  self::getIndexPath(). DS. 'view'. DS . 'footer.php';
        exit;
    }



    /**
    * dispacha a requisição paara o controlador (Controller) e executa
    * método referente e  acao (Action)
    * @access public
    * @static
    * @return void
    */

	public static function dispatch()
	{



        //prepara a variavel classnameinvoke com o path do arquivo que contém a classe a ser usada
		$classNameInvoke = 'Gauchacred\controller\\' . self::getNameController();
        // prepara a variável action com o nome do método que foi chamado
		$action = self::getNameAction();
        // instancia a classe que está referenciada na classNameinvoke
		$controller = new $classNameInvoke();

        // chama o método que está referenciado em action
        if (method_exists($controller, $action) === true)
		  $controller->$action();
        else{
            self::print404();
        }

        // chama o método que está referenciado em action
       // $controller->$action();
	}

    /**
    * Redireciona a chamada http para outra página
    * @param string $st_uri
    */
    static function redirect( $st_uri )
    {
        header("Location: $st_uri");
    }

}


//Define timezone
date_default_timezone_set("Brazil/East");

//define o nome do sistema
Application::setSystemName('wisistemas');

$pathInfo = pathinfo(__FILE__);
Application::setIndexPath(str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"].substr($pathInfo['dirname'], strlen($_SERVER["DOCUMENT_ROOT"]))));
Application::setFilesPath(Application::getIndexPath());
Application::setMysqlLogPath(Application::getIndexPath());
//Application::initDirectoryFile();

//Define a URL dos arquivos
Application::setUrlFiles(Application::GetProtocol(). '://' . $_SERVER['HTTP_HOST']);
//Define o endereço do servidor do Banco de Dados
Application::setHostdataBase("127.0.0.1");
//Define o nome da Base de Dados
Application::setNameDataBase("gauchacred"); //ourogold_perfil
//Define login da Base de Dados
Application::setLoginDataBase("root"); //ourogold_perfil
//Define a senha da Base de Dados
Application::setPasswordDataBase(''); //ourosite$$


//Inicia Application
Application::startApplication();




//Passa tarefa para o controlador da página
Application::dispatch();



?>
