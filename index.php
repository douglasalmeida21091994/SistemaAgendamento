<?php
include 'queries/buscarUnidades.php';  // Consulta as unidades

$section = isset($_GET['section']) ? $_GET['section'] : 'criarAgenda';

switch ($section) {
    case 'criarAgenda':
        $page = 'sections/criarAgenda.php';
        break;
    case 'agendarAtendimento':
        $page = 'sections/agendarAtendimento.php';
        break;
    case 'faltasConsecutivas':
        $page = 'sections/faltasConsecutivas.php';
        break;
    default:
        $page = 'sections/criarAgenda.php';
        break;
}

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
    <link rel="stylesheet" href="css/style_criarAgenda.css">
    <link rel="stylesheet" href="css/style_agendarAtendimento.css">
    <!--  font-awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ1QKZj6HJ1utD9ytpMbbL1t4ksmOlFqd5F2l1Ejhs8jGh5zBbYaS0luQk8f" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery (necessário para o DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.min.js"></script>

    <!-- ADD SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <header class="title">
        <img src="img/nova-logo-branca 1.png" alt="Logo Smile Saúde" class="title-image">
        <h2>Sistema de Agendamento</h2>
    </header>
    <div class="container-primario">

        <div class="buttons">
            <a href="?section=criarAgenda" class="btn-option">Criar Agenda</a>
            <a href="?section=agendarAtendimento" class="btn-option">Agendar Atendimento</a>
            <a href="?section=faltasConsecutivas" class="btn-option">3 ou Mais Faltas Seguidas</a>
        </div>

        <div class="container">
            <!-- Fluxo das sections -->
            <?php include $page; ?>
        </div>
    </div>


    <script src="js/criarAgenda.js"></script>
    <script src="js/botoes.js"></script>
    <script src="js/agendarAtendimento.js"></script>


</body>

</html>