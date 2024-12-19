<?php

include 'conn/db.php';

// Consulta para buscar os nomes das unidades
$sql = "SELECT * FROM rededadosgerais";
$stmt = $conn->prepare($sql);
$stmt->execute();

// armazenas os dados
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

///////////////

