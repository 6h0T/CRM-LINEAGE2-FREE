<?php

$bid = !empty($_GET['bid']) ? intval($_GET['bid']) : 0;

require('private/classes/classBanners.php');

$findBanner = Banners::findBanner($bid);
if(count($findBanner) == 0){
	fim('Banner inexistente!');
}
?>

<!-- iCheck -->
<link rel="stylesheet" href="layout/plugins/iCheck/flat/blue.css">

<section class="content-header">
	<h1>
		Cambiar Banner
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-object-group"></i> Banners</li>
		<li class="active">Cambiar</li>
	</ol>
</section>

<section class="content">

	<div class="box box-primary">

		<form class='atualstudio' method='POST' action='./?engine=change&module=banners&bid=<?php echo $bid; ?>' enctype='multipart/form-data'>

			<div class="box-body">
				
				Instrucciones y restricciones:<br />
				<ul>
					<li>Formatos permitidos: <b>JPG, JPEG y PNG</b>.</li>
					<li>Se recomienda no seleccionar imágenes de más de <b>4 MB</b>.</li>
					<li>Independiente del tamaño de la imagen, se cambia el tamaño al formato de <b><?php echo $bnWidth.'x'.$bnHeight; ?></b> pixels.</li>
					<li>Seleccione el idioma y cargue su imagen. La bandera en portugués es obligatorio, otros son opcionales.</li>
					<li>Si no desea cambiar los banners actuales, deje los campos de upload vacíos.</li>
				</ul>
				
				<div style='display:table;width:100%;margin: 20px 0 0 0;'>
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_1" data-toggle="tab">Português</a></li>
							<li><a href="#tab_2" data-toggle="tab">English</a></li>
							<li><a href="#tab_3" data-toggle="tab">Español</a></li>
						</ul>
						<div class="tab-content">
							
							<div class="tab-pane active" id="tab_1">
								
								<div class='form-group'>
									<div>
										<div class='desc'>Imagen</div>
										<?php echo ((strlen(trim($findBanner[0]['imgurl_pt'])) > 0 && file_exists('../'.$dir_banners.trim($findBanner[0]['imgurl_pt']))) ? "<div style='padding:10px;'><a href='../".$dir_banners.trim($findBanner[0]['imgurl_pt'])."' target='_blank'><img width='300' src='../".$dir_banners.trim($findBanner[0]['imgurl_pt'])."' /></a></div>" : ""); ?>
										<input type='file' name='img_pt' />
									</div>
								</div>
								
							</div>
							
							<div class="tab-pane" id="tab_2">
								
								<div class='form-group'>
									<div>
										<div class='desc'>Imagen (opcional)</div>
										<?php echo ((strlen(trim($findBanner[0]['imgurl_en'])) > 0 && file_exists('../'.$dir_banners.trim($findBanner[0]['imgurl_en']))) ? "<div style='padding:10px;'><a href='../".$dir_banners.trim($findBanner[0]['imgurl_en'])."' target='_blank'><img width='300' src='../".$dir_banners.trim($findBanner[0]['imgurl_en'])."' /></a></div>" : ""); ?>
										<input type='file' name='img_en' />
									</div>
								</div>
								
							</div>
							
							<div class="tab-pane" id="tab_3">
								
								<div class='form-group'>
									<div>
										<div class='desc'>Imagen (opcional)</div>
										<?php echo ((strlen(trim($findBanner[0]['imgurl_es'])) > 0 && file_exists('../'.$dir_banners.trim($findBanner[0]['imgurl_es']))) ? "<div style='padding:10px;'><a href='../".$dir_banners.trim($findBanner[0]['imgurl_es'])."' target='_blank'><img width='300' src='../".$dir_banners.trim($findBanner[0]['imgurl_es'])."' /></a></div>" : ""); ?>
										<input type='file' name='img_es' />
									</div>
								</div>
								
							</div>
							
						</div>
					</div>
				</div>
				
				<div class='form-group'>
					<label>
						<div class='desc'>Link</div>
						<input type='text' name='link' maxlength='100' class='form-control' placeholder='(opcional)' value='<?php echo $findBanner[0]['link']; ?>' />
					</label>
				</div>
				
				<div class='form-group'>
					<b>Link Target</b>
					<div class='radchk'>
						<label>
							<input type='radio' name='target' value='1' class='flat-blue' <?php echo ($findBanner[0]['target'] == '1' ? 'checked' : '') ?> />
							Abre el link en nueva tab
						</label>
						&nbsp;&nbsp;
						<label>
							<input type='radio' name='target' value='0' class='flat-blue' <?php echo ($findBanner[0]['target'] != '1' ? 'checked' : '') ?> />
							Abre en la misma tab
						</label>
					</div>
				</div>
				
				<div class='form-group'>
					<b>Visualización</b>
					<div class='radchk'>
						<label>
							<input type='radio' name='vis' value='1' class='flat-blue' <?php echo ($findBanner[0]['vis'] == '1' ? 'checked' : '') ?> />
							Visible
						</label>
						&nbsp;&nbsp;
						<label>
							<input type='radio' name='vis' value='0' class='flat-blue' <?php echo ($findBanner[0]['vis'] != '1' ? 'checked' : '') ?> />
							Oculto
						</label>
					</div>
				</div>
				
				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Completar la edición' />
				</div>

			</div>

		</form>

	</div>

</section>


<!-- iCheck 1.0.1 -->
<script src="layout/plugins/iCheck/icheck.min.js"></script>

<script>
	$(function () {
		
		$('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue'
		});
		
	});
</script>
