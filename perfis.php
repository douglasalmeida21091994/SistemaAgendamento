<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ocomonm_sos";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação de conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para limpar e validar os dados
function limparDados($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];
    if (is_uploaded_file($file)) {
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Ignora a primeira linha (cabeçalho)
            fgetcsv($handle);

            // Prepara a consulta SQL
            $stmt = $conn->prepare("INSERT INTO perfis (cod, nome, situacao, cod_us) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $cod, $nome, $situacao, $cod_us);

            // Lê cada linha do arquivo CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Atribui os valores às variáveis
                $cod = limparDados($data[0]);
                $nome = limparDados($data[1]);
                $situacao = limparDados($data[2]);
                $cod_us = limparDados($data[3]);

                // Executa a inserção
                if (!$stmt->execute()) {
                    echo "Erro ao inserir dados: " . $stmt->error;
                }
            }
            fclose($handle);
            echo "Dados importados com sucesso!";
        } else {
            echo "Erro ao abrir o arquivo CSV.";
        }
    } else {
        echo "Nenhum arquivo enviado.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Importar CSV para Perfis</title>
</head>
<body>
    <h2>Importar Dados para a Tabela Perfis</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="csv_file">Escolha o arquivo CSV:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <button type="submit">Importar</button>
    </form>
</body>
</html>
