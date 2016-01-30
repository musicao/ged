<div class ="container">
    <?php
    if ($this->session->flashdata('erro')):
        ?>
        <div class="alert alert-danger text-center" role="alert" ng-show="mensagem"><?= $this->session->flashdata('erro'); ?></div>
        <?php
    elseif ($this->session->flashdata('logado')):
        ?>
        <div class="alert alert-warning text-center" role="alert"  ng-show="mensagem" ><?= $this->session->flashdata('logado'); ?></div>
        <?php
    endif;
    ?>

</div>