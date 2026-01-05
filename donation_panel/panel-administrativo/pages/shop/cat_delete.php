<?php
$cat = !empty($_GET['cat']) ? intval($_GET['cat']) : '';

require('private/classes/classShop.php');

$consulta = Shop::findCat($cat);
if(count($consulta) == 0){
	fim('Categoría inexistente!');
}

$otherCats = Shop::listCats();

?>

<section class="content-header">
	<h1>
		Borrar Categoría
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-shopping-cart"></i> Shop</li>
		<li class="active">Borrar Categoría</li>
	</ol>
</section>

<section class="content">
	<div class="box">
		
		<div class="box-body">
				
			Usted está seguro de que desea excluir a la categoría "<b><?php echo $consulta[0]['nome']; ?></b>"?<br />
			
			<?php if(count($otherCats) > 1) { ?>
			
			Si sí, ¿a dónde van los Paquetes registrados en esta categoría?<br /><br />
			
			<form role="form" class='atualstudio usarJquery' id='formBorrar' action='?engine=cat_delete&module=shop&cat=<?php echo $cat; ?>' method='POST'>
				<div class='form-group'>
					<label>
						<div class='desc'>Seleccione la nueva categoría:</div>
						<select name='newCat' class='form-control select2'>
							<?php
							for($i=0, $c=count($otherCats); $i < $c; $i++) {
								if($otherCats[$i]['scat_id'] != $cat) {
									echo '<option value="'.$otherCats[$i]['scat_id'].'">'.$otherCats[$i]['nome'].'</option>';
								}
							}
							?>
						</select>
					</label>
				</div>
			</form>
			
			<a class='btn btn-info' href='javascript:history.back();'>&laquo; Volver</a>
			<a class='btn btn-danger formBorrar' href='#'>Borrar y mover paquetes para nueva categoría</a>
			
			<br />
			
			<?php } ?>

			<br />
			
			O desea excluir a categoría "<b><?php echo $consulta[0]['nome']; ?></b>" permanentemente, incluyendo sus paquetes y itens?
			
			<br /><br />
			<a class='btn btn-info' href='javascript:history.back();'>&laquo; Volver</a>
			<a class='btn btn-danger usarJquery' href='./?engine=cat_delete_brute&module=shop&cat=<?php echo $cat; ?>'>Borrar permanentemente</a>

		</div>
		
	</div>
</section>
