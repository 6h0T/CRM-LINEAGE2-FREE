<section class="content-header">
	<h1>
		Crear Paquete
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-shopping-cart"></i> Shop</li>
		<li class="active">Crear Paquete</li>
	</ol>
</section>

<section class="content">

	<div class="box box-primary">

		<form class='atualstudio' method='POST' action='./?engine=pack_add&module=shop' enctype='multipart/form-data'>
			<div class="box-body">

				<div class='form-group'>
					<label>
						<div class='desc'>Nombre</div>
						<input type='text' name='nome' maxlength='70' class='form-control' />
					</label>
				</div>

				<div class='form-group'>
					<label>
						<div class='desc'>Categoría</div>
						<select name='scat_id' class='form-control select2'>
							<?php
							require('private/classes/classShop.php');
							$consulta = Shop::listCats();
							for($i=0, $c=count($consulta); $i < $c; $i++) {
								echo "<option value='".$consulta[$i]['scat_id']."'>".$consulta[$i]['nome']."</option>";
							}
							?>
						</select>
					</label>
				</div>

				<div class='form-group'>
					<label>
						<div class='desc'>Imagen</div>
						<input style="background:#fff; border:0;" type="file" name="imagem" maxlength='40'/>
					</label>
				</div>

				<div class='form-group'>
					<label>
						<div class='desc'>Valor (<?php echo "em ".strtolower($coinName)."'s"; ?>)</div>
						<input type='text' name='valor' maxlength='11' class='form-control' value='0' />
					</label>
					<p class='help-block' style='font-size: 13px;'>Si introduce un valor, el usuario podrá comprar todos los itens del paquete de una sola vez por el valor informado. Deje "0" para cancelar esta posibilidad.</p>
				</div>
				
				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Crear Paquete' />
				</div>

			</div>
		</form>

	</div>

</section>
