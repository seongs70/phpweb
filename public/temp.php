<?php

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../classes/DatabaseTable.php';

$jokesTable = new DatabaseTable($pdo, 'joke', 'id');

print_r($jokesTable->findAll());


// $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
// $authorsTable = new DatabaseTable($pdo, 'author', 'id');
// //ID가 123인 유머글 검색
// $jokesTable->findById(123);
