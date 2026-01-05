<?php

$gid = !empty($_GET['gid']) ? intval($_GET['gid']) : 0;

require('private/classes/classGallery.php');

$findGallery = Gallery::findGallery($gid);
if(count($findGallery) == 0){
	fim('Item inexistente na galeria!');
}
?>

<section class="content-header">
	<h1>
		Borrar <?php echo ($findGallery[0]['isvideo'] == '1' ? 'Vídeo' : 'Imagen'); ?>
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-image"></i> Galeria</li>
		<li class="active">Borrar <?php echo ($findGallery[0]['isvideo'] == '1' ? 'Vídeo' : 'Imagen'); ?></li>
	</ol>
</section>

<section class="content">
	<div class="box">
		
		<div class="box-body">
				
			¿Está seguro de que desea eliminar este elemento de la galería?<br />
			
			<div style='padding: 10px;'>
				<?php if($findGallery[0]['isvideo'] != '1') { ?>
					<a href='<?php echo '../'.$dir_gallery.trim($findGallery[0]['url']); ?>' target='_blank'><img style='max-width:400px;' src='<?php echo '../'.$dir_gallery.trim($findGallery[0]['url']); ?>' /></a>
				<?php } else { ?>
					<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $findGallery[0]['url']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
				<?php } ?>
			</div>
			
			<br />
			<a class='btn btn-info' href='javascript:history.back();'>&laquo; Volver</a>
			<a class='btn btn-danger usarJquery' href='./?engine=delete&module=gallery&gid=<?php echo $gid; ?>'>Borrar</a>

		</div>
		
	</div>
</section>
