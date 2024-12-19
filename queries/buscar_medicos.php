<?php
include '../conn/db.php';

// Log inicial
error_log("Iniciando busca de médicos");

// Verificar conexão
if (!$conn) {
    error_log("Erro na conexão com o banco");
    echo "<tr><td colspan='5'>Erro na conexão com o banco de dados</td></tr>";
    exit;
}

if (isset($_GET['unidade_id'])) {
    $unidadeId = $_GET['unidade_id'];
    error_log("Unidade ID recebida: " . $unidadeId);

    try {
        $stmt = $conn->prepare("
            SELECT rc.cod, rc.nome, rc.crm
            FROM redecorpoclinico rc
            JOIN rededadosgerais rg ON rc.credenciado = rg.cod
            WHERE rg.cod = :unidadeId
        ");
        
        $stmt->execute(['unidadeId' => $unidadeId]);
        error_log("Query executada com sucesso");
        
        $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Número de médicos encontrados: " . count($medicos));

        if ($medicos) {
            foreach ($medicos as $medico) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($medico['cod']) . "</td>";
                echo "<td>" . htmlspecialchars($medico['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($medico['crm']) . "</td>";            
                echo "<td><button class='btn criar-agenda'>Criar Agenda</button></td>";
                echo "</tr>";
            }
        } else {
            error_log("Nenhum médico encontrado para a unidade: " . $unidadeId);
            echo "<tr><td colspan='5'>Nenhum médico encontrado para esta unidade.</td></tr>";
        }
    } catch (PDOException $e) {
        error_log("Erro na query: " . $e->getMessage());
        echo "<tr><td colspan='5'>Erro ao buscar médicos. Por favor, tente novamente.</td></tr>";
    }
} else {
    error_log("Nenhum ID de unidade fornecido");
    echo "<tr><td colspan='5'>Unidade não especificada</td></tr>";
}
?>