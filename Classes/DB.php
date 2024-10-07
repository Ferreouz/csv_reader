<?php

namespace Classes;
use Classes\CSVLine;
class DB
{

    private $pdo;
    public function __construct(private $host, private $db, private $user, private $pass, private $charset = 'utf8mb4')
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function search($search = "") {
        if ($search === "") {
            // Prepare the SQL statement to get a specific line by ID
            $stmt = $this->pdo->prepare("SELECT * FROM campaign_lines order by created_at DESC");
        } else {
            // Prepare the SQL statement to get all lines
            $stmt = $this->pdo->prepare("SELECT * FROM campaign_lines l
                                                    INNER JOIN campaigns c ON c.nome = l.campaign
                                                    WHERE l.telefone LIKE :search1
                                                    OR l.campaign LIKE :search2
            order by l.created_at DESC");
            $search = "%" . $search . "%";
            $stmt->bindParam(':search1', $search);
            $stmt->bindParam(':search2', $search);
        }
    
        $stmt->execute();
        $values = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $values;
    }
    public function insert(CSVLine $line, string $campaignName)
    {
        if(!($error = $this->insertCampaign($campaignName))){
            return $error;
        }
        $stmt = $this->pdo->prepare("INSERT INTO campaign_lines (nome, sobrenome, email, endereco, telefone, cidade, cep, data_nascimento, campaign) VALUES (:nome, :sobrenome, :email, :endereco, :telefone, :cidade, :cep, :data_nascimento, :campaign)");

        $stmt->bindParam(':nome', $line->nome);
        $stmt->bindParam(':sobrenome', $line->sobrenome);
        $stmt->bindParam(':email', $line->email);
        $stmt->bindParam(':endereco', $line->endereco);
        $stmt->bindParam(':telefone', $line->telefone);
        $stmt->bindParam(':cidade', $line->cidade);
        $stmt->bindParam(':cep', $line->cep);
        $stmt->bindParam(':data_nascimento', $line->dataNascimento);
        $stmt->bindParam(':campaign', $campaignName);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->errorInfo()[2];
        }
    }


    public function insertBulk(array $lines, string $campaignName)
    {
        if(!($error = $this->insertCampaign($campaignName))){
            return $error;
        }
        $values = [];
        $placeholders = [];

        foreach ($lines as $line) {
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $values = array_merge($values, [
                $line->nome,
                $line->sobrenome,
                $line->email,
                $line->endereco,
                $line->telefone,
                $line->cidade,
                $line->cep,
                $line->dataNascimento,
                $campaignName,
            ]);
        }
        $sql = "INSERT INTO campaign_lines (nome, sobrenome, email, endereco, telefone, cidade, cep, data_nascimento, campaign) VALUES " . implode(", ", $placeholders);

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($values)) {
            return true;
        } else {
            return $stmt->errorInfo()[2];
        }
    }

    private function insertCampaign(string $campaignName)
    {
        $stmt = $this->pdo->prepare("INSERT INTO campaigns (nome) VALUES (:nome)");

        $stmt->bindParam(':nome', $campaignName);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->errorInfo()[2];
        }
    }

}
