<div class="container">
    <div class="row mt-5">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="header" class="row">
                <div class="row">
                    <div class="col-md-1 align-self-center">
                        <a href="<?= base_url(); ?>"><i class="fa fa-long-arrow-left fa-2x" style="color:rgba(0, 0, 0, 0.25)" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-11 align-self-center">
                        <h1 class="convenio-header">Convenios</h1>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Pesquisar por nome...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Selecione o Status...</option>
                            <option value="Assinatura Pendente Registro TV Siafi">Assinatura Pendente Registro TV Siafi</option>
                            <option value="Aguardando Prestação de Contas">Aguardando Prestação de Contas</option>
                            <option value="Cancelado">Cancelado</option>
                            <option value="Convênio Anulado">Convênio Anulado</option>
                            <option value="Convênio Rescindido">Convênio Rescindido</option>
                            <option value="Em execução">Em execução</option>
                            <option value="Inadimplente">Inadimplente</option>
                            <option value="Prestação de Contas Aprovada">Prestação de Contas Aprovada</option>
                            <option value="Prestação de Contas Aprovada com Ressalvas">Prestação de Contas Aprovada com Ressalvas</option>
                            <option value="Prestação de Contas Comprovada em Análise">Prestação de Contas Comprovada em Análise</option>
                            <option value="Prestação de Contas Concluída">Prestação de Contas Concluída</option>
                            <option value="Prestação de Contas em Análise">Prestação de Contas em Análise</option>
                            <option value="Prestação de Contas em Complementação">Prestação de Contas em Complementação</option>
                            <option value="Prestação de Contas enviada para Análise">Prestação de Contas enviada para Análise</option>
                            <option value="Prestação de Contas Iniciada Por Antecipação">Prestação de Contas Iniciada Por Antecipação</option>
                            <option value="Prestação de Contas Rejeitada">Prestação de Contas Rejeitada</option>
                            <option value="Proposta/Plano de Trabalho Aprovado">Proposta/Plano de Trabalho Aprovado</option>
                            <option value="Proposta/Plano de Trabalho Complementado em Análise">Proposta/Plano de Trabalho Complementado em Análise</option>
                            <option value="Proposta/Plano de Trabalho Complementado Enviado para Análise">Proposta/Plano de Trabalho Complementado Enviado para Análise</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Selecione o Estado...</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input id="startDate" class="form-control" type="date" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<div id="list" class="row convenio-list" style="background-color:#FFF;border-radius:15px;">
    <div id="cards-container" class="w-80 mt-5 mb-3">
    </div>
</div>


<script>
    function obterCorPorStatus($status) {
        switch ($status) {
            case 'Prestação de Contas Concluída':
            case 'Prestação de Contas Aprovada':
            case 'Proposta/Plano de Trabalho Aprovado':
            case 'Em execução':
                return '#14B240'; // verde para status positivos (bright green)
            case 'Prestação de Contas em Análise':
            case 'Prestação de Contas em Complementação':
            case 'Aguardando Prestação de Contas':
            case 'Prestação de Contas Aprovada com Ressalvas':
            case 'Prestação de Contas Iniciada Por Antecipação':
            case 'Proposta/Plano de Trabalho Complementado Enviado para Análise':
            case 'Prestação de Contas enviada para Análise':
            case 'Proposta/Plano de Trabalho Complementado em Análise':
            case 'Prestação de Contas Comprovada em Análise':
            case 'Assinatura Pendente Registro TV Siafi':
                return '#FF8743';
            case 'Cancelado':
            case 'Prestação de Contas Rejeitada':
            case 'Convênio Anulado':
            case 'Inadimplente':
            case 'Convênio Rescindido':
                return '#FF4869'; // vermelho para status negativos (bright red)
            default:
                return '#000000'; // preto para outros casos (black)
        }
    }

    function carregarCards() {
        const cardsContainer = document.getElementById('cards-container');

        // Substitua a URL da API pela URL da sua API
        const base_url = $('#base_url').val();
        const apiUrl = base_url + 'convenios/getConvenios';


        fetch(apiUrl) // Faz uma requisição GET para a API
            .then(response => {
                // Verifica se a requisição foi bem sucedida
                if (!response.ok) {
                    throw new Error('Erro ao carregar os dados');
                }
                // Converte a resposta para JSON
                return response.json();
            })
            .then(data => {
                // Itera sobre os dados e cria os cards
                data.forEach(item => {
                    // Cria um elemento <div> para o card
                    const card = document.createElement('div');
                    card.classList.add('card', 'w-100', 'mb-3');

                    // Adiciona o conteúdo ao card
                    card.innerHTML = `
                    <div class="card-body card-convenios">
                        <h5 class="card-title mb-3 convenio-card-state">${item.MUNIC_PROPONENTE} - ${item.UF_PROPONENTE}</h5>
                        <h6 class="card-subtitle mb-2 text-muted convenio-card-title">
                            <a href="${base_url}documentos/modelo/${item.NR_CONVENIO}">${item.OBJETO_PROPOSTA}</a></h6>
                        
                        <div class="row">
                            <div class="col-md-6">                                
                                <p class="card-text convenio-card-status">
                                    Status: <font style="color:${obterCorPorStatus(item.SIT_CONVENIO)}">${item.SIT_CONVENIO}</font>
                                </p>
                            </div>
                            <div class="col-md-6">                                
                                <p class="convenio-card-dates" style="text-align: right">
                                    INÍCIO DA VIGÊNCIA: ${item['inicio']}  FIM: ${item['fim']}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                    // Adiciona o card ao container
                    cardsContainer.appendChild(card);
                });
            })
            .catch(error => {
                console.error('Erro ao carregar os dados:', error);
            });
    }

    // Chama a função para carregar os cards quando a página carrega
    document.addEventListener('DOMContentLoaded', carregarCards);
</script>