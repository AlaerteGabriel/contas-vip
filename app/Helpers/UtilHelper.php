<?php
namespace App\Helpers;

class UtilHelper {

    public static function dataBr(string $dataStr = null, bool $time = true)
    {

        $dt = date('Y-m-d H:i:s');
        $formato = 'd/m/Y H:i:s';

        if($dataStr){
            $dt = $dataStr;
        }

        if(!$time){
            $formato = 'd/m/Y';
        }

        $data = \Carbon\Carbon::parse($dt)->format($formato);
        return $data;
    }

    //monta os digitos de cpf ou cnpj
    public static function cpfCnpj($value)
    {

        if(!$value){
            return false;
        }

        $value = substr($value, 0, 14);
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        if(strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    //elimina caracteres
    public static function clear($string=null)
    {

        if(!$string){
            return null;
        }

        $string = trim($string);
        $string = str_replace(['.',',','-','_','/','(',')',';','#','!','>','<','[',']','@','*','&','=',' ',':','?','}','{'], '', $string);
        return $string;
    }

    //moeda brasileira
    public static function moeda($get_valor, $simbol = false)
    {

        $valor = 0.00;

        if($get_valor)  {
            $valor_moeda = $get_valor;
            // $real = new NumberFormatter('pt_BR',  NumberFormatter::CURRENCY);
            // return $real->formatCurrency($valor_moeda, 'BRL');
            $valor = number_format($valor_moeda, 2, ',','.');
        }

        $valor = ($simbol) ? 'R$ '.$valor : $valor;
        return trim($valor); //retorna o valor formatado para gravar no banco
    }

    public static function percent($percent=1, $total=null)
    {
        if(empty($total) || empty($percent)){
            return 0;
        }

        return ($total*$percent)/100;
    }

    public static function avaliacaoStar($media, $total=0)
    {
        $str = '';
        for($i = 1; $i <= 5; $i++){
            if($i <= $media){
                $str .= '<i class="ci-star-filled text-warning"></i>';
            }else{
                $str .= '<i class="ci-star text-body-tertiary opacity-60"></i>';
            }
        }

        return $str;
    }

}
