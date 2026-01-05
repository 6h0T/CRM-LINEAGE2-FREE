<section class="content-header">
	<h1>
		Cambiar el Saldo
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-money"></i> Donaciones</li>
		<li class="active">Cambiar Saldo</li>
	</ol>
</section>

<section class="content">

	<div class="box">

		<?php
		
		$account = !empty($_GET['account']) ? vCode($_GET['account']) : 0;

		require('private/classes/classDonate.php');

		$cons = Donate::checkBalance($account);
		if(count($cons) == 0){
			fim('La cuenta seleccionada no tiene saldo!');
		}
		
		?>

		<form method='POST' class='atualstudio usarJquery' action='?engine=balance_change&module=donate'>

			<div class="box-body">

				Introduzca el nuevo saldo de la cuenta.<br /><br />

				<div class='form-group'>
					<label>
						<div class='desc'>Account (Login)</div>
						<input type='text' class='form-control' value='<?php echo $cons[0]['account']; ?>' disabled />
					</label>
				</div>

				<div class='form-group'>
					<label>
						<div class='desc'> Saldo (<?php echo strtolower($coinName)."'s"; ?>)</div>
						<input type='text' name='saldo' maxlength='11' class='form-control' value='<?php echo intval(trim($cons[0]['saldo'])); ?>'/>
					</label>
				</div>
				
				<input type='hidden' name='account' value='<?php echo $account; ?>' />

				<div class='box-footer'>
					<input type='submit' class='btn btn-primary' value='Cambiar Saldo' />
				</div>

			</div>
			
		</form>
		
	</div>
	
</section>

