<?php
    declare(strict_types=1);

    require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

    use src\pagination\Pagination;
    use src\database\queryExecutor;
    use src\database\modules\mysqlConnector;

    $database = new queryExecutor(new mysqlConnector('localhost', 'pagination', 'root', ''));

    $pagination = (new Pagination($database, 'alphabet'))
        ->setItemsPerPage(10)
        ->initialize();

    echo '<pre>';
        print_r($pagination->getSelectedRows());
    echo '</pre>';
    for($page = 1; $page <= $pagination->getAllPagesCount(); $page++) {
        echo "<a href='?page={$page}'>{$page}</a>";
    }