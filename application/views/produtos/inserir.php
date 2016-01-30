<div class="container">
    <form role="form" method="POST" action="<?= base_url('produto/cadastrar') ?>">
        <fieldset>

            <legend>Cadastro de Produtos</legend>


            <div class="row">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group">
                        <label for = "nomeProduto">Nome do Produto</label>
                        <input id = "nomeProduto" name = "nomeProduto" class = "form-control "  required type = "text" value = "<?= set_value('nomeProduto') ?>">
                        <?= form_error('nomeProduto');
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group">
                        <label for = "minimo">Quantidade Mínimo </label>
                        <input id = "minimo" name = "minimo" class = "form-control" required type = "text" value = "<?= set_value('minimo') ?>">
                        <?= form_error('minimo'); ?>
                    </div>
                    
            
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group">
                        <label for = "maximo">Quantidade Máximo </label>
                        <input id = "maximo" name = "maximo" class = "form-control" required type = "text" value = "<?= set_value('maximo') ?>">
                        <?= form_error('maximo'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group">
                        <label for = "obs">Observações: </label>
                        <textarea  rows="3"  id="obs" name = "obs" class = "form-control"  title = "Preenchimento opicional" x-moz-errormessage = "Preenchimento opicional" ><?= set_value('obs') ?> </textarea>
                        <?= form_error('obs');
                        ?>
                    </div>
                </div>
            </div>



            <div class="pull-right">
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> GRAVAR</button>
                <a href="<?= base_url('produto/listar') ?>" class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> CANCELAR</a>

            </div>
        </fieldset>

    </form>



</div>