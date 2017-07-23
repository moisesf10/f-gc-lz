<?php
namespace Gauchacred\library\php;


class Utils 
{
    
    
   public static final function formatStringDate($date, $oldFormat = 'd/m/Y H:i:s', $newFormat = 'Y-m-d H:i:s')
    {
        $return = null;
            
        if (!empty($date) && $v_date = date_create_from_format($oldFormat, $date)) 
        {
             
          //  $v_date = date_format($v_date, $format);
            
                $return = $v_date->format($newFormat);
        }
        
        return $return;
    }
    
    public static final function moneyToNumber($money)
    {
        $money = preg_replace('/[.]/','', $money);
        $money = preg_replace('/[,]/', '.', $money);
        return $money;
    }
    
    
    public static final function numberToMoney($number)
    {       
        return number_format( $number , 2, ',', '.');
    }
    
   /**
    * Formata um CPF o deixando no modelo ###.###.###-##
    * @param String CPF a ser formatado
    * @return string O CPF formatado;
    */
    public static final function formatCpfHowString($cpf)
    {
        $mask = "###.###.###-##";
        
        $cpf = preg_replace('/[.-]/','',$cpf);

        for($i=0;$i<strlen($cpf);$i++){
            $mask[strpos($mask,"#")] = $cpf[$i];
        }

        return $mask;

    }
    
    public static function semAcentos($str)
    {
            $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
            $to = "aaaaeeiooouucAAAAEEIOOOUUC";
            
            $keys = array();
            $values = array();
            preg_match_all('/./u', $from, $keys);
            preg_match_all('/./u', $to, $values);
            $mapping = array_combine($keys[0], $values[0]);
            return strtr($str, $mapping);
        
    }
    
}


?>