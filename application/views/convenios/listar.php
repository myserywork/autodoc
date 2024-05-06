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
                            <input id="nameOrNumberSearch" type="text" class="form-control" placeholder="Pesquisar por nome ou número...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="searchStatus" class="form-select" aria-label="Default select example">
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
                        <select id="searchEstado" class="form-select" aria-label="Default select example">
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
                        <input id="searchDate" class="form-control" type="date" />
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

<div class="row">
    <div class="pagination-container">
        <ul id="pagination" class="pagination" >
        </ul>
    </div>
</div>