<?php
namespace Gauchacred\library\php;


class ApiFile 
{
    

    /** 
    * Pesquisa pelo ícone de um mime type informado em filepath Ex: getMimeIcon(\Application::getIndexPath(). '/images/programs/'.   $nameIcon . '30x30.png' )
    * getMimeIcon tenta localizar o arquivo UNDEFINED30x30.png caso não encontre filePath
    * @param filePath String Path absoluto para o arquivo
    * @access public 
    * @return String|false Retorna uma string base64 encoded em caso de sucesso ou false em caso de falha.
    */ 
    public static final function getMimeIcon($filePath)
    {
        //echo $filePath; exit;
        $string = false;
        if (file_exists($filePath))
        {
            $string = file_get_contents($filePath);
            if ($string !== false)
                $string = base64_encode($string);
        }else
        {
            $aux = explode(DS, $filePath);
            array_pop($aux);
            $filePath = implode(DS, $aux). DS . 'UNDEFINED30x30.png';
            if (file_exists($filePath))
            {
                $string = file_get_contents($filePath);
                if ($string !== false)
                    $string = base64_encode($string);
            }
        }
        
        return $string;
    }
    
    
    public static final function getNameIcon($mime)
    {
        $connection = \Application::getNewDataBaseInstance();
        $return = false; 
        $query = "
            select nomeicone from mimetypes where nomemime = ? limit 1;
        ";
         if ($stm = $connection->prepare($query))
        {
            $stm->bind_param('s',  $mime);
            if ($stm->execute())
            {
                $stm->bind_result($nomeIcone);
               $stm->fetch();
               $return = $nomeIcone;
                
            }
            else
                $return = false;
        }
        else
        {
            \Application::setMysqlLogQuery('Classe ApiFile; Método getNameIcon; Mysql '. $connection->error); 
            $return = false;
        }
        
        return $return;
    }
    
    
    public static function normalizeName($name)
    {
        // obtem a extensão
        $arName = explode('.', $name);
        $ext = array_pop($arName);
        $newName = implode('.', $arName);
         
        
        $LetraProibi = array('"',".","'","\"","&","|","!","#","$","¨","*","(",")","`","´","<",">",";","=","+","§","{","}","[","]","^","~","?","%");
         $special = array('Á','È','ô','Ç','á','è','Ò','ç','Â','Ë','ò','â','ë','Ø','Ñ','À','Ð','ø','ñ','à','ð','Õ','Å','õ','Ý','å','Í','Ö','ý','Ã','í','ö','ã',
            'Î','Ä','î','Ú','ä','Ì','ú','Æ','ì','Û','æ','Ï','û','ï','Ù','®','É','ù','©','é','Ó','Ü','Þ','Ê','ó','ü','þ','ê','Ô','ß','‘','’','‚','“','”','„');
         $clearspc = Array('a','e','o','c','a','e','o','c','a','e','o','a','e','o','n','a','d','o','n','a','o','o','a','o','y','a','i','o','y','a','i','o','a',
            'i','a','i','u','a','i','u','a','i','u','a','i','u','i','u','','e','u','c','e','o','u','p','e','o','u','b','e','o','b','','','','','','');
         $newName = str_replace($special, $clearspc, $newName);
          $newName = str_replace($LetraProibi, "", trim( $newName));
         return  $newName . '.' . $ext;
    }
    
    public static function moveUploadedErrorDescript($error)
    {
        $return = '';
        if (is_int($error))
        {
            switch($error)
            {
                case 1:
                    $return = 'O tamanho do arquivo excedeu o limite máximo permitido';
                    break;
                case 2:
                    $return = 'O tamanho do arquivo excedeu o limite máximo permitido';
                    break;
                case 3:
                    $return = 'O upload do arquivo não foi interrompido';
                    break;
                case 4:
                    $return= 'Nenhum arquivo foi enviado';
                    break;
                case 6:
                    $return= 'Erro no servidor. Pasta temporaria ausente';
                    break;
                case 7:
                    $return = 'Erro no servidor. Falha em escrever o arquivo em disco';
                    break;
                case 8:
                    $return = 'Erro no servidor. Uma extensão interrompeu o upload do arquivo';
                    break;
                default:
                    $return = 'Erro no servidor. Não foi possível determinar o erro';
                    break;
            }
        }else
        {
            switch($error)
            {
                case 'UPLOAD_ERR_INI_SIZE':
                    $return = 'O tamanho do arquivo excedeu o limite máximo permitido';
                    break;
                case 'UPLOAD_ERR_FORM_SIZE':
                    $return = 'O tamanho do arquivo excedeu o limite máximo permitido';
                    break;
                case 'UPLOAD_ERR_PARTIAL':
                    $return = 'O upload do arquivo não foi interrompido';
                    break;
                case 'UPLOAD_ERR_NO_FILE':
                    $return= 'Nenhum arquivo foi enviado';
                    break;
                case 'UPLOAD_ERR_NO_TMP_DIR':
                    $return= 'Erro no servidor. Pasta temporaria ausente';
                    break;
                case 'UPLOAD_ERR_CANT_WRITE':
                    $return = 'Erro no servidor. Falha em escrever o arquivo em disco';
                    break;
                case 'UPLOAD_ERR_EXTENSION':
                    $return = 'Erro no servidor. Uma extensão interrompeu o upload do arquivo';
                    break;
                default:
                    $return = 'Erro no servidor. Não foi possível determinar o erro';
                    break;
            }
        }
        
        return $return;
    }
    
    
    
    
    
    
}


?>