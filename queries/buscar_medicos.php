<?php
include '../conn/db.php';

// Verificar conexão
if (!$conn) {
    error_log("Erro na conexão com o banco");
    die("Erro na conexão com o banco de dados");
}

// Função para buscar médicos
if (isset($_GET['unidade_id'])) {
    $unidadeId = $_GET['unidade_id'];
    error_log("Unidade ID recebida: " . $unidadeId);

    try {
        $stmt = $conn->prepare("
            SELECT 
                rc.cod AS medico_cod,
                rc.nome AS medico_nome,
                rc.crm,
                re.nome AS especialidade_nome
            FROM 
                redecorpoclinico rc
            LEFT JOIN 
                rededadosgerais rg ON rc.credenciado = rg.cod
            LEFT JOIN 
                redeespecialidadecorpoclinico rec ON rc.cod = rec.medico
            LEFT JOIN 
                redeespecialidades re ON rec.especialidade = re.cod
            WHERE 
                rg.cod = :unidadeId
        ");

        $stmt->execute(['unidadeId' => $unidadeId]);

        $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        

        if ($medicos) {
            foreach ($medicos as $medico) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($medico['medico_cod']) . "</td>";
                echo "<td>" . htmlspecialchars($medico['medico_nome']) . "</td>";
                echo "<td>" . htmlspecialchars($medico['especialidade_nome'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($medico['crm'] ?? '-') . "</td>";
                echo "<td><i class='btn criar-agenda fa-solid fa-calendar-plus' data-nome='" . htmlspecialchars($medico['medico_nome']) . "'></i></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum médico encontrado para esta unidade.</td></tr>";
        }
    } catch (PDOException $e) {
        error_log("Erro na query: " . $e->getMessage());
        echo "<tr><td colspan='5'>Erro ao buscar médicos.</td></tr>";
    }
}


// Função para buscar especialidades para cadastrar na agenda (MODAL)
if (isset($_GET['medico_id'])) {
    $medicoId = $_GET['medico_id'];
    error_log("Médico ID recebido: " . $medicoId);

    try {
        $stmt = $conn->prepare("
            SELECT re.cod, re.nome
            FROM redeespecialidades re
            JOIN redeespecialidadecorpoclinico rec ON re.cod = rec.especialidade
            WHERE rec.medico = :medicoId AND rec.situacao = '1'
            ORDER BY re.nome
        ");

        $stmt->execute(['medicoId' => $medicoId]);
        $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($especialidades) {
            foreach ($especialidades as $especialidade) {
                echo "<option value='" . htmlspecialchars($especialidade['cod']) . "'>" .
                    htmlspecialchars($especialidade['nome']) . "</option>";
            }
        } else {
            echo "<option value=''>Sem especialidades cadastradas</option>";
        }
    } catch (PDOException $e) {
        error_log("Erro na query de especialidades: " . $e->getMessage());
        echo "<option value=''>Erro ao carregar especialidades</option>";
    }
}
