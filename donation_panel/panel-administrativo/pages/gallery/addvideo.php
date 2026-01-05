<!-- iCheck -->
<link rel="stylesheet" href="layout/plugins/iCheck/flat/blue.css">

<section class="content-header">
	<h1>
		Añadir Vídeo
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-image"></i> Galeria</li>
		<li class="active">Añadir Vídeo</li>
	</ol>
</section>

<section class="content">

	<div class="box box-primary">

		<form class='atualstudio' method='POST' action='./?engine=addvideo&module=gallery'>

			<div class="box-body">
				
				Nuestro sistema está integrado al <a href='http://youtube.com' target='_blank'>YouTube</a>. Sólo tienes que informar del link o ID de vídeo alojado en el sitio web de YouTube que haremos la vinculación!<br /><br />
				
				<div class='form-group'>
					<label>
						<div class='desc'>Link YouTube</div>
						<input type='text' name='link' maxlength='100' class='form-control' placeholder='Introduzca aquí el link o ID de vídeo' />
					</label>
				</div>
				
				<div class='form-group'>
					<b>Visualización</b>
					<div class='radchk'>
						<label>
							<input type='radio' name='vis' value='1' class='flat-blue' checked />
							Visible
						</label>
						&nbsp;&nbsp;
						<label>
							<input type='radio' name='vis' value='0' class='flat-blue' />
							Oculto
						</label>
					</div>
				</div>
				
				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Añadir' />
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
