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

    <!-- Modal -->
    <div id="agenda-modal" class="modal">
        <div class="modal-content">
            <header>
                <span class="close">&times;</span>
                <h1>Cadastro de Agenda</h1>
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
                            <option value="">Selecione...</option>
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