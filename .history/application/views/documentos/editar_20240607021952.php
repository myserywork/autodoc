<?php

if (isset($convenio)) {
    echo "<script>";
    echo "var convenio = " . json_encode($convenio) . ";";
    echo "var convenioId = " . $convenio->NR_CONVENIO . ";";
    echo "var defaultModel = false;";
    echo "</script>";
} else {
    echo "<script>";
    echo "var convenioId = null;";
    echo "var defaultModel = true;";
    echo "</script>";
}

if(isset($documento)) {
    echo "<script>";
    echo "var documento = " . json_encode($documento) . ";";
    echo "</script>";
}

?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <div id="header" class="row">
                <div class="row">
                    <div class="col-md-1 align-self-right">
                        <a href="<?=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('documentos');?>"><i class="fa fa-long-arrow-left fa-2x" style="color:rgba(0, 0, 0, 0.25)" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-11 align-self-left">
                        <h1 class="convenio-header">Documentos</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <form id="convenioForm">
                <div class="form-group mb-3">
                    <label for="nomeModelo">Número Convênio:</label>
                    <?php if (isset($convenio->NR_CONVENIO)) : ?>                    
                        <input type="text" class="form-control" value="<?=$convenio->NR_CONVENIO;?>" disabled>
                    <?php else : ?>
                        <input id="numeroConvenio" type="text" class="form-control  mb-3" placeholder="Procure pelo número do convênio de desejar sincronizar... Caso não informe um convênio, um modelo padrão será criado...">
                        <select id="selectConvenio" class="form-control selectpicker" data-live-search="true" data-style="input-sm btn-default" style="display:none">
                        </select>
                    <?php endif; ?>
                </div>
                <?php if (isset($convenio->NR_CONVENIO)) : ?> 
                <div class="form-group mb-3">
                    <label for="nomeModelo">Convênio:</label>
                    <textarea class="form-control" disabled><?=isset($convenio->OBJETO_PROPOSTA) ? $convenio->OBJETO_PROPOSTA : '';?></textarea>
                </div>
                <?php endif; ?>
                <div class="form-group mb-3">
                    <label for="nomeModelo">Nome do Modelo:</label>
                    <input type="text" id="nomeModelo" class="form-control" value="<?=((isset($documento)) ? $documento->nome : "");?>" name="nome_modelo">
                </div>
                <div class="form-group mb-3">
                    <label for="tipoConvenio">Tipo:</label>
                    <select id="tipoConvenio" class="form-select">
                        <option value="Artigo" <?=((isset($documento) && $documento->tipo == "Artigo") ? "selected" : "");?>>Artigo</option>
                        <option value="Dinâmico" <?=((isset($documento) && $documento->tipo == "Dinâmico") ? "selected" : "");?>>Dinâmico</option>
                        <option value="Jornalistico" <?=((isset($documento) && $documento->tipo == "Jornalistico") ? "selected" : "");?>>Jornalistico</option>
                        <option value="Notícias" <?=((isset($documento) && $documento->tipo == "Notícias") ? "selected" : "");?>>Notícias</option>                        
                        <option value="Portifólio de Imagens" <?=((isset($documento) && $documento->tipo == "Portifólio de Imagens") ? "selected" : "");?>>Portifólio de Imagens</option>
                        <option value="Outros" <?=((isset($documento) && $documento->tipo == "Outros") ? "selected" : "");?>>Outros</option>
                    </select>
                </div>
                <?php if (isset($convenio->NR_CONVENIO)) : ?>
                <div class="form-group mb-3">
                    <label for="selectConvenio">Informações do Convênio:</label>
                    <select class="form-select ql-insertCustomTags" style="margin-bottom: 10px;">
                        <option>Selecione aqui as variáveis para usar em seu documento...</option>
                        <?php
                        foreach ($convenio_items as $item) {
                            echo "<option value='$item' data-marker='%[$item]%' data-title='%$item%' data-colour='warning'>$item</option>";
                        }
                        ?>
                    </select>
                </div>
                <?php endif; ?>
                <div id="editor" class="form-control mb-3" style="height: 200px;"></div>                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <?php if(isset($documento)) { ?>
                        <a href="<?=base_url('documentos/gerar_pdf/').$documento->id;?>" type="button" class="btn btn-success mt-3" >Exportar para o SEI</a>
                        <a href="<?=base_url('documentos/gerar_pdf/').$documento->id;?>" type="button" class="btn btn-success mt-3" >Baixar PDF</a>
                    <?php } ?>
                    <button type="button" class="btn btn-primary mt-3" onclick="salvarConteudo()">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>