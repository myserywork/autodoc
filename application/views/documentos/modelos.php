<div class="container">
    <div class="row mt-5">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="header" class="row">
                <div class="row">
                    <div class="col-md-1 align-self-center">
                        <a href="<?= base_url(); ?>"><i class="fa fa-long-arrow-left fa-2x" style="color:rgba(0, 0, 0, 0.25)" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-9 align-self-center">
                        <h1 class="convenio-header">Documentos Base</h1>
                    </div>
                    <div class="col-md-2 align-self-center">
                        <a href="<?= base_url('documentos/modelo'); ?>" class="link-dark" style='text-decoration:none;'><i class="fa fa-plus" style="color:rgba(0, 0, 0, 0.25)" aria-hidden="true"></i> Criar Modelo</a>
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
                            <input id="searchNome" type="text" class="form-control" placeholder="Pesquisar por nome...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="searchTipo" class="form-select" >
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

<div class="row">
    <div class="pagination-container">
        <ul id="pagination" class="pagination" >
        </ul>
    </div>
</div>