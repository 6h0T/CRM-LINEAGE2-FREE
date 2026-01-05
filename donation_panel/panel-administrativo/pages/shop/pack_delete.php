<?php
$pack = !empty($_GET['pack']) ? intval($_GET['pack']) : '';

require('private/classes/classShop.php');

$consulta = Shop::findPack($pack);
if(count($consulta) == 0){
	fim('Paquete inexistente!');
}

$otherPacks = Shop::listPacks();

?>

<section class="content-header">
	<h1>
		Borrar Paquete
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-shopping-cart"></i> Shop</li>
		<li class="active">Borrar Paquete</li>
	</ol>
</section>

<section class="content">
	<div class="box">
		
		<div class="box-body">
				
			Usted está seguro de que desea eliminar el paquete "<b><?php echo $consulta[0]['nome']; ?></b>"?<br />
			
			<?php if(count($otherPacks) > 1) { ?>
			
			Si sí, ¿a dónde van los artículos registrados en este paquete?<br /><br />
			
			<form role="form" class='atualstudio usarJquery' id='formBorrar' action='?engine=pack_delete&module=shop&pack=<?php echo $pack; ?>' method='POST'>
				<div class='form-group'>
					<label>
						<div class='desc'>Seleccione el nuevo paquete:</div>
						<select name='newPack' class='form-control select2'>
							<?php
							for($i=0, $c=count($otherPacks); $i < $c; $i++) {
								if($otherPacks[$i]['spack_id'] != $pack) {
									echo '<option value="'.$otherPacks[$i]['spack_id'].'">'.$otherPacks[$i]['nome'].'</option>';
								}
							}
							?>
						</select>
					</label>
				</div>
			</form>
			
			<a class='btn btn-info' href='javascript:history.back();'>&laquo; Volver</a>
			<a class='btn btn-danger formBorrar' href='#'>Eliminar y mover itens a nuevo paquete</a>
			
			<br /><br />
			
			O desea eliminar el paquete "<b><?php echo $consulta[0]['nome']; ?></b>" permanentemente, incluyendo sus itens?
			
			<br />
			
			<?php } ?>

			<br />
			
			<a class='btn btn-info' href='javascript:history.back();'>&laquo; Volver</a>
			<a class='btn btn-danger usarJquery' href='./?engine=pack_delete_brute&module=shop&pack=<?php echo $pack; ?>'>Eliminar permanentemente</a>

		</div>
		
	</div>
</section>



