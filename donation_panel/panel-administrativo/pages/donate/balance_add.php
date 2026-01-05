<section class="content-header">
	<h1>
		Añadir Saldo
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-money"></i> Donaciones</li>
		<li class="active">Añadir Saldo</li>
	</ol>
</section>

<section class="content">
	
	<div class="box">
		
		<form method='POST' class='atualstudio usarJquery' action='?engine=balance_change&module=donate'>

			<div class="box-body">
				
				Introduzca el login de la cuenta y el saldo que desea insertar en la misma.<br />
				Nota: Si la cuenta insertada ya tiene saldo, se reemplazará con el nuevo valor que se inserta a continuación.<br /><br />

				<div class='form-group'>
					<label>
						<div class='desc'>Account (Login)</div>
						<input type='text' name='account' maxlength='25' class='form-control' />
					</label>
				</div>

				<div class='form-group'>
					<label>
						<div class='desc'> Saldo (<?php echo strtolower($coinName)."'s"; ?>)</div>
						<input type='text' name='saldo' maxlength='11' class='form-control' />
					</label>
				</div>

				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Añadir Saldo' />
				</div>

			</div>
			
		</form>
		
	</div>
	
</section>

