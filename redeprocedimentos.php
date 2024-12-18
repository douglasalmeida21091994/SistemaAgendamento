<?php
// Configurações do banco de dados
$host = "localhost";
$dbname = "ocomonm_sos";
$username = "root"; 
$password = ""; 

// Conexão com o banco
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// Verificar se um arquivo foi enviado
if (isset($_POST["submit"])) {
    if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES["file"]["tmp_name"];

        // Ler o arquivo CSV
        $handle = fopen($fileTmpName, "r");
        if ($handle) {
            // Preparar a query de inserção
            $stmt = $pdo->prepare("INSERT INTO redeprocedimentos (
                cod, nome, codinterno, sexo, idadeinicial, idadefinal, 
                complexidade, rol, classificacao, situacao, codsolus, usuario, recomendacao, datahora
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )");

            // Processar cada linha do CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Verificar se a linha tem a quantidade correta de colunas
                if (count($data) == 14) {
                    // Substituir valores vazios por NULL
                    $data = array_map(function ($value) {
                        return $value === "" ? NULL : $value;
                    }, $data);

                    // Executar a inserção
                    try {
                        $stmt->execute($data);
                    } catch (PDOException $e) {
                        echo "Erro ao inserir linha: " . $e->getMessage();
                    }
                }
            }
            fclose($handle);
            echo "Arquivo CSV importado com sucesso!";
        } else {
            echo "Erro ao ler o arquivo.";
        }
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Upload de CSV</title>
</head>
<body>
    <h1>Importar Dados de CSV para o Banco</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="file">Selecione o arquivo CSV:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <br><br>
        <input type="submit" name="submit" value="Importar CSV">
    </form>
</body>
</html>