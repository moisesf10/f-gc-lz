<?php
namespace Gauchacred\library\php;
/** 
* Classe que serve para fazer a criptografia de senhas
* 
* @author vivaFC  
* @version 1.0 
* @copyright VIVAFC © 2015, moisés, ludmila. 
* @access public 
* @package vivaFC
* @subpackage Aplicação
* @abstract
*/ 
class Blowfish
{
    /** 
    * armazena o salt utilizada para fazer a criptografia. 
    * Deve ser uma string de 22 caracteres que respeite a expressão regular ./0-9A-Za-z
    * @access public 
    * @static
    * @name $salt 
    */ 
    public static $salt = 'Cf1f11eVIrKlBJomM0F6aM';
    /** 
    * armazena o custo de processamento para realizar a criptografia. É uma potência de 2 que indica quantos ciclos será aguardados. 
    * deve ser um número inteiro entre 4 e 31, outro detalhe é que o custo precisa ter dois dígitos, 
    * então números menores que 10 precisam ter zero à esquerda
    * @access public 
    * @static
    * @name $custo 
    */
    public static $custo = '04';
    
     /** 
    * Criptografa uma senha utilizando BlowFish
    * @access public 
    * @static
    * @param String String a ser criptografada
    * @return String Retorna o hash gerado
    */ 
    public static function crypt($senha)
    {
        if (preg_match('/[ ]/',$senha))
            throw new  \Exception("A senha não pode conter espaços em branco");
        
        // Atualiza o salt com um valor aleatorio
        self::updateSaltRandom();
        // Gera um hash baseado em bcrypt
        return crypt($senha, '$2a$' . self::$custo . '$' . self::$salt . '$');   
    }
    
    
    /** 
    * Verifica se uma string é igual ao hash
    * @access public 
    * @static
    * @param String String a ser validada
    * @param String Hash que será comparado
    * @return boolean True em caso de igualdade ou false caso contrario
    */ 
    public static function compare($str, $hash)
    {
        if (crypt($str, $hash) === $hash) {
            return true;
        } else {
            return false;
        }   
    }
    
    private static function updateSaltRandom() {
	    $tamanho = 22;
        self::$salt = substr(sha1(mt_rand()), 0, $tamanho);  
    }
    
    

}

?>