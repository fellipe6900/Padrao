<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="panel panel-default" id="painel_principal">
    <div class="panel-body panel-nav">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-orcamento-menu">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <ul class="nav navbar-nav">
                        <li>
                            <div class="navbar-brand">Orçamento</div>
                        </li>
                    </ul>
                </div>
                
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-orcamento-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Menu <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php
                                empty($this->session->orcamento->descricao) ? $descricao = '' : $descricao = $this->session->orcamento->descricao;
                                empty($this->session->orcamento->desconto) ? $desconto = "''" : $desconto = $this->session->orcamento->desconto;
                                $pedido = $this->session->pedido;
                                ?>
                                <li class="dropdown-header">Orçamento</li>
                                <li>
                                    <a onclick="orcamento_modal()" href="javascript:void(0)"><i class="glyphicon glyphicon-plus"></i> Novo</a>
                                </li>
                                <li>
                                    <a onclick="session_orcamento_info()" href="javascript:void(0)"><i class="glyphicon glyphicon-info-sign"></i> Informações</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">Opções do orçamento</li>
                                <li>
                                    <a onclick="orcamento_cliente()" href="javascript:void(0)"><i class="glyphicon glyphicon-user"></i> Cliente</a>
                                </li>
                                <li>
                                    <a onclick="orcamento_assessor('inserir', 'Assessores')" href="javascript:void(0)"><i class="glyphicon glyphicon-user"></i> Assessor</a>
                                </li>
                                <li>
                                    <a onclick="orcamento_desconto('inserir',<?= $desconto ?>)" href="javascript:void(0)"><i class="glyphicon glyphicon-piggy-bank"></i> Desconto</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-shopping-cart"></i> Produtos <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?= base_url('convite') ?>"><i class="glyphicon glyphicon-envelope"></i> Convite</a>
                                </li>
                                <li>
                                    <a href="<?= base_url('personalizado') ?>"><i class="fa fa-paint-brush" aria-hidden="true"></i> Personalizado</a>
                                </li>
                                <li>
                                    <a onclick="produto_modal('inserir')" href="javascript:void(0)"><i class="glyphicon glyphicon-gift"></i> Produto
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-save"></i> Salvar <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a onclick="criar_orcamento()" href="javascript:void(0)"><i class="glyphicon glyphicon-floppy-save"></i> Salvar como orçamento</a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a onclick="criar_pedido()" href="javascript:void(0)"><i class="glyphicon glyphicon-floppy-open"></i> Salvar como pedido</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="col-sm-12">
            <div class="row">
                <input type="hidden" id="panel_cliente_id" class="form-control" value="<?=$this->session->orcamento->cliente->id?>">
                <div class="col-sm-3">
                    <label for="" class="control-label">Cliente: </label>
                    <input type="text" class="form-control input-sm panel_cliente_nome" value="<?=$this->session->orcamento->cliente->nome?> <?=$this->session->orcamento->cliente->sobrenome?>" readonly>
                </div>
                <div class="col-sm-3">
                    <label for="" class="control-label">E-mail:</label>
                    <input type="text" class="form-control input-sm panel_cliente_email" value="<?=$this->session->orcamento->cliente->email?>" readonly>
                </div>
                <div class="col-sm-3">
                    <label for="" class="control-label">Assessor(a):</label>
                    <input type="text" name="" class="form-control input-sm panel_assessor_nome" value="<?=$this->session->orcamento->assessor->nome . ' ' . $this->session->orcamento->assessor->sobrenome?>" title="Assessor" readonly>
                </div>
                <div class="col-sm-3">
                    <label for="" class="control-label">E-mail:</label>
                    <input type="text" name="" id="input" class="form-control input-sm panel_assessor_email" value="<?=$this->session->orcamento->assessor->email?>" title="email" readonly>
                </div>
            </div>
            <br>
            
            <!-- Tabela do orçamento -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th>#</th>
                                <th>Categoria</th>
                                <th>Produto</th>
                                <th class="data_entrega">Data Entrega</th>
                                <th>Qtd</th>
                                <th>Unitário</th>
                                <th>Sub-total</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                            <tbody>
                                <?php
                                $count = 1;
                                //CONVITES
                                foreach ($this->session->orcamento->convite as $key => $convite) {
                                    ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td>Convite</td>
                                        <td><?= $convite->modelo->nome ?></td>
                                        <td class="data_entrega form-group">
                                            <form id="convite-<?= $key ?>" class="form_data_entrega">
                                                <input onchange="delivery_date('convite', '#convite-<?= $key ?>')" type="text" name="data_entrega-convite-<?= $key ?>" class="form-control input-sm datetimepicker input_data_entrega" value="<?= $convite->data_entrega ?>" placeholder="dd/mm/yyyy">
                                                <span class="help-block"></span>
                                                <input type="hidden" name="posicao" id="posicao-<?= $key ?>" class="form-control" value="<?= $key ?>">
                                            </form>
                                        </td>
                                        <td><?= $convite->quantidade ?></td>
                                        <td>R$ <?= number_format($convite->calcula_unitario(), 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($convite->calcula_total(), 2, ',', '.') ?></td>
                                        <td><a href="<?= base_url('convite/session_orcamento_convite_editar/' . $key) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                        <td><a onclick="convite_excluir_posicao(<?= $key ?>)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>
                                    </tr>
                                    <?php
                                    $count ++;
                                }
                                //PERSONALIZADOS
                                foreach ($this->session->orcamento->personalizado as $key => $personalizado) {
                                    ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td>Personalizado</td>
                                        <td><?= $personalizado->modelo->nome ?></td>
                                        <td class="data_entrega form-group">
                                            <form id="personalizado-<?= $key ?>" class="form_data_entrega">
                                                <input onchange="delivery_date('personalizado', '#personalizado-<?= $key ?>')" type="text" name="data_entrega-personalizado-<?= $key ?>" class="form-control input-sm datetimepicker input_data_entrega" value="<?= $personalizado->data_entrega ?>" placeholder="dd/mm/yyyy">
                                                <span class="help-block"></span>
                                                <input type="hidden" name="posicao" id="posicao-<?= $key ?>" class="form-control" value="<?= $key ?>">
                                            </form>
                                        </td>
                                        <td><?= $personalizado->quantidade ?></td>
                                        <td>R$ <?= number_format($personalizado->calcula_unitario(), 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($personalizado->calcula_total(), 2, ',', '.') ?></td>
                                        <td><a href="<?= base_url('personalizado/session_orcamento_personalizado_editar/' . $key) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                        <td><a onclick="personalizado_excluir_posicao(<?= $key ?>)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>
                                    </tr>
                                    <?php
                                    $count ++;
                                }
                                //PRODUTOS
                                foreach ($this->session->orcamento->produto as $key => $container) {
                                    ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><?= $container->produto->produto_categoria->nome ?></td>
                                        <td><?= $container->produto->nome ?></td>
                                        <td class="data_entrega form-group">
                                            <form id="produto-<?= $key ?>" class="form_data_entrega">
                                                <input onchange="delivery_date('produto', '#produto-<?= $key ?>')" type="text" name="data_entrega-produto-<?= $key ?>" class="form-control input-sm datetimepicker input_data_entrega" value="<?= $container->data_entrega ?>" placeholder="dd/mm/yyyy">
                                                <span class="help-block"></span>
                                                <input type="hidden" name="posicao" id="posicao-<?= $key ?>" class="form-control" value="<?= $key ?>">
                                            </form>
                                        </td>
                                        <td><?= $container->quantidade ?></td>
                                        <td>R$ <?= number_format($container->calcula_unitario(), 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($container->calcula_total(), 2, ',', '.') ?></td>
                                        <td><a onclick="produto_modal('editar',<?= $key ?>,<?= $container->produto->id ?>, '<?= $container->produto->nome ?>',<?= $container->quantidade ?>, '<?= $container->descricao ?>',<?= $container->produto->produto_categoria->id ?>)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                        <td>
                                        <a onclick="produto_excluir_posicao(<?= $key ?>)" class="btn btn-sm btn-default">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $count ++;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <?php
                                if (!empty($this->session->orcamento->desconto)) {
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="data_entrega"></td>
                                        <td></td>
                                        <td>Desconto</td>
                                        <td>R$ <?= number_format($this->session->orcamento->desconto, 2, ',', '.') ?></td>
                                        <td>
                                            <a onclick="orcamento_desconto('editar',<?= $this->session->orcamento->desconto ?>)" class="btn btn-sm btn-default">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="orcamento_desconto('excluir', '')" class="btn btn-sm btn-default">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="data_entrega"></th>
                                    <th></th>
                                    <th>Total a pagar</th>
                                    <th>R$ <?= number_format($this->session->orcamento->calcula_total(), 2, ',', '.') ?></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Descrição -->
            <div class="row">
                <div class="col-sm-12">
                    <form  class="form_ajax" id="form_descricao" action="" method="post" accept-charset="utf-8" role="form">
                        <div class="form-group">
                            <label for="descricao" class="control-label">Descrição:</label>
                            <textarea name="descricao" id="form_descricao_txt" class="form-control" rows="3" onchange="session_orcamento_descricao()"><?= $this->session->orcamento->descricao ?></textarea>
                            <span class="help-block"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal: Produto -->
<div class="modal fade" id="md_produto">
    <div class="modal-dialog">
        <form id="form_produto" action="" method="post" accept-charset="utf-8" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Produto</h4>
                </div>			
                <div class="modal-body row">
                    <div class="form-group col-sm-4">
                        <label for="produto_categoria" class="control-label">Categoria:</label>
                        <select id="produto_categoria" class="form-control selectpicker" data-live-search="true" autofocus required>
                            <option disabled selected>Selecione</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="produto" class="control-label">Produto:</label>
                        <select name="produto" id="produto" class="form-control selectpicker" data-live-search="true" required>
                            <option disabled selected>Selecione</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="quantidade_produto" class="control-label">Quantidade:</label>
                        <input type="number" name="quantidade" id="quantidade_produto" step="1" class="form-control" value="" min="1" placeholder="Quantidade de produtos" required>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="descricao" class="control-label">Descrição:</label>
                        <textarea id="descricao_produto" name="descricao" class="form-control" rows="3" placeholder="Descrição"></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-default btnSubmit">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal: Clientes Tabela-->
<div class="modal fade" id="md_clientes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Clientes</h4>
            </div>
            <nav class="navbar navbar-default navbar-static-top" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-acabamento-menu">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="navbar-brand"></div>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-acabamento-menu">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" onclick="pre_crud('cliente', 'adicionar', '#form_cliente', '#md_clientes', '#md_form_cliente', '<?= base_url('cliente/ajax_add') ?>')"><i class="glyphicon glyphicon-plus"></i> Adicionar</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" onclick="pre_crud('cliente', 'editar', '#form_cliente', '#md_clientes', '#md_form_cliente', '<?= base_url('cliente/ajax_update') ?>')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a data-toggle="modal" href='#md_filtro_cliente'><i class="glyphicon glyphicon-search"></i> Filtro</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" class="btn-reset"><i class="glyphicon glyphicon-erase"></i> Limpar Filtro</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="tabela_cliente" class="table display compact table-bordered " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Nome2</th>
                                    <th>Sobrenome2</th>
                                    <th>Email2</th>
                                    <th>Telefone2</th>
                                    <th>Rg</th>
                                    <th>Cpf</th>
                                    <th>Endereço</th>
                                    <th>Número</th>
                                    <th>Complemento</th>
                                    <th>Estado</th>
                                    <th>UF</th>
                                    <th>Bairro</th>
                                    <th>Cidade</th>
                                    <th>Cep</th>
                                    <th>Observacao</th>
                                    <th>Pessoa</th>
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>I.E</th>
                                    <th>I.M</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-default" id="md_cliente_selecionar" ><i class="glyphicon glyphicon-circle-arrow-right"></i> Selecionar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal: Cliente Form e Filtro-->
<?php $this->load->view('cliente/cliente_modal'); ?>
<!-- Modal: Assessor Filtro -->
<?php $this->load->view('assessor/assessor_modal'); ?>
<!-- Modal: Orcamento Info -->
<div class="modal fade" id="md_orcamento_info">
    <form id="form_orcamento_info"  action="#" method="POST" accept-charset="utf-8">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-info-sign"></i> Informações do orçamento</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="loja" class="control-label"><i class="glyphicon glyphicon-map-marker"></i> Loja:</label>
                                    <select name="loja" id="loja" class="form-control" required>
                                        <option value="" selected >Selecione</option>
                                        <?php foreach ($dados['lojas'] as $loja): ?>
                                            <option value="<?= $loja->id ?>" <?php ($loja->id === $this->session->orcamento->loja->id) ? print 'selected' : '' ?>><?= $loja->unidade ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="evento" class="control-label"><i class="glyphicon glyphicon-edit"></i> Evento:</label>
                                    <select name="evento" id="evento" class="form-control" required>
                                        <option value="" selected >Selecione</option>
                                        <?php foreach ($dados['eventos'] as $evento): ?>
                                            <option value="<?= $evento->id ?>" <?php ($evento->id === $this->session->orcamento->evento) ? print 'selected' : '' ?>><?= $evento->nome ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="data_evento" class="control-label"><i class="glyphicon glyphicon-calendar"></i> Data Evento:</label>
                                    <input type='text' name="data_evento" id="data_evento" class="form-control datetimepicker" value="<?= $this->session->orcamento->get_data_evento() ?>" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="orcamento_info">
                            <!-- Cliente -->
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-top: 25px">
                                            <i class="glyphicon glyphicon-cog"></i>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li>
                                                <a href="javascript:void(0)" onclick="orcamento_cliente()">
                                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Adicionar
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" id="panel_cliente_id_onclick" onclick="orcamento_cliente_editar(<?=$this->session->orcamento->cliente->id?>)">
                                                    <i class="glyphicon glyphicon-pencil"></i> Editar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">Cliente:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_cliente_nome" value="<?=$this->session->orcamento->cliente->nome . ' ' . $this->session->orcamento->cliente->sobrenome?>" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">E-mail:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_cliente_email" value="<?=$this->session->orcamento->cliente->email?>" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <label for="" class="control-label">Telefone:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_cliente_telefone" value="<?=$this->session->orcamento->cliente->telefone?>" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">CPF:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_cliente_cpf" value="<?=$this->session->orcamento->cliente->cpf?>" readonly>
                                </div>
                            </div>
                            <hr>
                            <!-- Assessor -->
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-top: 25px">
                                            <i class="glyphicon glyphicon-cog"></i>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li>
                                                <a href="javascript:void(0)" onclick="orcamento_assessor('inserir', 'Assessores')">
                                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Adicionar
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" id="panel_assessor_id_onclick" onclick="orcamento_assessor_editar(<?=$this->session->orcamento->assessor->id?>)">
                                                    <i class="glyphicon glyphicon-pencil"></i> Editar
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="orcamento_assessor('excluir', '')">
                                                    <i class="glyphicon glyphicon-trash"></i> Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">Assessor(a):</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_assessor_nome" value="<?=$this->session->orcamento->assessor->nome . ' ' . $this->session->orcamento->assessor->sobrenome?>" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">E-mail:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_assessor_email" value="<?=$this->session->orcamento->assessor->email?>" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <label for="" class="control-label">Telefone:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_assessor_telefone" value="<?=$this->session->orcamento->assessor->telefone?>" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label for="" class="control-label">Empresa:</label>
                                    <input type="text" name="" id="" class="form-control input-sm panel_assessor_empresa" value="<?=$this->session->orcamento->assessor->empresa?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-default btnSubmit">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Modal: Assessores Tabela -->
<div class="modal fade" id="md_assessores">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Assessores</h4>
            </div>
            <nav class="navbar navbar-default navbar-static-top" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-acabamento-menu">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="navbar-brand"></div>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-acabamento-menu">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" onclick="pre_crud('assessor', 'adicionar', '#form_assessor', '#md_assessores', '#md_form_assessor', '<?= base_url('assessor/ajax_add') ?>')"><i class="glyphicon glyphicon-plus"></i> Adicionar</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" onclick="pre_crud('assessor', 'editar', '#form_assessor', '#md_assessores', '#md_form_assessor', '<?= base_url('assessor/ajax_update') ?>')" id="editar_assessor"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a data-toggle="modal" href='#md_filtro_assessor'><i class="glyphicon glyphicon-search"></i> Filtro</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)" class="btn-reset" data-reset_filtro="assessor"><i class="glyphicon glyphicon-erase"></i> Limpar Filtro</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="tabela_assessor" class="table display compact table-bordered " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>Empresa</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Comissão(%)</th>
                                    <th>Descrição</th>
                                    <th>Ativo</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-default" id="md_assessor_selecionar" ><i class="glyphicon glyphicon-circle-arrow-right"></i> Selecionar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal: Desconto -->
<div class="modal fade" id="md_desconto">
    <div class="modal-dialog modal-sm">
        <form  id="form_desconto" action="" method="post" accept-charset="utf-8" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Desconto</h4>
                </div>			
                <div class="modal-body">
                    <div class="form-group">
                        <label for="desconto" class="control-label">Desconto:</label>
                        <input type="number" name="desconto" id="desconto" class="form-control" value="" step="0.01" min="0">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-default btnSubmit">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="md_forma_pagamento">
    <form  class="form-horizontal" id="form_forma_pagamento" action="" method="post" accept-charset="utf-8" role="form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Forma de pagamento</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row">
                            <!-- Forma de pagamento -->
                            <div class="col-sm-6">
                                <div class="form-group input-padding">
                                    <label for="forma_pagamento" class="control-label">Forma de pagamento:</label>
                                    <select name="forma_pagamento" id="forma_pagamento" class="form-control selectpicker" autofocus onchange="get_quantidade_parcelas()" data-show-subtext="true">
                                        <option value="" selected >Selecione</option>
                                        <?php
                                        foreach ($dados['forma_pagamento'] as $key => $forma_pagamento) {
                                            ?>
                                            <option value="<?= $forma_pagamento->id ?>" data-parcelamento_maximo="<?=$forma_pagamento->parcelamento_maximo?>" data-valor_minimo="<?=$forma_pagamento->valor_minimo?>" data-subtext="(Max <?=$forma_pagamento->parcelamento_maximo?>x - Min R$ <?=$forma_pagamento->valor_minimo?>)"><?= $forma_pagamento->nome ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <!-- Quantidade de parcelas -->
                            <div class="col-sm-6">
                                <div class="form-group input-padding">
                                    <label for="qtd_parcelas" class="control-label">Quantidade de Parcelas:</label>
                                    <select name="qtd_parcelas" id="qtd_parcelas" class="form-control">
                                        <option value="" selected>Selecione</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Primeiro vencimento -->
                            <div class="col-sm-6">
                                <div class="form-group input-padding">
                                    <label for="primeiro_vencimento" class="control-label">1º Vencimento:</label>
                                    <input type="text" name="primeiro_vencimento" id="primeiro_vencimento" class="form-control datetimepicker" placeholder="1° Vencimento dd/mm/aaaa">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <!-- Próximos vencimentos -->
                            <div class="col-sm-6">
                                <div class="form-group input-padding">
                                    <label for="vencimento_dia" class="control-label">Próximos vencimentos:</label>
                                    <select name="vencimento_dia" id="vencimento_dia" class="form-control">
                                        <option value="" selected >Selecione</option>
                                        <option value="01">Todo dia 1</option>
                                        <option value="05">Todo dia 5</option>
                                        <option value="10">Todo dia 10</option>
                                        <option value="15">Todo dia 15</option>
                                        <option value="20">Todo dia 20</option>
                                        <option value="25">Todo dia 25</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!--Condições-->
                            <div class="col-sm-12">
                                <div class="form-group input-padding">
                                <label for="condicoes" class="control-label">Condições:</label>
                                    <textarea name="condicoes" id="condicoes" class="form-control" rows="3" placeholder="Condições"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button onclick="finalizar_pedido(event)" type="button" class="btn btn-default btnSubmit">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $this->load->view('_include/dataTable'); ?>
<script>

    var tabela_cliente;
    var tabela_assessor;
    /*Variaveis globais para o crud do cliente e assessor*/
    var form_crud;
    var url_crud;
    var url_edit_id;
    var md_form_crud;
    var md_tb_crud;
    var owner_crud;
    var criarPedido = false;
    /*Variaveis globais para o ajax*/
    var form_ajax;
    var url_ajax;
    var modal_ajax;

    var data_entrega_show = false;

    $(document).ready(function () {
        apply_this_document_ready();
    });

    function apply_this_document_ready() {
        $('.datetimepicker').datetimepicker({
            format:'L'
        });
        //Executa o evento change do datetimepicker
        $(".datetimepicker").on("dp.change", function (e) {
            $(e.target).trigger("change");
        });
        $("#input_cep").keyup(carregaCep);
        //Deixa pré setado o filtro do assessor como somente os ativos
        $('#filtro_assessor_ativo option[value=1]').prop('selected','selected');
        //Verifica se o orçamento info já foi preechido
        session_orcamento_info(false);
        is_empty_orcamento_info(false);
        $.each($("td form.form_data_entrega input.input_data_entrega"), function(index, val) {
             if($(val).val() != ""){
                $('.data_entrega').show();
                return;
             }
        });
        /*
        is_set_delivery_date();
        //apagar 
        if (data.status && !data.date_found) {
                $('.data_entrega').hide();
            } else {
                $('.data_entrega').show();
            }
        apagar//
        $("#form_orcamento_info").on('submit', function (e) {

            is_empty_orcamento_cliente(false);
        });
        */
        $("#qtd_parcelas").click(function (event) {
            if ($("#qtd_parcelas").val() == 1) {
                $("#vencimento_dia").attr("disabled", true);
                $("#vencimento_dia option[value='']").prop("selected", true);
            } else {
                $("#vencimento_dia").attr("disabled", false);
            }
        });
        //button filter event click
        $('#btn-filter-cliente').click(function () {
            //just reload table
            tabela_cliente.ajax.reload(null, false);
            $("#md_filtro_cliente").modal('hide');
        });
        //button reset event click
        $('.btn-reset').click(function () {
            if($(this).data('reset_filtro') == 'assessor'){
                console.log('.btn-reset(assessor)');
                $('#form-filter-assessor')[0].reset();
                if ($.fn.DataTable.isDataTable('#tabela_assessor')) {
                    tabela_assessor.ajax.reload(null, false);
                }
            }else{
                console.log('.btn-reset(cliente)');
                $('#form-filter-cliente')[0].reset();
                if ($.fn.DataTable.isDataTable('#tabela_cliente')) {
                    tabela_cliente.ajax.reload(null, false);
                }
            }
        });
        //button filter event click
        $('#btn-filter-assessor').click(function () {
            //just reload table
            tabela_assessor.ajax.reload(null, false);
            $("#md_filtro_assessor").modal('hide');
        });
        // Resaltar a linha selecionada
        $("#tabela_cliente tbody").on("click", "tr", function () {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
            } else {
                tabela_cliente.$("tr.selected").removeClass("selected");
                $(this).addClass("selected");
            }
        });
        // Resaltar a linha selecionada
        $("#tabela_assessor tbody").on("click", "tr", function () {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
                disable_button_salvar();
            } else {
                tabela_assessor.$("tr.selected").removeClass("selected");
                $(this).addClass("selected");
                enable_button_salvar();
            }
        });
        //Inserir o cliente no orçamento
        $('#md_assessor_selecionar').click(function () {
            var id = tabela_assessor.row(".selected").id();
            if (id != "") {
                session_assessor_inserir(id);
            }
        });
        //Inserir o cliente no orçamento
        $('#md_cliente_selecionar').click(function () {
            var id = tabela_cliente.row(".selected").id();
            if (id != "") {
                session_cliente_inserir(id);
            }
        });
        $("#form_cliente").submit(function (event) {
            event.preventDefault();
            if($("#form_cliente #pessoa_tipo").val() == ""){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #pessoa_tipo").focus();
                mapear_erro($("#form_cliente #pessoa_tipo"),"O campo Pessoa é obrigatório.");
                return;
            }
            if($("#form_cliente #pessoa_tipo").val() == "juridica"){
                if($("#form_cliente #razao_social").val() == ""){
                    $("#form_cliente a[href='#juridica']").tab('show');
                    $("#form_cliente #razao_social").focus();
                    mapear_erro($("#form_cliente #razao_social"),"O campo Razão Social é obrigatório.");
                    return;
                }
            }
            if($("#form_cliente #nome").val() == ""){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #nome").focus();
                mapear_erro($("#form_cliente #nome"),"O campo Nome é obrigatório.");
                return;
            }
            if($("#form_cliente #sobrenome").val() == ""){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #sobrenome").focus();
                mapear_erro($("#form_cliente #sobrenome"),"O campo Sobrenome é obrigatório.");
                return;
            }
            if($("#form_cliente #email").val() == ""){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #email").focus();
                mapear_erro($("#form_cliente #email"),"O campo Email é obrigatório.");
                return;
            }
            if(!validar_email( $("#form_cliente #email").val() )){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #email").focus();
                mapear_erro($("#form_cliente #email"),"O campo Email deve conter um endereço de e-mail válido.");
                return;
            }
            if($("#form_cliente #telefone").val() == ""){
                $("#form_cliente a[href='#fisica']").tab('show');
                $("#form_cliente #telefone").focus();
                mapear_erro($("#form_cliente #telefone"),"O campo Telefone é obrigatório.");
                return;
            }
            if($("#form_cliente #cpf").val() != ""){
                if(!validar_cpf($("#cpf").val())){
                    $("#form_cliente a[href='#fisica']").tab('show');
                    $("#form_cliente #cpf").focus();
                    mapear_erro($("#form_cliente #cpf"),"O CPF informado é inválido!");
                    return;
                }
            }
            if($("#form_cliente #cnpj").val() != ""){
                if(!validar_cnpj($("#cnpj").val())){
                    $("#form_cliente a[href='#juridica']").tab('show');
                    $("#form_cliente #cnpj").focus();
                    mapear_erro($("#form_cliente #cnpj"),"O CNPJ informado é inválido!");
                    return;
                }
            }
            disable_button_salvar();
            reset_errors();
            var form = form_crud;
            var url = url_crud;
            var modal_form = md_form_crud;
            var owner = owner_crud;
            $.ajax({
                url: url,
                type: "POST",
                data: $(form).serialize(),
                dataType: "JSON",
            })
            .done(function (data) {
                console.log("success");
                enable_button_salvar();
                if (data.status)
                {
                    $(modal_form).modal('hide');
                    session_cliente_inserir(data.id);
                    reload_table_cliente();
                } else
                {
                    close_loadingModal();
                    reset_errors_crud();
                    $.map(data.form_validation, function (value, index) {
                        $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                        $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                        var juridica = ["razao_social", "cnpj", "ie", "im"];
                        var fisica = ["nome", "sobrenome", "email", "telefone", "nome2", "sobrenome2", "email2", "telefone2", "rg", "cpf"];
                        var endereco = ['endereco', 'numero', 'complemento', 'estado', 'uf', 'bairro', 'cidade', 'cep', 'observacao'];
                        if ($.inArray(index, fisica) !== -1) {
                            $('a[href="#fisica"]').children().addClass('glyphicon glyphicon-remove');
                        }
                        if ($.inArray(index, juridica) !== -1) {
                            $('a[href="#juridica"]').children().addClass('glyphicon glyphicon-remove');
                        }
                        if ($.inArray(index, endereco) !== -1) {
                            $('a[href="#endereco"]').children().addClass('glyphicon glyphicon-remove');
                        }
                    });
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
                enable_button_salvar();
            });
        });
        $("#form_assessor").submit(function (event) {
            event.preventDefault();
            if($("#form_assessor #nome").val() == ""){
                $("#form_assessor #nome").focus();
                mapear_erro($("#form_assessor #nome"),"O campo Nome é obrigatório.");
                return;
            }
            if($("#form_assessor #sobrenome").val() == ""){
                $("#form_assessor #sobrenome").focus();
                mapear_erro($("#form_assessor #sobrenome"),"O campo Sobrenome é obrigatório.");
                return;
            }
            if($("#form_assessor #email").val() == ""){
                $("#form_assessor #email").focus();
                mapear_erro($("#form_assessor #email"),"O campo Email é obrigatório.");
                return;
            }
            if(!validar_email( $("#form_assessor #email").val() )){
                $("#form_assessor #email").focus();
                mapear_erro($("#form_assessor #email"),"O campo Email deve conter um endereço de e-mail válido.");
                return;
            }
            if($("#form_assessor #telefone").val() == ""){
                $("#form_assessor #telefone").focus();
                mapear_erro($("#form_assessor #telefone"),"O campo Telefone é obrigatório.");
                return;
            }
            if($("#form_assessor #comissao").val() == ""){
                $("#form_assessor #comissao").focus();
                mapear_erro($("#form_assessor #comissao"),"O campo Comissão é obrigatório.");
                return;
            }
            if(!$.isNumeric($("#form_assessor #comissao").val())){
                $("#form_assessor #comissao").focus();
                mapear_erro($("#form_assessor #comissao"),"O campo Comissão deve conter apenas números.");
                return;
            }
            disable_button_salvar();
            reset_errors();
            var form = form_crud;
            var url = url_crud;
            var modal_form = md_form_crud;
            var owner = owner_crud;
            $.ajax({
                url: url,
                type: "POST",
                data: $(form).serialize(),
                dataType: "JSON",
            })
            .done(function (data) {
                console.log("success");
                enable_button_salvar();
                if (data.status)
                {
                    $(modal_form).modal('hide');
                    session_assessor_inserir(data.id);
                    reload_table_assessor();
                } else
                {
                    close_loadingModal();
                    reset_errors_crud();
                    $.map(data.form_validation, function (value, index) {
                        $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                        $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                    });
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
                enable_button_salvar();
            });
        });
        $("#form_forma_pagamento #qtd_parcelas").change(function(event) { // Limpa o erro dos próximos vencimentos caso selecione 1 parcela
            if($("#form_forma_pagamento #qtd_parcelas").val() == 1){
                $("#form_forma_pagamento #vencimento_dia").closest(".form-group").removeClass('has-error').find('.help-block').empty();
            }
        });
    }

    function reset_form_crud() {
        $('#form_cliente')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.error_validation').removeClass('glyphicon-remove');
        $('.help-block').empty();
    }

    function reset_errors_crud() {
        $('.form-group').removeClass('has-error');
        $('.error_validation').removeClass('glyphicon-remove');
        $('.help-block').empty();
    }

    function pre_crud(owner, action, form, md_tb, md_form, url) {
        console.log('Função: pre_crud()');
        reset_errors_crud();
        form_crud = form;
        url_crud = url;
        md_tb_crud = md_tb;
        md_form_crud = md_form;
        owner_crud = owner;
        var id = "";
        if (owner == 'assessor') {
            console.log('owner: Assessor');
            id = tabela_assessor.row(".selected").id();
            if (action == 'adicionar') {
                console.log('action: adicionar');
                adicionar(form, md_tb_crud, md_form_crud);
            } else if (action == 'editar') {
                console.log('action: editar');
                url_edit_id = "<?= base_url('assessor/ajax_edit/') ?>" + id;
                editar(id, md_tb_crud, md_form_crud);
            } else {
                console.log('Nenhuma action foi definida!');
            }
        } else if (owner == 'cliente') {
            console.log('owner: Cliente');
            id = tabela_cliente.row(".selected").id();
            if (action == 'adicionar') {
                console.log('action: adicionar');
                adicionar(form, md_tb_crud, md_form_crud);
            } else if (action == 'editar') {
                console.log('action: editar');
                url_edit_id = "<?= base_url('cliente/ajax_edit/') ?>" + id;
                editar(id, md_tb_crud, md_form_crud, 'Editar Cliente');
            } else {
                console.log('Nenhuma action foi definida!');
            }
        } else {
            console.log('Nenhum owner foi definido!');
        }
    }

    function adicionar(form, md_tb_crud, md_form_crud) {
        console.log('Função: adicionar()');
        reset_form(form);
        $(".ativo-crud").prop('checked', true);
        $(md_tb_crud).modal('hide');

        save_method = 'add';
        $("input[name='id']").val("");

        $(md_form_crud).modal('show');
    }

    function editar(id, md_tb_crud, md_form_crud) {
        console.log('Função: editar()');
        var form = form_crud;
        var url = url_crud;
        var url_edit = url_edit_id;
        var md_tb = md_tb_crud;
        var md_form = md_form_crud;
        var owner = owner_crud;
        if (!id) {
            return;
        }
        reset_form(form);

        save_method = 'edit';
        $("input[name='id']").val(id);
        $.ajax({
            url: url_edit,
            type: "POST",
            dataType: "JSON",
            success: function (data)
            {
                console.log('success: preenchendo o formulário com o id que foi passado...');
                if (owner == 'assessor') {
                    console.log('owner: assessor');
                    $.map(data.assessor, function (value, index) {
                        preencher_por_mapeamento(value,index);
                    });
                } else if (owner == 'cliente') {
                    console.log('owner: cliente');
                    $.map(data.cliente, function (value, index) {
                        preencher_por_mapeamento(value,index);
                    });
                }
                $(md_form).modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Erro ao buscar os dados');
            },
            complete: function(){
                enable_button_salvar();
            }
        });
    }

    function preencher_por_mapeamento(value,index) {
        console.log('preencher_por_mapeamento()');
        if ($('[name="' + index + '"]').is("input, textarea")) {
            if($('[name="' + index + '"]').is(':checkbox')){
                if(value === "0"){checked = false;}else{ checked = true;}
                $('[name="' + index + '"]').prop('checked', checked);
            }else{
                $('[name="' + index + '"]').val(value);
            }
        }else if ($('[name="' + index + '"]').is("select")){
            $('[name="' + index + '"] option[value=' + value + ']').prop("selected", "selected");
        }
    }

    function orcamento_info_modal() {

        $('#md_orcamento_info').modal();
    }

    function orcamento_assessor(acao) {
        load_assessor_dataTable();
        if (acao === 'inserir') {
            $("#md_assessores").modal();
        } else if (acao === 'excluir') {
            $.ajax({
                url: '<?= base_url('orcamento/ajax_session_assessor/excluir')?>',
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
                console.log("success");
                if(data.status){
                    $(".panel_assessor_nome").val("");
                    $(".panel_assessor_email").val("");
                    $(".panel_assessor_empresa").val("");
                    $(".panel_assessor_telefone").val("");
                    $.alert({
                        title: "Sucesso!",
                        content: "Assessor excluido com sucesso!"
                    });
                }
            })
            .fail(function() {
                console.log("error");
            })      
        }
    }

    function orcamento_cliente() {
        load_cliente_dataTable();
        $("#md_clientes").modal();
    }

    function load_assessor_dataTable() {
        if (!$.fn.DataTable.isDataTable('#tabela_assessor')) {
            tabela_assessor = $("#tabela_assessor").DataTable({
                scrollX: true,
                dom: 'lBfrtip',
                buttons: [
                {
                    extend: 'colvis',
                    text: 'Visualizar colunas'
                }],
                language: {
                    url: "<?= base_url("assets/idioma/dataTable-pt.json") ?>"
                },
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                ajax: {
                    url: "<?= base_url('assessor/ajax_list') ?>",
                    type: "POST",
                    data: function (data) {
                        data.filtro_id = $('#filtro_assessor_id').val();
                        data.filtro_nome = $('#filtro_assessor_nome').val();
                        data.filtro_sobrenome = $('#filtro_assessor_sobrenome').val();
                        data.filtro_telefone = $('#filtro_assessor_telefone').val();
                        data.filtro_email = $('#filtro_assessor_email').val();
                        data.filtro_ativo = $('#filtro_assessor_ativo').val();
                    },
                },
                columns: [
                    {data: "id", "visible": true},
                    {data: "nome", "visible": true},
                    {data: "sobrenome", "visible": true},
                    {data: "empresa", "visible": true},
                    {data: "telefone", "visible": true},
                    {data: "email", "visible": true},
                    {data: "comissao", "visible": false},
                    {data: "descricao", "visible": true},
                    {data: "ativo", "visible": false},
                ]
            });
        } else {
            tabela_assessor.ajax.reload(null, false);
        }
    }

    function load_cliente_dataTable() {
        if (!$.fn.DataTable.isDataTable('#tabela_cliente')) {
            tabela_cliente = $("#tabela_cliente").DataTable({
                scrollX: true,
                dom: 'lBfrtip',
                buttons: [
                {
                    extend: 'colvis',
                    text: 'Visualizar colunas'
                }],
                order: [[0, 'desc']],//Última inserção para facilitar
                language: {
                    url: "<?= base_url("assets/idioma/dataTable-pt.json") ?>"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?= base_url('cliente/ajax_list') ?>",
                    type: "POST",
                    data: function (data) {
                        data.filtro_id = $('#filtro_id').val();
                        data.filtro_email = $('#filtro_email').val();
                        data.filtro_nome = $('#filtro_nome').val();
                        data.filtro_sobrenome = $('#filtro_sobrenome').val();
                        data.filtro_telefone = $('#filtro_telefone').val();
                        data.filtro_cpf = $('#filtro_cpf').val();
                        data.filtro_cnpj = $('#filtro_cnpj').val();
                        data.filtro_razao_social = $('#filtro_razao_social').val();
                    },
                },
                columns: [
                    {data: "id", "visible": true},
                    {data: "nome", "visible": true},
                    {data: "sobrenome", "visible": true},
                    {data: "email", "visible": true},
                    {data: "telefone", "visible": true},
                    {data: "nome2", "visible": false},
                    {data: "sobrenome2", "visible": false},
                    {data: "email2", "visible": false},
                    {data: "telefone2", "visible": false},
                    {data: "rg", "visible": false},
                    {data: "cpf", "visible": true},
                    {data: "endereco", "visible": false},
                    {data: "numero", "visible": false},
                    {data: "complemento", "visible": false},
                    {data: "estado", "visible": false},
                    {data: "uf", "visible": false},
                    {data: "bairro", "visible": false},
                    {data: "cidade", "visible": false},
                    {data: "cep", "visible": false},
                    {data: "observacao", "visible": false},
                    {data: "pessoa_tipo", "visible": false},
                    {data: "razao_social", "visible": false},
                    {data: "cnpj", "visible": false},
                    {data: "ie", "visible": false},
                    {data: "im", "visible": false},
                ]
            });
        } else {
            tabela_cliente.ajax.reload(null, false);
        }
    }

    function orcamento_cliente_editar(id = "") {
        if(id != ""){
            form_crud = '#form_cliente';
            url_crud = "<?= base_url('cliente/ajax_update') ?>";
            md_tb_crud = '#md_clientes';
            md_form_crud = '#md_form_cliente';
            owner_crud = 'cliente';
            url_edit_id = "<?= base_url('cliente/ajax_edit/') ?>" + id;
            reset_errors_crud();
            editar(id, md_tb_crud, md_form_crud);
            load_cliente_dataTable();
        }
    }

    function orcamento_assessor_editar(id = "") {
        if(id != ""){
            form_crud = '#form_assessor';
            url_crud = "<?= base_url('assessor/ajax_update') ?>";
            md_tb_crud = '#md_assessores';
            md_form_crud = '#md_form_assessor';
            owner_crud = 'assessor';
            url_edit_id = "<?= base_url('assessor/ajax_edit/') ?>" + id;
            reset_errors_crud();
            editar(id, md_tb_crud, md_form_crud);
            load_assessor_dataTable();
        }
    }

    function session_orcamento_info(modal_open = true) {
        pre_submit('#form_orcamento_info', '<?= base_url('orcamento/ajax_session_orcamento_info') ?>', '#md_orcamento_info');
        if (modal_open) {
            $('#md_orcamento_info').modal();
        }
    }

    function session_orcamento_descricao() {
        $.ajax({
            url: '<?=base_url('orcamento/ajax_session_orcamento_descricao')?>',
            type: 'POST',
            dataType: 'json',
            data: $('#form_descricao').serialize(),
        })
        .done(function(data) {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
    }

    function session_cliente_inserir(id) {
        $.ajax({
            url: '<?= base_url("orcamento/ajax_session_cliente_inserir") ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {id: id}
        })
        .done(function (data) {
            console.log("success");
            $("#panel_cliente_id").val(data.cliente.id);
            $("#panel_cliente_id_onclick").attr('onclick', 'orcamento_cliente_editar('+data.cliente.id+')');
            $(".panel_cliente_nome").val(data.cliente.nome +" "+data.cliente.sobrenome);
            $(".panel_cliente_email").val(data.cliente.email);
            $(".panel_cliente_telefone").val(data.cliente.telefone);
            $(".panel_cliente_cpf").val(data.cliente.cpf);
            $("#md_clientes").modal('hide');
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log("error");
            $.alert({
                title: 'Aviso!',
                content: 'Não foi possível inserir o cliente no orçamento! Tente novamente.',
            });
        })
    }

    function session_assessor_inserir(id) {
        console.log('session_assessor_inserir()');
        $.ajax({
            url: '<?= base_url("orcamento/ajax_session_assessor/inserir") ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {id: id}
        })
        .done(function (data) {
            console.log("success");
            if (data.status) {
                $("#panel_assessor_id_onclick").attr('onclick', 'orcamento_assessor_editar('+data.assessor.id+')');
                $(".panel_assessor_nome").val(data.assessor.nome +" "+data.assessor.sobrenome);
                $(".panel_assessor_email").val(data.assessor.email);
                $(".panel_assessor_empresa").val(data.assessor.empresa);
                $(".panel_assessor_telefone").val(data.assessor.telefone);
                $("#md_assessores").modal('hide');
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log("error");
        })
    }

    function orcamento_desconto(acao, valor) {
        reset_errors();
        if (acao === 'inserir') {
            pre_submit("#form_desconto", "<?= base_url('orcamento/ajax_session_desconto/inserir') ?>", "#md_desconto");
            $("#desconto").val(valor);
            $("#md_desconto").modal();
        } else if (acao === 'editar') {
            pre_submit("#form_desconto", "<?= base_url('orcamento/ajax_session_desconto/editar') ?>", "#md_desconto");
            $("#desconto").val(valor);
            $("#md_desconto").modal();
        } else if (acao === 'excluir') {
            main_excluir("<?= base_url('orcamento/ajax_session_desconto/excluir') ?>");
        }
    }

    function orcamento_modal() {
        $.confirm({
            title: 'Confirmação!',
            content: 'Deseja iniciar um novo orçamento?',
            confirm: function () {
                call_loadingModal('Criando um novo orcamento...');
                $.ajax({
                    url: '<?= base_url('orcamento/ajax_session_orcamento_novo') ?>',
                    type: 'POST',
                    dataType: 'json',
                })
                .done(function (data) {
                    console.log("success");
                    if (data.status) {
                        clear_all_forms();
                        reload_table(true);
                        session_orcamento_info();
                    }
                })
                .fail(function () {
                    console.log("error");
                })
                .always(function() {
                    apply_this_document_ready();
                });
            },
            cancel: function () {
            }
        });
    }

    function produto_modal(acao, posicao = "", id = "", nome = "", quantidade = "", descricao = "", id_categoria = "") {
        if (acao === "inserir") {
            ajax_carregar_categoria();
            reset_form("#form_produto");
            $('#produto').selectpicker('val', '');
            pre_submit("#form_produto", "<?= base_url('produto/session_produto_inserir') ?>", "#md_produto");
        } else if (acao === "editar") {
            pre_submit("#form_produto", 'produto/session_produto_editar/' + posicao, "#md_produto");
            $('#produto').selectpicker('val', id);
            $("#quantidade_produto").val(quantidade);
            $("#descricao_produto").val(descricao);
            ajax_carregar_categoria(true,id_categoria);
            ajax_carregar_produto(id_categoria,editar = true, id)
        } else {
            console.log('error:não foi passado uma ação no produto_modal()');
        }
        $("#md_produto").modal();
    }

    function ajax_carregar_categoria(editar = false,id_categoria = null) {
        $('#produto_categoria')
        .find('option')
        .remove()
        .end()
        .append('<option value="">Selecione</option>')
        .val('');
        $('#produto').selectpicker('val', '');

        $.ajax({
            url: '<?= base_url("produto_categoria/ajax_get_personalizado")?>',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                $('#produto_categoria').append($('<option>', {
                    value: val.id,
                    text: val.nome
                }));
            });
        })
        .fail(function() {
            console.log("erro ao ajax_carregar_categoria");
        })
        .always(function() {
            $('#produto_categoria').selectpicker('refresh');
            if(editar){
                $('#produto_categoria').selectpicker('val', id_categoria);
            }
        });
    }

    $("#produto_categoria").change(function(event) {
        var option = $(this).find('option:selected');
        var id_categoria = option.val();
        ajax_carregar_produto(id_categoria);
    });

    function ajax_carregar_produto(id_categoria,editar = false, id_produto = null) {
        $('#produto')
        .find('option')
        .remove()
        .end()
        .append('<option value="">Selecione</option>')
        .val('');
        $.ajax({
            url: '<?= base_url("produto/ajax_get_personalizado/")?>'+id_categoria,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                $('#produto').append($('<option>', {
                    value: val.id,
                    text: val.nome
                }).attr('data-subtext', '( mínimo de ' + val.qtd_minima + ' )'));
            });
        })
        .fail(function() {
            console.log("erro ao ajax_carregar_produto");
        })
        .always(function() {
            $('#produto').selectpicker('refresh');
            if(editar){
                $('#produto').selectpicker('val', id_produto);
            }
        });
    }

    function produto_excluir_posicao(posicao) {
        main_excluir('produto/session_produto_excluir/' + posicao);
    }

    function convite_excluir_posicao(posicao) {
        main_excluir('convite/session_orcamento_convite_excluir/' + posicao);
    }

    function personalizado_excluir_posicao(posicao) {
        main_excluir('personalizado/session_orcamento_personalizado_excluir/' + posicao);
    }

    function delivery_date(owner, form) {
        if (owner === "convite") {
            set_date_delivery('<?= base_url('pedido/ajax_set_date_delivery/convite') ?>', form);
        } else if (owner === "personalizado") {
            set_date_delivery('<?= base_url('pedido/ajax_set_date_delivery/personalizado') ?>', form);
        } else if (owner === "produto") {
            set_date_delivery('<?= base_url('pedido/ajax_set_date_delivery/produto') ?>', form);
        }
    }

    function set_date_delivery(url, form) {
        reset_errors();
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $(form).serialize(),
        })
        .done(function (data) {
            console.log("success");
            $.map(data.form_validation, function (value, index) {
                $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
            });
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
    }

    function criar_orcamento() {
        criarPedido = false;
        $.confirm({
            title: 'Criar orçamento',
            content: 'Deseja realmente criar um orçamento?',
            confirm: function(){
                call_loadingModal('Preparando para salvar...');
                is_editing_container_itens();
            },
            cancel: function(){
            }
        });
    }

    function criar_pedido() {
        criarPedido = true;
        $.confirm({
            title: 'Criar pedido',
            content: 'Deseja realmente criar um pedido?',
            confirm: function(){
                console.log('criar_pedido()');
                //$('.data_entrega').show();
                $.ajax({
                    url: '<?= base_url('pedido/ajax_is_set_delivery_date') ?>',
                    type: 'POST',
                    dataType: 'json'
                })
                .done(function (data) {
                    console.log("success");
                    if (data.status) {
                        $('.data_entrega').show();
                        $.alert({
                            title: "Data de entrega",
                            content: "Defina as datas de entrega para cada produto"
                        });
                    } else {
                        $('#md_forma_pagamento').modal('show');
                    }
                })
                .fail(function () {
                    console.log("error");
                })
            },
            cancel: function(){
            }
        });
    }

    function get_quantidade_parcelas() {
        ajax_get_parcelas_pedido();
    }

    function finalizar_pedido(event) {
        event.preventDefault();
        if($("#form_forma_pagamento #forma_pagamento").val() == ""){
            $("#form_forma_pagamento #forma_pagamento").focus();
            mapear_erro($("#form_forma_pagamento #forma_pagamento"),"O campo Forma de pagamento é obrigatório.");
            return;
        }
        if($("#form_forma_pagamento #qtd_parcelas").val() == ""){
            $("#form_forma_pagamento #qtd_parcelas").focus();
            mapear_erro($("#form_forma_pagamento #qtd_parcelas"),"O campo Quantidade de parcelas é obrigatório.");
            return;
        }
        if($("#form_forma_pagamento #primeiro_vencimento").val() == ""){
            $("#form_forma_pagamento #primeiro_vencimento").focus();
            mapear_erro($("#form_forma_pagamento #primeiro_vencimento"),"O campo 1º Vencimento é obrigatório.");
            return;
        }
        if( !date_before_today( $("#form_forma_pagamento #primeiro_vencimento").val() ) ) {
            $("#form_forma_pagamento #primeiro_vencimento").focus();
            mapear_erro($('#form_forma_pagamento #primeiro_vencimento'),'A data é anterior a data de hoje ' + get_data_hoje('dd/mm/yyyy'));
            return;
        }
        if($("#form_forma_pagamento #qtd_parcelas").val() > 1){
            if($("#form_forma_pagamento #vencimento_dia").val() == ""){
                $("#form_forma_pagamento #vencimento_dia").focus();
                mapear_erro($("#form_forma_pagamento #vencimento_dia"),"O campo Quantidade de parcelas é obrigatório.");
                return;
            }
        }
        reset_errors();
        disable_button_salvar();
        $.ajax({
            url: '<?= base_url('pedido/ajax_forma_pagamento') ?>',
            type: 'POST',
            dataType: 'json',
            data: $('#form_forma_pagamento').serialize(),
        })
        .done(function (data) {
            console.log("success: finalizar_pedido()");
            if (data.status) {
                $('#md_forma_pagamento').modal('hide');
                console.log("Preparando para criar pedido");
                console.log("Verificando se há algo em edição...");
                call_loadingModal('Preparando para criar o pedido...');
                is_editing_container_itens();
            } else {
                $.map(data.form_validation, function (value, index) {
                    $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                    $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                });
            }
        })
        .fail(function () {
            console.log("error: finalizar_pedido()");
        })
        .always(function () {
            console.log("complete: finalizar_pedido()");
            enable_button_salvar();
        });
    }

    function is_set_delivery_date() {
        $.ajax({
            url: '<?= base_url('pedido/ajax_is_set_delivery_date') ?>',
            type: 'POST',
            dataType: 'json'
        })
        .done(function (data) {
            console.log("success");
            if (data.status && !data.date_found) {
                $('.data_entrega').hide();
            } else {
                $('.data_entrega').show();
            }
        })
        .fail(function () {
            console.log("error");
        })
    }

    function is_editing_container_itens() {
        $.ajax({
            url: '<?= base_url('orcamento/ajax_is_editing_container_itens') ?>',
            type: 'POST',
            dataType: 'json',
        })
        .done(function (data) {
            var itens = "";
            if (data.status) {
                close_loadingModal();
                $.each(data.location, function (index, location) {
                    itens += '<li><strong><a href="' + data.url[index] + '">' + location + '</a></strong></li> ';
                    console.log("success: Editando: " + location);
                });
                $.confirm({
                    title: 'Confirmação!',
                    content: 'Em edição: <ul>' + itens + '</ul> <p>Deseja salvar mesmo assim?</p>',
                    confirm: function () {
                        call_loadingModal('Preparando para salvar...');
                        is_empty_orcamento_info();
                    },
                    cancel: function () {
                        $.alert('Operação cancelada!');
                    }
                });
            } else {
                console.log("success");
                is_empty_orcamento_info();
            }
        })
        .fail(function () {
            console.log("error:orcamento/ajax_is_editing_container_itens");
        })
        .always(function () {
            console.log("complete");
        });
    }

    function is_empty_orcamento_info(salvar = true) {
        if(salvar){
            $.ajax({
                url: '<?= base_url('orcamento/ajax_is_empty_orcamento_info') ?>',
                type: 'POST',
                dataType: 'JSON',
            })
            .done(function (data) {
                console.log("success");
                if (data.status) {
                    is_empty_orcamento_itens();
                } else {
                    close_loadingModal();
                    show_orcamento_info_error(data);
                }
            })
            .fail(function () {
                console.log("error");
            })
        }else{
            if($("#loja").val() == "" || $("#evento").val() == "" || $("#data_evento").val() == ""){
                $("#md_orcamento_info").modal();
            }
        }
    }

    function show_orcamento_info_error(data) {
        var itens = "";
        close_loadingModal();
        $.each(data.location, function (index, location) {
            if (location === 'data_evento') {
                location = 'data do evento'
            }
            itens += '<li><strong>' + location + '</strong> não foi definido!</li> ';
            console.log("success: Editando: " + location);
        });
        $.confirm({
            icon: 'glyphicon glyphicon-info-sign',
            title: 'Informações do orçamento',
            content: '<ul>' + itens + '</ul><p>Clique em Info para definir estas informações</p>',
            confirmButton: 'Info',
            cancelButton: 'Cancelar',
            confirm: function () {
                session_orcamento_info();
            },
            cancel: function () {
                $.alert({
                    title: 'Cancelado',
                    content: 'Operação cancelada!',
                });
            }
        });
    }

    function is_empty_orcamento_itens() {
        console.log("Função: is_empty_orcamento_itens");
        //console.log("Verificando condições para salvar...");
        //call_loadingModal('Preparando para salvar');
        $.ajax({
            url: '<?= base_url('orcamento/ajax_is_empty_orcamento_itens') ?>',
            type: 'POST',
            dataType: 'json',
        })
        .done(function (data) {
            console.log("success: orcamento/ajax_is_empty_orcamento_itens");
            if (data.status) {
                is_empty_orcamento_cliente();
            } else {
                console.log(data.msg);
                close_loadingModal();
                $.alert({
                    title: 'Orçamento',
                    content: 'Não constam produtos neste orçamento. Insira pelo menos um produto antes salvar.',
                });
            }
        })
        .fail(function () {
            console.log("error: orcamento/is_empty_orcamento_itens");
        })
        .always(function () {
            console.log("complete:orcamento/is_empty_orcamento_itens");
        });
    }

    function is_empty_orcamento_cliente(save = true) {
        var is_criar_pedido = null;
        if (criarPedido) {
            is_criar_pedido = 'true';
        }
        $.ajax({
            url: '<?= base_url('orcamento/ajax_is_empty_orcamento_cliente') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {is_criar_pedido: is_criar_pedido},
        })
        .done(function (data) {
            console.log("success");
            if (data.status) {
                if (save) {
                    call_loadingModal('Salvando...');
                    salvar();
                }
            } else {
                close_loadingModal();
                //Abre o modal do formulário do cliente com o ID
                form_crud = '#form_cliente';
                url_crud = '<?= base_url('cliente/ajax_update') ?>';
                md_tb_crud = '#md_clientes';
                md_form_crud = '#md_form_cliente';
                owner_crud = 'cliente';
                url_edit_id = "<?= base_url('cliente/ajax_edit/') ?>" + data.cliente_id;
                editar(data.cliente_id, md_tb_crud, md_form_crud);
                $.alert({
                    title: 'Cliente!',
                    content: data.msg
                });
                if(data.cliente_pessoa_tipo == 'fisica'){
                    setTimeout(function() {
                        $("#form_cliente ul li a[href='#fisica']").tab('show');
                        $('#form_cliente #cpf').focus();
                    }, 2000);
                }else if(data.cliente_pessoa_tipo == 'juridica'){
                    setTimeout(function() {
                        $("#form_cliente ul li a[href='#juridica']").tab('show');
                        $('#form_cliente #cnpj').focus();
                    }, 2000);
                    console.log(data.cliente_pessoa_tipo);
                }
            }
        })
        .fail(function () {
            console.log("error");
        })
    }

    function is_empty_orcamento_assessor() {
        //Função desativada
        $.ajax({
            url: '<?= base_url('orcamento/ajax_is_empty_orcamento_assessor') ?>',
            type: 'POST',
            dataType: 'JSON',
        })
        .done(function (data) {
            if (data.status) {
                salvar();
            } else {
                //close_loadingModal();
                $.confirm({
                    title: "Assessor",
                    content: "Deseja adicionar um assessor?",
                    confirmButton: 'Sim',
                    cancelButton: 'Não',
                    confirm: function () {
                        $('#md_assessores').modal();
                    },
                    cancel: function () {
                        //call_loadingModal('Salvando...');
                        //salvar();
                    }
                });
            }
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
    }

    function salvar() {
        if (criarPedido) {
            url = '<?= base_url('pedido/ajax_salvar') ?>';
        } else {
            url = '<?= base_url('orcamento/ajax_salvar') ?>';
        }
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
        })
        .done(function (data) {
            console.log("success");
            close_loadingModal();
            if (data.status) {
                call_loadingModal("Finalizando...");
                clean_session_orcamento(data.id);
            } else {
                $.confirm({
                    title: "Ops",
                    content: "Não foi possível salvar o orçamento. <p>Deseja tentar salvar novamente?</p>",
                    confirmButton: 'Sim',
                    cancelButton: 'Não',
                    confirm: function () {
                        salvar();
                    },
                    cancel: function () {
                        $.alert({
                            title: 'Cancelado!',
                            content: 'Operação cancelada.'
                        });
                    }
                });
            }
        })
        .fail(function () {
            close_loadingModal();
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
    }

    function clean_session_orcamento(id) {
        //newWindow = window.open();
        //Limpa o formulário
        // $.each($('form'), function (index, value) {
        //     value.reset();
        // });
        $.ajax({
            url: '<?= base_url('orcamento/ajax_clean_session_orcamento') ?>',
            type: 'POST',
            dataType: 'json'
        })
        .done(function (data) {
            close_loadingModal();
            console.log("success");
            if (data.status) {
                if (criarPedido) {
                    //newWindow.location = '<?= base_url('pedido/pdf/') ?>'+data.id, '_blank';
                    url = '<?= base_url('pedido/pdf/') ?>' + id;
                    item = "Pedido";
                } else {
                    //newWindow.location = '<?= base_url('orcamento/pdf/') ?>'+id, '_blank';
                    url = '<?= base_url('orcamento/pdf/') ?>' + id;
                    item = "Orçamento";
                }
                $.alert({
                    title: item + " N° " + id,
                    content: "<p>Seu " + item + " foi criado com sucesso!</p>",
                    confirmButton: 'PDF',
                    confirm: function () {
                        window.open(url, '_blank');
                    },
                });
                reload_table(true);
                clear_all_forms();
            }
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            criarPedido = false;
            close_loadingModal();
            apply_this_document_ready();
            console.log("complete");
        });
    }

    function pre_submit(form, url, modal) {
        console.log('pre_submit(form, url, modal)');
        form_ajax = form;
        url_ajax = url;
        modal_ajax = modal;
    }

    //Adiciona ou Edita produto
    $(".form_ajax").submit(function (event) {
        event.preventDefault();
        disable_button_salvar();
        reset_errors();
        var form = form_ajax;
        var url = url_ajax;
        var modal = modal_ajax;
        $.ajax({
            url: url,
            type: "POST",
            data: $(form).serialize(),
            dataType: "JSON",
        }).done(function (data) {
            console.log('success');
            if (data.status) {
                if(modal != ''){
                    $(modal).modal('hide');
                }
                reload_table();
            } else {
                $.map(data.form_validation, function (value, index) {
                    $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                    $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                });
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log('error');
            $.alert({
                title: 'Atenção!',
                content: 'Não foi possível Adicionar ou Editar! Tente novamente.',
            });
        })
        .always(function () {
           enable_button_salvar();
           apply_this_document_ready();
       });
    });

    $("#form_produto").submit(function (event) {
        event.preventDefault();
        if($("#form_produto #produto_categoria").val() == ""){
            $("#form_produto #produto_categoria").focus();
            mapear_erro($("#form_produto #produto_categoria"),"O campo Categoria é obrigatório.");
            return;
        }
        if($("#form_produto #produto").val() == ""){
            $("#form_produto #produto").focus();
            mapear_erro($("#form_produto #produto"),"O campo Produto é obrigatório.");
            return;
        }
        if($("#form_produto #quantidade_produto").val() == ""){
            $("#form_produto #quantidade_produto").focus();
            mapear_erro($("#form_produto #quantidade_produto"),"O campo Quantidade é obrigatório.");
            return;
        }
        if($("#form_produto #quantidade_produto").val() <= 0){
            $("#form_produto #quantidade_produto").focus();
            mapear_erro($("#form_produto #quantidade_produto"),"O campo Quantidade não pode ser zero ou menor que zero.");
            return;
        }
        disable_button_salvar();
        reset_errors();
        var form = form_ajax;
        var url = url_ajax;
        var modal = modal_ajax;
        $.ajax({
            url: url,
            type: "POST",
            data: $(form).serialize(),
            dataType: "JSON",
        }).done(function (data) {
            console.log('success');
            if (data.status) {
                if(modal != ''){
                    $(modal).modal('hide');
                }
                reload_table();
            } else {
                $.map(data.form_validation, function (value, index) {
                    $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                    $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                });
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log('error');
            $.alert({
                title: 'Atenção!',
                content: 'Não foi possível Adicionar ou Editar! Tente novamente.',
            });
        })
        .always(function () {
           enable_button_salvar();
           apply_this_document_ready();
       });
    });

    $("#form_desconto").submit(function (event) {
        event.preventDefault();
        if($("#form_desconto #desconto").val() != ""){
            if($("#form_desconto #desconto").val() < 0){
                $("#form_desconto #desconto").focus();
                mapear_erro($("#form_desconto #desconto"),"O Desconto não pode ser um valor negativo!");
                return;
            }
            if(!$.isNumeric($("#form_desconto #desconto").val())){
                $("#form_desconto #desconto").focus();
                mapear_erro($("#form_desconto #desconto"),"O campo Desconto deve conter apenas números.");
                return;
            }
        }
        disable_button_salvar();
        reset_errors();
        var form = form_ajax;
        var url = url_ajax;
        var modal = modal_ajax;
        $.ajax({
            url: url,
            type: "POST",
            data: $(form).serialize(),
            dataType: "JSON",
        }).done(function (data) {
            console.log('success');
            if (data.status) {
                $(modal).modal('hide');
                reload_table();
                apply_this_document_ready();
            } else {
                $.map(data.form_validation, function (value, index) {
                    $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                    $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                });
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log('error');
            $.alert({
                title: 'Atenção!',
                content: 'Não foi possível Adicionar ou Editar! Tente novamente.',
            });
        })
        .always(function () {
           enable_button_salvar();
       });
    });

    $("#form_orcamento_info").submit(function (event) {
        event.preventDefault();
        if($('#form_orcamento_info #loja').val() == ""){
            $('#form_orcamento_info #loja').focus();
            mapear_erro($('#form_orcamento_info #loja'),'O campo Loja é obrigatório.');
            return;
        }
        if($('#form_orcamento_info #evento').val() == ""){
            $('#form_orcamento_info #evento').focus();
            mapear_erro($('#form_orcamento_info #evento'),'O campo Evento é obrigatório.');
            return;
        }
        if($('#form_orcamento_info #data_evento').val() == ""){
            $('#form_orcamento_info #data_evento').focus()
            mapear_erro($('#form_orcamento_info #data_evento'),'O campo Data Evento é obrigatório.');
            return;
        }
        if( !date_before_today( $('#form_orcamento_info #data_evento').val() ) ) {
            $('#form_orcamento_info #data_evento').focus();
            mapear_erro($('#form_orcamento_info #data_evento'),'A data é anterior a data de hoje ' + get_data_hoje('dd/mm/yyyy'));
            return;
        }
        if($("#form_orcamento_info #panel_cliente_id").val() == ""){
            orcamento_cliente();
            return;
        }
        disable_button_salvar();
        reset_errors();
        var form = form_ajax;
        var url = url_ajax;
        var modal = modal_ajax;
        $.ajax({
            url: url,
            type: "POST",
            data: $(form).serialize(),
            dataType: "JSON",
        }).done(function (data) {
            console.log('success');
            if (data.status) {
                $(modal).modal('hide');
            } else {
                $.map(data.form_validation, function (value, index) {
                    $('[name="' + index + '"]').closest(".form-group").addClass('has-error');
                    $('[name="' + index + '"]').closest(".form-group").find('.help-block').text(value);
                });
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log('error');
            $.alert({
                title: 'Atenção!',
                content: 'Não foi possível Adicionar ou Editar! Tente novamente.',
            });
        })
        .always(function () {
           enable_button_salvar();
       });
    });

    function required_alert(value) {
        $.alert({
            title: "Atenção!",
            content: "Nenhum(a) " + value + " foi definido(a) para este orçamento."
        });
    }

    function main_excluir(url) {
        $.confirm({
            title: 'Confirmação',
            content: 'Deseja realmente excluir?',
            confirm: function () {
                call_loadingModal();
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                })
                .done(function (data) {
                    console.log("success");
                })
                .fail(function () {
                    console.log("error");
                })
                .always(function (data) {
                    console.log("complete");
                    reload_table();
                    apply_this_document_ready();
                });
            },
            cancel: function () {
                $.alert({
                    title: '',
                    content: 'A operação foi cancelada!',
                });
            }
        });
    }

    function reload_table(orcamento_info = false) {
        $.ajax({
            url: '<?= base_url('orcamento') ?>',
            type: 'POST',
            dataType: 'html',
        })
        .done(function (data) {
            console.log("success");
            $('#painel_principal').html($('#painel_principal', data).html());
        })
        .fail(function () {
            console.log("error");
        })
        .always(function (data) {
            console.log("complete");
            close_loadingModal();
            if(orcamento_info){
                $('#orcamento_info').html($('#orcamento_info', data).html());
            }
            apply_this_document_ready();
        });
    }

    function call_loadingModal(msg = "") {
        if (msg === "") {
            msg = "Processando os dados..."
        }
        $('body').loadingModal({
            position: 'auto',
            text: msg,
            color: '#fff',
            opacity: '0.7',
            backgroundColor: 'rgb(0,0,0)',
            animation: 'threeBounce'
        });
    }

    function close_loadingModal() {
        // hide the loading modal
        $('body').loadingModal('hide');
        // destroy the plugin
        $('body').loadingModal('destroy');
    }

    function clear_all_forms() {

        $('form').each(function () {
            this.reset()
        });
        $("#loja").val("")
        $("#evento").val("")
        $("#data_evento").val("")
    }

    function reset_form(form) {
        $(form)[0].reset();
        reset_errors();
    }

    function ajax_get_parcelas_pedido() {
        var parcelamento_maximo = $("#forma_pagamento").find(':selected').data('parcelamento_maximo');
        var valor_minimo = $("#forma_pagamento").find(':selected').data('valor_minimo');
        var qtd_parcelas = $("#qtd_parcelas option:selected").val();
        $.ajax({
            url: '<?= base_url("pedido/ajax_get_parcelas_pedido") ?>',
            type: 'GET',
            dataType: 'JSON',
            data: {parcelamento_maximo:parcelamento_maximo,valor_minimo:valor_minimo}
        })
        .done(function (data) {
            //Limpa o select antes de inserir os novos options
            $('#qtd_parcelas').find('option').remove().end().append('<option value="" selected>Selecione</option>');
            $.each(data, function (i, item) {
                $("#qtd_parcelas").append($('<option>', {
                    value: item.value,
                    text: item.text
                }));
                if(item.value == qtd_parcelas){
                    $("#qtd_parcelas option[value="+qtd_parcelas+"]").prop("selected","selected");
                }
            });
        })
        .fail(function () {
            return null;
            console.log("Erro ao buscar o valor do pedido");
        })
        .always(function() {
            console.log("complete");
        });
    }

    function reload_table_cliente() {

        tabela_cliente.ajax.reload(null, false);
    }

    function reload_table_assessor() {

        tabela_assessor.ajax.reload(null, false);
    }
</script>