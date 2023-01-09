<?php
    declare(strict_types=1);

    namespace src\database\modules;

    use src\exceptions\databaseExceptions;
    use src\database\modules\moduleInterface;

    class mysqlConnector implements moduleInterface {
        private string $dsn;
        public \PDO $pdoInstance;

        public function __construct(
            private string $host,
            private string $database,
            private string $username,
            private string $password
        ){
            $this->dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";
            return $this;
        }

        public function connect() : self {
            try {
                $this->pdoInstance = new \PDO($this->dsn, $this->username, $this->password);
                $this->pdoInstance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
            } catch(\PDOException $exc) {
                die($exc->getMessage() . ' | Code: ' . $exc->getCode() . ' | Line: ' . $exc->getLine());
            }

            return $this;
        }

        public function disconnect() : bool {
            if(isset($pdoInstance)) {
                unset($pdoInstance);
                return true;
            }

            return false;
        }
    }