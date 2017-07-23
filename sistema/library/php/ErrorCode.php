<?php
namespace library\php;

class ErrorCode
{
    private static $error = 
        array
        (
            1       =>  'Cliente Não Autenticado',
            2       =>  'Valor não pode ser nulo',
            3       =>  'Falha na autenticação',
            4       =>  'Nenhum registro encontrado',
        
            50      =>  'Usuário já participa do jogo',
        
            60      =>  'Nenhum convite encontrado',
        
            500     =>  'Erro Interno',
        
            1062    => 'Entrada duplicada para campo único',
        );
    
    
        
    public static function getCodeError($messageError)
    {
        $code = null;
        foreach(self::$error as $i => $value)
        {
            if (strtolower($messageError) == strtolower($value))
            {
                $code = $i;
                break;
            }
        }
        return $code;
        
    }
    
    
    public static function getMessageError($codeError)
    {
        $message = null;
        if (array_key_exists($codeError, self::$error))
            $message = self::$error[$codeError];
        return $message;
    }
    
}

?>