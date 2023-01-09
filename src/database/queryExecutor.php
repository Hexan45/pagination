<?php
    declare(strict_types=1);

    namespace src\database;

    use src\database\modules\moduleInterface;

    require_once(dirname(__DIR__) . '/exceptions/databaseExceptions.php');

    class queryExecutor {
        private moduleInterface $databaseConnection;
        private \PDOStatement|bool $databaseQuery;

        const FETCH_ASSOC = \PDO::FETCH_ASSOC;
        const FETCH_NUM = \PDO::FETCH_NUM;
        const FETCH_OBJ = \PDO::FETCH_OBJ;

        const DATA_TYPES = [
            'boolean' => \PDO::PARAM_BOOL,
            'integer' => \PDO::PARAM_INT,
            'string' => \PDO::PARAM_STR,
            'NULL' => \PDO::PARAM_NULL
        ];

        public function __construct(moduleInterface $dbModule) {
            $this->databaseConnection = $dbModule->connect();
        }

        public function __destruct() {
            $this->databaseConnection->disconnect();
            unset($this->databaseConnection);
        }

        public function query(string $query, ?array $bindValues = NULL) : self {
            try {

                $this->databaseQuery = $this->databaseConnection->pdoInstance->prepare($query);
                
                if(!$this->databaseQuery) return false;
                
                if(!is_null($bindValues)) {
                    foreach($bindValues as $bindName => $bindValue) {
                        $bindValueType = gettype($bindValue);
    
                        if(!$this->databaseQuery->bindValue($bindName, $bindValue, self::DATA_TYPES[$bindValueType])) {
                            throw new \src\exceptions\errorWithBindingStatementException();
                            return false;
                        }
                    }
                }

                if(!$this->databaseQuery->execute()) return false;

                return $this;
            } catch(\PDOException $exc) {
                die($exc->getMessage() . ' | Code: ' . $exc->getCode() . ' | Line: ' . $exc->getLine());
            }
        }

        public function getData(int $fetchMode = self::FETCH_ASSOC) : array|int|object {
            return $this->databaseQuery->fetchAll($fetchMode);
        }

        public function getOneRow() : mixed {
            return $this->databaseQuery->fetchColumn();
        }
    }