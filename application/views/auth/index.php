

<div class="container mt-4">
    <h1>Configurações</h1>
    <?php if ($message != NULL): ?>
    <div class="alert alert-info" role="alert">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

 
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">Usuários e Grupos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="dataUpload-tab" data-bs-toggle="tab" data-bs-target="#dataUpload" type="button" role="tab" aria-controls="dataUpload" aria-selected="false">Upload de Dados</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="transfereGov-tab" data-bs-toggle="tab" data-bs-target="#transfereGov" type="button" role="tab" aria-controls="transfereGov" aria-selected="false">TransfereGOV</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sei-tab" data-bs-toggle="tab" data-bs-target="#sei" type="button" role="tab" aria-controls="sei" aria-selected="false">Integração SEI</button>
        </li>

          <li class="nav-item" role="presentation">
            <button class="nav-link" id="alerts-tab" data-bs-toggle="tab" data-bs-target="#alerts" type="button" role="tab" aria-controls="alerts" aria-selected="false">Alertas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Configurações Adicionais</button>
        </li>


    </ul>


    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="card mt-3">
                <div class="card-header">
                    Administrar Usuários e Grupos
                </div>
                <div class="card-body">
                    <p><?php echo anchor('auth/create_user', lang('index_create_user_link')); ?> | <?php echo anchor('auth/create_group', lang('index_create_group_link')); ?></p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo lang('index_fname_th');?></th>
                                <th><?php echo lang('index_lname_th');?></th>
                                <th><?php echo lang('index_email_th');?></th>
                                <th><?php echo lang('index_groups_th');?></th>
                                <th><?php echo lang('index_status_th');?></th>
                                <th><?php echo lang('index_action_th');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?></td>
                                <td>
                                    <?php foreach ($user->groups as $group): ?>
                                    <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?><br />
                                    <?php endforeach; ?>
                                </td>
                                <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
                                <td><?php echo anchor("auth/edit_user/".$user->id, 'Editar '); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <div class="tab-pane fade" id="dataUpload" role="tabpanel" aria-labelledby="dataUpload-tab">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Upload de Dados</h4>
            </div>
            <div class="card-body">
                <p>Para realizar o upload de dados, clique no botão abaixo:</p>
                <span>Modelo de referência para upload de dados:</span> <a href="<?php echo base_url('assets/files/Modelo_Upload_Dados.xlsx'); ?>">Modelo_Upload_Dados.xlsx</a><br><br>
                <div class="mb-3">
                    <label for="dataUploadAction" class="form-label">Ação para o banco de dados:</label>
                    <select class="form-select" id="dataUploadAction">
                        <option selected>Escolha uma ação...</option>
                        <option value="overwrite">Sobreescrever o banco existente</option>
                        <option value="delete">Apagar o banco existente</option>
                        <option value="edit">Editar o banco existente</option>
                    </select>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    Upload de Dados
                </button>
            </div>
        </div>
    </div>


        <div class="tab-pane fade" id="transfereGov" role="tabpanel" aria-labelledby="transfereGov-tab">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Configurações TransfereGOV</h4>
            </div>
            <div class="card-body">
                <p>Conta principal atual: <strong><?php $transferegov_user = 'admin@mapa.gov.br'; echo $transferegov_user; ?></strong></p>
                <p>Para configurar os dados de acesso da conta principal do TransfereGOV, clique no botão abaixo:</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#configModal">
                    Configurar Acesso
                </button>
                <hr>
                <h5>Gerenciamento de Convênios</h5>
                <button class="btn btn-info" onclick="fetchConvenios()">Atualizar Lista de Convênios</button>
                <div id="conveniosList">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <hr>
                <h5>Adicionar ou Atualizar Convênio</h5>
                <form id="convenioForm" onsubmit="return addOrUpdateConvenio();">
                    <div class="mb-3">
                        <label for="convenioId" class="form-label">ID do Convênio (deixe em branco para adicionar)</label>
                        <input type="text" class="form-control" id="convenioId" placeholder="ID Convênio">
                    </div>
                    <div class="mb-3">
                        <label for="convenioName" class="form-label">Nome do Convênio</label>
                        <input type="text" class="form-control" id="convenioName" required>
                    </div>
                    <button type="submit" class="btn btn-success">Salvar Convênio</button>
                </form>
            </div>
        </div>
    </div>





        <div class="tab-pane fade" id="sei" role="tabpanel" aria-labelledby="sei-tab">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Integrar SEI</h4>
                </div>
                <div the="card-body" style="padding: 1%">
                    <p>Para configurar os dados de acesso da conta principal do SEI, clique no botão abaixo:</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#seiModal">
                        Integrar com SEI
                    </button>
                </div>
            </div>
        </div>

             <!-- Alerts  Tab -->
       <div class="tab-pane fade" id="alerts" role="tabpanel" aria-labelledby="alerts-tab">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Gerenciamento de Alertas para TransfereGOV e SEI</h4>
                </div>
                <div class="card-body">
                    <p>Configura alertas específicos para convenios do TransfereGOV e SEI, monitorando eventos críticos e atividades operacionais.</p>
                    <form>
                        <!-- Alert Type Selection -->
                        <div class="mb-3">
                            <label for="systemSelect" class="form-label">Sistema</label>
                            <select class="form-select" id="systemSelect">
                                <option selected>Escolha o sistema...</option>
                                <option value="transfereGov">TransfereGOV</option>
                                <option value="sei">SEI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alertType" class="form-label">Tipo de Alerta</label>
                            <select class="form-select" id="alertType">
                                <option selected>Escolha um tipo...</option>
                                <option value="1">Erro Crítico</option>
                                <option value="2">Falha de Login</option>
                                <option value="3">Uso de Recursos</option>
                                <option value="4">Expiração de Convênio</option>
                                <option value="5">Alteração de Condições</option>
                            </select>
                        </div>
                        <!-- Recipient Email Input -->
                        <div class="mb-3">
                            <label for="alertEmail" class="form-label">Enviar alertas para:</label>
                            <input type="email" class="form-control" id="alertEmail" placeholder="nome@exemplo.com">
                        </div>
                        <!-- Submission Button -->
                        <button type="submit" class="btn btn-primary">Salvar Alerta</button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Additional Settings Tab -->
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Configurações Adicionais do Sistema</h4>
                </div>
                <div class="card-body">
                    <p>Ajuste configurações avançadas e parâmetros do sistema aqui.</p>
                    <form>
                        <div class="mb-3">
                            <label for="systemTimeout" class="form-label">Timeout do Sistema (em minutos)</label>
                            <input type="number" class="form-control" id="systemTimeout" value="30">
                        </div>
                        <div class="mb-3">
                            <label for="languageSelect" class="form-label">Idioma do Sistema</label>
                            <select class="form-select" id="languageSelect">
                                <option selected>Português</option>
                                <option value="en">English</option>
                                <option value="es">Español</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Aplicar Configurações</button>
                    </form>
                </div>
            </div>
        </div>



    </div>
