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
                        <h1 class="convenio-header">Documentos</h1>
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
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Pesquisar por número do convênio...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" >
                            <option selected>Selecione o Tipo...</option>
                            <option value="Artigo">Artigo</option>
                            <option value="Dinâmico">Dinâmico</option>
                            <option value="Jornalistico">Jornalistico</option>
                            <option value="Notícias">Notícias</option>                        
                            <option value="Portifólio de Imagens">Portifólio de Imagens</option>
                            <option value="Outros">Outros</option>

                        </select>
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
    function carregarCards() {
        const cardsContainer = document.getElementById('cards-container');

        // Substitua a URL da API pela URL da sua API
        const base_url = $('#base_url').val();
        const apiUrl = base_url + 'documentos/getDocumentos';


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
                    <div class="card-body card-documentos">
                        <h5 class="card-title mb-3 convenio-card-state"><i class="fa fa-calendar" aria-hidden="true"></i>  ${item.criado_em}</h5>
                        <h6 class="card-subtitle mb-2 text-muted convenio-card-title">
                            <a href="${base_url}documentos/modelo/${item.id_convenio}/${item.id}">${item.nome}</a>
                        </h6>
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