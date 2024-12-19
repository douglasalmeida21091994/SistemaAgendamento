<?php
include 'queries/buscarUnidades.php';  // Consulta as unidades

// Consulta para buscar os nomes das unidades
// $sql = "SELECT * FROM rededadosgerais";
// $stmt = $conn->prepare($sql);
// $stmt->execute();
// $unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamento</title>
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ1QKZj6HJ1utD9ytpMbbL1t4ksmOlFqd5F2l1Ejhs8jGh5zBbYaS0luQk8f" crossorigin="anonymous">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- jQuery (necessário para o DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="container-primario">
        <header class="title">
            <img src="img/nova-logo-branca 1.png" alt="Logo Smile Saúde" class="title-image">
            <h2>Sistema de Agendamento</h2>
        </header>

        <div class="container">
            <!-- Fluxo para Criar Agenda -->
            <section id="criar-agenda">
                <h2>Criar Agenda</h2>
                <div>
                    <label for="unidade">Selecione a Unidade:</label>
                    <select id="unidade">
                        <option value="selecione">Selecione...</option>
                        <?php foreach ($unidades as $unidade): ?>
                            <option value="<?= htmlspecialchars($unidade['cod']) ?>">
                                <?= htmlspecialchars($unidade['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="corpo-clinico" class="hidden">                    
                    <table id="medicosTabela" class="display">
                        <thead>
                            <tr>
                                <th>Cod</th>
                                <th>Nome</th>
                                <th>Nº Conselho</th>                               
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="medicos-tabela">
                            <!-- Médicos serão carregados aqui via AJAX -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script src="js/script.js"></script>
    
</body>

</html>
