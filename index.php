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
            <div class="btn-option">Criar Agenda</div>
            <div class="btn-option">Agendar Atendimento</div>
            <div class="btn-option">3 ou Mais Faltas Seguidas</div>
        </div>

        <div class="container">
            <!-- Fluxo para Criar Agenda -->
            <section id="criar-agenda">
                <h2>Criar Agenda</h2>
                <div>
                    <label for="unidade">Selecione a Unidade:</label>
                    <select id="unidade">
                        <!-- <option value="selecione">Selecione...</option> -->
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
                                <th>Especialidade</th>
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

    <!-- Modal -->
    <div id="agenda-modal" class="modal">
        <div class="modal-content">
            <header>
                <span class="close">&times;</span>
                <h1></h1>
            </header>
            <form>
                <div class="form-group">
                    <label for="vigencia">Vigência:</label>
                    <div class="form-div-group">
                        <input type="date" id="vigencia-inicio">
                        <span>até</span>
                        <input type="date" id="vigencia-fim">
                    </div>
                </div>

                <div class="form-group">
                    <label for="dia-agendamento">Dia de Agendamento:</label>
                    <div id="dias">
                        <button type="button">SEG</button>
                        <button type="button">TER</button>
                        <button type="button">QUA</button>
                        <button type="button">QUI</button>
                        <button type="button">SEX</button>
                        <button type="button">SAB</button>
                    </div>
                </div>

                <div class="form-envelop">
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select id="tipo">
                            <option value="1">Ordem de chegada</option>
                            <option value="2">Hora marcada</option>
                            <option value="3">Hora prevista</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contratacao">Tipo de Contratação:</label>
                        <select id="contratacao">
                            <option value="1">Plantão</option>
                            <option value="2">Produção</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="beneficiarios">Quantidade de Beneficiários:</label>
                        <input type="text" id="beneficiarios">
                    </div>

                    <div class="form-group">
                        <label for="especialidade">Especialidade:</label>
                        <select id="especialidade">
                            <option>Escolha</option>
                        </select>
                    </div>

                </div>

                <!-- Procedimentos -->
                <div id="procedimentos-container">
                    <div class="form-procedimento-select">
                        <label for="procedimento">Procedimento:</label>
                        <select class="form-select procedimento">
                            <option value="">selecione...</option>
                            <option value="1">EM CONSULTORIO (NO HORARIO NORMAL OU PREESTABELECIDO)</option>
                            <option value="2">EM PRONTO SOCORRO</option>
                            <option value="4163">CONSULTA EM PSICOLOGIA</option>
                            <option value="4169">SESSAO DE PSICOTERAPIA INDIVIDUAL</option>
                            <option value="4180">SESSAO DE PSICOPEDAGOGIA INDIVIDUAL</option>
                            <option value="4187">SESSAO DE PSICOMOTRICIDADE INDIVIDUAL</option>
                            <option value="4168">CONSULTA EM TERAPIA OCUPACIONAL</option>
                            <option value="4195">SESSAO INDIVIDUAL AMBULATORIAL, EM TERAPIA OCUPACIONAL</option>
                            <option value="4171">SESSAO INDIVIDUAL AMBULATORIAL DE FONOAUDIOLOGIA</option>
                        </select>
                    </div>
                </div>

                <!-- Botões para adicionar/remover selects -->
                <div id="div-button">
                    <button type="button" id="addProcedimento">Adicionar Procedimento</button>
                    <button type="button" id="removeProcedimento">Remover Procedimento</button>
                    <button type="submit" id="cadastrarAgenda">Cadastrar Agenda</button>
                </div>
            </form>
        </div>
    </div>


    <script src="js/script.js"></script>

</body>

</html>