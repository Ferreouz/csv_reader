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
    public $nome, $sobrenome, $email, $telefone, $endereco, $cidade, $cep, $dataNascimento;
    public function __construct(
        private array $csvData,
        private readonly int $lineNumber,
    ) {
        $diff = array_diff_key(array_flip(CSVLine::$requiredFields), $this->csvData);
        if (count($diff) != 0) {
            $message = "Erro na linha " . $this->lineNumber . ", campos faltando: ";
            foreach ($diff as $key => $value) {
                $message .= $key . ",";
            }
            $message = substr($message, 0, -1);
            throw new \Exception($message);
        }
        $this->nome = $this->csvData["Nome"];
        $this->sobrenome = $this->csvData["Sobrenome"];
        $this->email = $this->csvData["Email"];
        $this->telefone = $this->csvData["Telefone"];
        $this->endereco = $this->csvData["Endereço"];
        $this->cidade = $this->csvData["Cidade"];
        $this->cep = $this->csvData["CEP"];
        $this->dataNascimento = $this->csvData["Data de Nascimento"];

        $this->validate();
    }
    private function validate(): void
    {
        $phone = CSVLine::validateBrazilianPhone($this->telefone);
        if ($phone === null) {
            throw new \Exception("Erro na linha " . $this->lineNumber . ", telefone '" . htmlspecialchars($this->csvData["Telefone"], ENT_QUOTES, 'UTF-8') . "' é inválido.");
        }
        $this->telefone = $phone;
        if(isset($this->dataNascimento) && preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $this->dataNascimento)){
            $dateTime = \DateTime::createFromFormat('d/m/Y', $this->dataNascimento);

            if ($dateTime) {
                $this->dataNascimento = $dateTime->format('Y-m-d');
            } 
        }else {
            $this->dataNascimento = "";
        }

    }


    public static function validateBrazilianPhone(string $number): string|null
    {
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
        if ($eighthDigit >= 2 && $eighthDigit <= 5) {
            return $ddd . $num;
        }

        if ($eighthDigit >= 6 && $eighthDigit <= 9) {
            return $ddd . "9" . $num;
        }

        return null;
    }
    public static function validateBrazilianWhatsapp(string $number)
    {
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