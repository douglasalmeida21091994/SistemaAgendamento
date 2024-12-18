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

    // Verifica se o arquivo foi carregado corretamente
    if (is_uploaded_file($file)) {
        // Abre o arquivo CSV
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Ignora a primeira linha (cabeçalho)
            fgetcsv($handle);

            // Prepara a instrução SQL
            $stmt = $conn->prepare("INSERT INTO prontfila (credenciado, beneficiario, tipoPreferencia, stTriagem, data, hora, preferencia, ordem, situacao,
                tipoagendamento, codagendamento, medicoatender, totalchamada, chamandomedicotriagem, chamandomedico,
                iniciotriagem, inicioConsulta, fimtriagem, fimConsulta, horamarcada, retornoMedico, classManchester,
                podeChamar, guiaSolus, horaChegada, estanolocal, id_tmp_conclinica
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            

            // Vincula os parâmetros
            $stmt->bind_param(
                "iiisissiiiiiissssssssii",
                $credenciado, $beneficiario, $tipoPreferencia, $stTriagem, $data, $hora, $preferencia, $ordem, $situacao,
                $tipoagendamento, $codagendamento, $medicoatender, $totalchamada, $chamandomedicotriagem, $chamandomedico,
                $iniciotriagem, $inicioConsulta, $fimtriagem, $fimConsulta, $horamarcada, $retornoMedico, $classManchester,
                $podeChamar, $guiaSolus, $horaChegada, $estanolocal, $id_tmp_conclinica
            );

            // Lê cada linha do arquivo CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Atribui os valores às variáveis
                $cod = limparDados($data[0]);
                $credenciado = limparDados($data[1]);
                $beneficiario = limparDados($data[2]);
                $tipoPreferencia = limparDados($data[3]);
                $stTriagem = limparDados($data[4]);
                $data = limparDados($data[5]);
                $hora = limparDados($data[6]);
                $preferencia = limparDados($data[7]);
                $ordem = limparDados($data[8]);
                $situacao = limparDados($data[9]);
                $tipoagendamento = limparDados($data[10]);
                $codagendamento = limparDados($data[11]);
                $medicoatender = limparDados($data[12]);
                $totalchamada = limparDados($data[13]);
                $chamandomedicotriagem = limparDados($data[14]);
                $chamandomedico = limparDados($data[15]);
                $iniciotriagem = limparDados($data[16]);
                $inicioConsulta = limparDados($data[17]);
                $fimtriagem = limparDados($data[18]);
                $fimConsulta = limparDados($data[19]);
                $horamarcada = limparDados($data[20]);
                $retornoMedico = limparDados($data[21]);
                $classManchester = limparDados($data[22]);
                $podeChamar = limparDados($data[23]);
                $guiaSolus = limparDados($data[24]);
                $horaChegada = limparDados($data[25]);
                $estanolocal = limparDados($data[26]);
                $id_tmp_conclinica = limparDados($data[27]);
            
                // Executa a inserção
                $stmt->execute();
            }           
            

            // Fecha o arquivo e a instrução preparada
            fclose($handle);
            $stmt->close();

            echo "Dados importados com sucesso!";
        } else {
            echo "Erro ao abrir o arquivo CSV.";
        }
    } else {
        echo "Erro no upload do arquivo.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar CSV para prontfila</title>
</head>
<body>
    <h2>Importar Dados para a Tabela prontfila</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="csv_file">Selecione o arquivo CSV:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <button type="submit">Importar</button>
    </form>
</body>
</html>
