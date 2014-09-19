<?php
include DAO_PATH . '/PoemDao.php';
$poemDao = new PoemDao();
$poemDao->setDebug(1);

var_dump($poemDao->getList(array()));var_dump($poemDao);
?>