</div>


 <div class="modal fade" id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configModalLabel">Configurações de Acesso TransfereGOV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="username" class="form-label">Nome de usuário</label>
                            <input type="text" class="form-control" id="username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button class="btn btn-primary">Salvar Configurações</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="seiModal" tabindex="-1" aria-labelledby="seiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seiModalLabel">Integração com SEI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="seiUsername" class="form-label">Usuário SEI</label>
                        <input type="text" class="form-control" id="seiUsername">
                    </div>
                    <div class="mb-3">
                        <label for="seiPassword" class="form-label">Senha SEI</label>
                        <input type="password" class="form-control" id="seiPassword">
                    </div>
                    <div class="mb-3">
                        <label for="seiServer" class="form-label">Orgão</label>
                        <select class="form-select" id="seiServer">
                            <option selected>Escolha o servidor...</option>
                            <option value="server1">Servidor 1</option>
                            <option value="server2">Servidor 2</option>
                            <option value="server3">Servidor 3</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Integrar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload de Dados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <p>Modelo de referência para upload de dados:</p>
                    <div class="mb-3">
                        <a href="<?php echo base_url('assets/files/Modelo_Upload_Dados.xlsx'); ?>">Modelo_Upload_Dados.xlsx</a>
                    </div>
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label">Selecionar arquivo:</label>
                        <input type="file" class="form-control" id="fileUpload">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Enviar Arquivo</button>
            </div>
        </div>
    </div>
</div>
   

  <div class="tab-content" id="myTabContent">
        <!-- Example content omitted for brevity -->

    </div>
</div>


<script>
function fetchConvenios() {
    fetch('/api/convenios')
    .then(response => response.json())
    .then(data => {
        const convenios = data.map(convenio => 
            `<li>${convenio.name} (ID: ${convenio.id})</li>`
        ).join('');
        document.getElementById('conveniosList').innerHTML = `<ul>${convenios}</ul>`;
    })
    .catch(error => console.error('Error fetching convenios:', error));
}

function addOrUpdateConvenio() {
    const id = document.getElementById('convenioId').value;
    const name = document.getElementById('convenioName').value;

    const url = id ? `/api/convenios/update/${id}` : '/api/convenios/add';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name: name })
    })
    .then(response => {
        if (response.ok) {
            fetchConvenios(); // Refresh the list after update
            return response.json();
        }
        throw new Error('Network response was not ok.');
    })
    .then(data => console.log('Success:', data))
    .catch(error => console.error('Error:', error));

    return false; // Prevent form submission
}
</script>
