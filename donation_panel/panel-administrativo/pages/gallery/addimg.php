<section class="content-header">
	<h1>
		Añadir Imagen
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-image"></i> Galeria</li>
		<li class="active">Añadir Imagen</li>
	</ol>
</section>

<section class="content">
	
	<div class="box box-primary">
		
		<form class='atualstudio' method='POST' action='./?engine=add&module=gallery' enctype='multipart/form-data'>
			
			<div class="box-body">
				
				Instrucciones y restricciones:<br />
				<ul>
					<li>Seleccione en su computadora las imágenes que desea enviar. Es posible seleccionar varias al mismo tiempo!</li>
					<li>Formatos permitidos: <b>JPG, JPEG, PNG y GIF</b>.</li>
					<li>Se recomienda no seleccionar imágenes más grandes que <b>4 MB</b>.</li>
					<li>Si la imagen tiene dimensiones demasiado grandes, se cambia el tamaño de la imagen <b>1600 pixels</b> de altura y anchura.</li>
				</ul>
				
				<div style='display:table; width: 100%; padding: 10px; box-sizing: border-box;'>
					<iframe scrolling="no" marginwidth="0" marginheight="0" allowtransparency="true" src="layout/plugins/jQueryFileUpload/index.php" frameborder="0" style="height:500px;width: 100%;"></iframe>
				</div>
				
			</div>
			
		</form>
		
	</div>
	
</section>
