<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <img src="<?php echo base_url('assets/img/main/'); ?>main.svg" alt="logo" class="img-responsive center-block main-img-header">
            </div>
        </div>
        <div class="clearfix m-b-5"></div>
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="height:100px;border-radius: 8px;background: linear-gradient(0deg, #DCEEFF 0%, #DCEEFF 100%), rgba(165, 212, 255, 0.49);border:0px">
                        <div class="row">
                            <div class="col-md-12">
                                <img width="32px" height="32px" src="<?php echo base_url('assets/img/main/'); ?>doc-icon-card-1.svg" style="float: left;">
                            </div>
                        </div>
                        <div class="row mt-3 main-card-text">
                            <a href="<?=base_url('auth');?>" style='text-decoration: none'><font class="main-card-text">Modelos</font></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="height:100px;border-radius: 8px;background: #F3E3E2;">
                        <div class="row">
                            <div class="col-md-12">
                                <img width="32px" height="32px" src="<?php echo base_url('assets/img/main/'); ?>hist-icon-card-2.svg" style="float: left;">
                            </div>
                        </div>
                        <div class="row mt-3 main-card-text">
                            <a href="<?=base_url('auth');?>" style='text-decoration: none'><font class="main-card-text">Histórico</font></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($this->ion_auth->is_admin()): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="height:100px;border-radius: 8px;background: #FAF1D0;">
                        <div class="row">
                            <div class="col-md-12">
                                <img width="32px" height="32px" src="<?php echo base_url('assets/img/main/'); ?>config-icon-card-3.svg" style="float: left;">
                            </div>
                        </div>
                        <div class="row mt-3 main-card-text">
                            <a href="<?=base_url('auth');?>" style='text-decoration: none'><font class="main-card-text">Configurações do Sistema</font></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="height:100px;border-radius: 8px;background: #FAF1D0;">
                        <div class="row">
                            <div class="col-md-12">
                                <img width="32px" height="32px" src="<?php echo base_url('assets/img/main/'); ?>config-icon-card-3.svg" style="float: left;">
                            </div>
                        </div>
                        <div class="row mt-3 main-card-text">
                            <a href="<?=base_url('convenios');?>" style='text-decoration: none'><font class="main-card-text">Convênios</font></a>
                        </div>
                    </div>
                </div>
            </div> 
            <?php endif; ?>
        </div>
        <div class="clearfix m-b-5"></div>
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header" style="border-radius: 8px;background: #FFF;">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-9">
                                <font class="main-card-text">Últimos Documentos</font>
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="main-card-last-doc-text-plus" style="float: right;text-decoration: none">Ver Todos</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-radius: 8px;background: #FFF;min-height:300px;">
                        <?php foreach($documentos as $doc): ?>
                            <div class="row mb-3"></div>
                            <div class="row mb-3">
                                <a class="main-card-last-doc-text" href="<?=base_url('documentos/modelo/').$doc->id_convenio.'/'.$doc->id;?>" style="text-decoration: none"><?=$doc->nome;?></a>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header" style="border-radius: 8px;background: #FFF;">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-12">
                                <font class="main-card-text">Convênios</font>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-radius: 8px;background: #FFF;">
                        <div class="row mb-3 main-card-convenios-text" style="padding-left: 1em;">Convênios Aprovados</div>
                        <div class="row mb-3 mt-3">
                            <font class="main-card-convenios-numbers"><?=$conveniosAprovados;?></font>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?=($conveniosAprovados*100/$countConvenios);?>%" aria-valuenow="<?=($conveniosAprovados*100/$countConvenios);?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>          
                        <div class="row mt-3">
                            <div class="row">
                                <a href="<?=base_url('convenios');?>" class="btn btn-primary center-block align-bottom" style="width: 80%;border-radius: 8px;float:inline-end;">Ver Convênios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                <div class="card-header" style="border-radius: 8px;background: #FFF;">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-12">
                                <font class="main-card-text">Convênios</font>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-radius: 8px;background: #FFF;">
                        <div class="row mb-3 main-card-convenios-text" style="padding-left: 1em;">Convênios em Análise</div>
                        <div class="row mb-3 mt-3">
                            <font class="main-card-convenios-numbers"><?=$conveniosPendentes;?></font>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?=($conveniosPendentes*100/$countConvenios);?>%" aria-valuenow="<?=($conveniosPendentes*100/$countConvenios);?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>           
                        <div class="row mt-3">
                            <div class="row">
                                <a href="<?=base_url('convenios');?>" class="btn btn-primary center-block align-bottom" style="width: 80%;border-radius: 8px;float:inline-end;">Ver Convênios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>