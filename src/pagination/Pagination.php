<?php
    declare(strict_types=1);

    namespace src\pagination;

    use src\database\queryExecutor;

    class Pagination {
        //All records selected from database
        private int $selectedItemsCount;
        //Items per page setting
        private int $itemsPerPage = 5;
        //Actually page number taked from endpoint
        private int $selectedPage;
        //Define first limit row for
        private int $firstLimitRow;
        //All pages count
        private int $allPagesCount;

        //Query executor for database
        private queryExecutor $database;
        //Table name for pagination
        private string $tableName;
        //Actually selected rows for page
        private array|object $selectedRows;

        public function __construct(queryExecutor $database, string $tableName) {
            $this->database = $database;
            $this->tableName = $tableName;
            $this->setSelectedItemsCount();
        }

        private function setSelectedItemsCount() : void {
            $result = $this->database
                ->query("SELECT COUNT(*) FROM {$this->tableName}")
                ->getOneRow();

            $this->selectedItemsCount = (int)$result ?: 0;
        }

        private function getPageNumber() : void {
            if(isset($_GET['page'])) {
                $this->selectedPage = (int)$_GET['page'];
            } else {
                $this->selectedPage = 1;
            }
        }

        //Getter for sleectedItemsCount propety
        public function getSelectItemsCount() : int {
            //Check if property has initialized
            if(isset($this->selectedItemsCount)) {
                return $this->selectedItemsCount;
            }
        }

        //Getter for getItemsPerPage property
        public function getItemsPerPage() : int {
            return $this->itemsPerPage;
        }

        //Getter for allPagesCount
        public function getAllPagesCount() : int {
            return $this->allPagesCount;
        }

        //Getter for selectedPage
        public function getSelectedPage() : int {
            return $this->selectedPage;
        }

        //Getter for selectedRows
        public function getSelectedRows() : array|object {
            return $this->selectedRows;
        }

        //Setter of ritemsPerPage
        public function setItemsPerPage(int $itemsCount) : self {
            //Check if parameter is greater than zero
            if($itemsCount > 0) {
                $this->itemsPerPage = $itemsCount;
            }
            //Return instance of this class
            return $this;
        }

        public function initialize() : self {
            $this->getPageNumber();
            $this->firstLimitRow = ($this->selectedPage - 1) * $this->itemsPerPage;
            $this->allPagesCount = (int)ceil($this->selectedItemsCount / $this->itemsPerPage);

            $this->selectedRows = $this->database
                ->query("SELECT * FROM {$this->tableName} LIMIT {$this->firstLimitRow}, {$this->itemsPerPage}")
                ->getData(queryExecutor::FETCH_ASSOC);

            return $this;
        }
    }