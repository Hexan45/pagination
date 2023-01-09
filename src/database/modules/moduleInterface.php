<?php
    declare(strict_types=1);

    namespace src\database\modules;

    interface moduleInterface {
        public function connect() : self;
        public function disconnect() : bool;
    }