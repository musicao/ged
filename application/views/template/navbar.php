 

<div id='cssmenu'  >
    <ul>
        <li><a href='<?= base_url('Sistema/inicio'); ?>'>Início</a></li>


        <li><a href='#'><span class="glyphicon glyphicon-sort"></span> Retirada</a></li>
        <li><a href='<?= base_url('Produto/listar'); ?>'><span class="glyphicon glyphicon-barcode"></span> Produtos</a></li>
        <li><a href='<?= base_url('Voluntario/listar'); ?>'><span class="glyphicon glyphicon-globe"></span> Voluntários</a></li>
        <li><a href='<?= base_url('usuario/listar'); ?>'><span class="glyphicon glyphicon-user"></span> Usuários</a></li>
        <li><a href='<?= base_url('estoque/listar'); ?>'><span class="glyphicon glyphicon-equalizer"></span> Estoque</a></li>



        <li class='  has-sub'><a href='#'><span class="glyphicon glyphicon-signal"></span> Relatório</a>
            <ul>
                <li><a href='#'>Estoque</a></li>
                <li><a href='#'>Retiradas</a></li>
            </ul>
        </li>
        <!--   <li class='active has-sub'><a href='#'>Cadastrar</a>
              <ul>
                 <li class='has-sub'><a href='#'>Produtos</a>
                    <ul>
                       <li><a href='#'>Sub Product</a></li>
                       <li><a href='#'>Sub Product</a></li>
                    </ul>
                 </li>
                 <li class='has-sub'><a href='#'>Product 2</a>
                    <ul>
                       <li><a href='#'>Sub Product</a></li>
                       <li><a href='#'>Sub Product</a></li>
                    </ul>
                 </li>
              </ul>
           </li>-->
        <li> <div id="divBusca">
                <form> 
                    <input type="text" id="txtBusca" placeholder="Nome ou CPF"/>
                    <button type="submit"> 
                        <img src="<?= base_url('assets/img/search3.png'); ?>" id="btnBusca" alt="Buscar"/>
                    </button>
                </form>
            </div>
        <li></li>
        <li></li>
        <li><a href="<?= base_url('login/logout') ?>"><span class="glyphicon glyphicon-off"></span> Sair</a></li>

    </ul>

</div> 


