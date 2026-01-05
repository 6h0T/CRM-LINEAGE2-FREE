<?php

$cat = !empty($_GET['cat']) ? intval($_GET['cat']) : 0;

require('private/classes/classShop.php');

$cons = Shop::findCat($cat);
if(count($cons) == 0){
	fim('Categoría inexistente!');
}

?>

<section class="content-header">
	<h1>
		Alterar Categoría
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-shopping-cart"></i> Shop</li>
		<li class="active">Alterar Categoría</li>
	</ol>
</section>

<section class="content">
	<div class="box box-primary">
	
		<form class='atualstudio usarJquery' method='POST' action='./?engine=cat_change&module=shop&cat=<?php echo $cat; ?>'>
			<div class="box-body">
	
				<div class='form-group'>
					<label>
						<div class='desc'>Nombre</div>
						<input type='text' name='nome' maxlength='70' class='form-control' value='<?php echo $cons[0]['nome']; ?>' />
					</label>
				</div>
	
				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Alterar Categoría' />
					<div style='float:right;'>
						<a href='javascript:history.back();' class='btn btn-default'>&laquo; Volver</a>
						<a href='?page=pack_list&module=shop&cat=<?php echo $cat; ?>&catnome=<?php echo base64_encode($cons[0]['nome']); ?>' class='btn btn-default'>Visualizar Paquetes</a>
						<a href='?page=cat_delete&module=shop&cat=<?php echo $cat; ?>' title='Borrar' class='btn btn-danger'>Borrar</a>
					</div>
				</div>
			</div>
		</form>
		
	</div>
</section>
