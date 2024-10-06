<?php

namespace Classes;

class CSVLine
{
    public static $requiredFields = [
        "Nome",
        "Sobrenome",
        "Email",
        "Telefone",
        "Endereço",
        "Cidade",
        "CEP",
        "Data de Nascimento"
    ];
    public function __construct(
        private array $csvData,
        private int $lineNumber, 
    )
    {
        $diff = array_diff_key(array_flip(CSVLine::$requiredFields), $this->csvData);
        if(count($diff) != 0){
            $message = "Erro na linha " . $this->lineNumber . ", campos faltando: ";
            foreach($diff as $key => $value){
                $message .= $key .",";
            }
            $message  = substr($message, 0, -1) ;
            throw new \Exception($message);
        }
        // foreach ($csvData as $key => $value) {
        //     if($value === null){
        //         throw new \Exception( "Erro na linha " . $this->lineNumber . ", campo faltando: ");
        //     }
        // }
        $this->validate();
    }
    public function getData() {
        return $this->csvData;
    }
    private function validate(): void {
        $phone = CSVLine::validateBrazilianPhone($this->csvData["Telefone"]);
        if($phone === null){
            throw new \Exception("Erro na linha " . $this->lineNumber . ", telefone '". htmlspecialchars($this->csvData["Telefone"], ENT_QUOTES, 'UTF-8') . "' é inválido.");
        }

        $this->csvData["Telefone"] = $phone;
        $this->csvData["WhatsApp"] = CSVLine::validateBrazilianWhatsapp($phone);
    }

    public static function validateBrazilianPhone(string $number): string|null {
        $number = preg_replace('/\D/', '', $number);
        $numberLength = strlen($number);
    
        if ($numberLength < 10 || $numberLength > 13) {
            return null;
        }
    
        if (substr($number, 0, 2) === '55' && $numberLength > 11) {
            $number = substr($number, 2);
        }
    
        $num = substr($number, -8);
        $eighthDigit = intval(substr($num, 0, 1));
        $ddd = substr($number, 0, 2);
    
        // Format based on DDD
        if ( $eighthDigit >= 2 && $eighthDigit <= 5) {
            return $ddd . $num;
        }

        if ( $eighthDigit >= 6 && $eighthDigit <= 9) {
            return $ddd . "9" . $num;
        }
    
        return null;
    }
    public static function validateBrazilianWhatsapp(string $number) {
        $number = preg_replace('/\D/', '', $number);
        $numberLength = strlen($number);
    
        // Check if the number is valid
        if ($numberLength < 10) {
            return null;
        }
    
        // Remove the country code if it exists
        if (substr($number, 0, 2) === '55' && $numberLength > 11) {
            $number = substr($number, 2);
        }
    
        // Get the last 8 digits and the DDD
        $num = substr($number, -8);
        $ddd = substr($number, 0, 2);
    
        // Format based on DDD
        if (intval($ddd) <= 30) {
            return "55" . $ddd . "9" . $num;
        }
    
        return "55" . $ddd . $num;
    }
}