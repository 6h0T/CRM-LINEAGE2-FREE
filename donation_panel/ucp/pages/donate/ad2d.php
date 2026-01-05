<?php if((!$indexing) || ($logged != 1)) { exit; }
if($funct['donate'] != 1) { fim($LANG[40003], 'ERROR', './'); }
require('private/classes/classDonate.php');
?>

<ul class="breadcrumb">
	<li><a href='./?module=donate&page=add'><i class='fa fa-money'></i> <?php echo $LANG[12039]; ?></a></li>
	<li><?php echo $LANG[39010]; ?> <?php echo $coinName_mini; ?>'s</li>
</ul>

<h1><?php echo $LANG[39010]; ?> <?php echo $coinName_mini; ?>'s</h1>

<div class='pddInner'>
		
Para adquirir donaciones comunicarse via facebook<br>
Valor de la donacion 2 pesos 1 Forever Coin's<br>	
Donaciones unicamente por transferencia<br>
</div>

<script type='text/javascript'>
$(document).ready(function(){
	
	$('select option:selected').removeAttr('selected');
	
	var dsymbol = $('select#metodo_pgto option:selected').attr('data-symbol');
	$('#valor_symbol').text(dsymbol);
	
	$('select#metodo_pgto').change(function(){
		
		var dsymbol = $('select#metodo_pgto option:selected').attr('data-symbol');
		$('#valor_symbol').text(dsymbol);
		
		if($('select#metodo_pgto').val() == 'PagSeguro') {
			var preco = "<?php echo (!empty($PagSeguro['coin_price']) ? $PagSeguro['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Banking') {
			var preco = "<?php echo (!empty($Banking['coin_price']) ? $Banking['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PicPay') {
			var preco = "<?php echo (!empty($PicPay['coin_price']) ? $PicPay['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_USD') {
			var preco = "<?php echo (!empty($PayPal['USD']['coin_price']) ? $PayPal['USD']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_BRL') {
			var preco = "<?php echo (!empty($PayPal['BRL']['coin_price']) ? $PayPal['BRL']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_EUR') {
			var preco = "<?php echo (!empty($PayPal['EUR']['coin_price']) ? $PayPal['EUR']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'MercadoPago') {
			var preco = "<?php echo (!empty($MercadoPago['coin_price']) ? $MercadoPago['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PagosOnline') {
			var preco = "<?php echo (!empty($PagosOnline['coin_price']) ? $PagosOnline['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_USD') {
			var preco = "<?php echo (!empty($PayGol['USD']['coin_price']) ? $PayGol['USD']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_BRL') {
			var preco = "<?php echo (!empty($PayGol['BRL']['coin_price']) ? $PayGol['BRL']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_EUR') {
			var preco = "<?php echo (!empty($PayGol['EUR']['coin_price']) ? $PayGol['EUR']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'WebMoney') {
			var preco = "<?php echo (!empty($WebMoney['coin_price']) ? $WebMoney['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Payza') {
			var preco = "<?php echo (!empty($Payza['coin_price']) ? $Payza['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Skrill') {
			var preco = "<?php echo (!empty($Skrill['coin_price']) ? $Skrill['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Ualabis') {
            var preco = "<?php echo (!empty($Ualabis['coin_price']) ? $Ualabis['coin_price'] : 0); ?>";
        } else if($('select#metodo_pgto').val() == 'Weear') {
            var preco = "<?php echo (!empty($Weear['coin_price']) ? $Weear['coin_price'] : 0); ?>";
        } else {
			var preco = "<?php echo (!empty($G2APay['coin_price']) ? $G2APay['coin_price'] : 0); ?>";
		}
		
		var qntCoins = parseInt($('select#qtdCoins').val());
		
		<?php if($bonusActived == 1) { ?>
		
		var count1 = parseInt("<?php echo (isset($buyCoins['bonus_count'][1]) ? intval($buyCoins['bonus_count'][1]) : 0); ?>");
		var bonus1 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][1]) ? intval($buyCoins['bonus_percent'][1]) : 0); ?>");
		var count2 = parseInt("<?php echo (isset($buyCoins['bonus_count'][2]) ? intval($buyCoins['bonus_count'][2]) : 0); ?>");
		var bonus2 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][2]) ? intval($buyCoins['bonus_percent'][2]) : 0); ?>");
		var count3 = parseInt("<?php echo (isset($buyCoins['bonus_count'][3]) ? intval($buyCoins['bonus_count'][3]) : 0); ?>");
		var bonus3 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][3]) ? intval($buyCoins['bonus_percent'][3]) : 0); ?>");
		
		if(qntCoins >= count3) { var bonus = bonus3; }
		else if(qntCoins >= count2) { var bonus = bonus2; }
		else if(qntCoins >= count1) { var bonus = bonus1; }
		else { var bonus = '0'; }
		if(bonus > 0) {
			var calculado = parseInt((qntCoins*bonus)/100);
			$('#bonus').text(calculado);
			$('.bonus').show();
		} else {
			$('#bonus').text('');
			$('.bonus').hide();
		}
		
		<?php } ?>
		
		var price = ((qntCoins * (preco/<?php echo trim($coinQntV); ?>)).toFixed(2)).replace(".", ",");
		$('#valor_total').text(''+price+'');
		
	});
	
	$('select#qtdCoins').change(function(){
		
		if($('select#metodo_pgto').val() == 'PagSeguro') {
			var preco = "<?php echo (!empty($PagSeguro['coin_price']) ? $PagSeguro['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Banking') {
			var preco = "<?php echo (!empty($Banking['coin_price']) ? $Banking['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PicPay') {
			var preco = "<?php echo (!empty($PicPay['coin_price']) ? $PicPay['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_USD') {
			var preco = "<?php echo (!empty($PayPal['USD']['coin_price']) ? $PayPal['USD']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_BRL') {
			var preco = "<?php echo (!empty($PayPal['BRL']['coin_price']) ? $PayPal['BRL']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayPal_EUR') {
			var preco = "<?php echo (!empty($PayPal['EUR']['coin_price']) ? $PayPal['EUR']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'MercadoPago') {
			var preco = "<?php echo (!empty($MercadoPago['coin_price']) ? $MercadoPago['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_USD') {
			var preco = "<?php echo (!empty($PayGol['USD']['coin_price']) ? $PayGol['USD']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_BRL') {
			var preco = "<?php echo (!empty($PayGol['BRL']['coin_price']) ? $PayGol['BRL']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'PayGol_EUR') {
			var preco = "<?php echo (!empty($PayGol['EUR']['coin_price']) ? $PayGol['EUR']['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'WebMoney') {
			var preco = "<?php echo (!empty($WebMoney['coin_price']) ? $WebMoney['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Payza') {
			var preco = "<?php echo (!empty($Payza['coin_price']) ? $Payza['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Skrill') {
			var preco = "<?php echo (!empty($Skrill['coin_price']) ? $Skrill['coin_price'] : 0); ?>";
		} else if($('select#metodo_pgto').val() == 'Ualabis') {
            var preco = "<?php echo (!empty($Ualabis['coin_price']) ? $Ualabis['coin_price'] : 0); ?>";
        } else if($('select#metodo_pgto').val() == 'Weear') {
            var preco = "<?php echo (!empty($Weear['coin_price']) ? $Weear['coin_price'] : 0); ?>";
        } else {
			var preco = "<?php echo (!empty($G2APay['coin_price']) ? $G2APay['coin_price'] : 0); ?>";
		}
		
		var qntCoins = parseInt($(this).val());
		
		<?php if($bonusActived == 1) { ?>
		
		var count1 = parseInt("<?php echo (isset($buyCoins['bonus_count'][1]) ? intval($buyCoins['bonus_count'][1]) : 0); ?>");
		var bonus1 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][1]) ? intval($buyCoins['bonus_percent'][1]) : 0); ?>");
		var count2 = parseInt("<?php echo (isset($buyCoins['bonus_count'][2]) ? intval($buyCoins['bonus_count'][2]) : 0); ?>");
		var bonus2 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][2]) ? intval($buyCoins['bonus_percent'][2]) : 0); ?>");
		var count3 = parseInt("<?php echo (isset($buyCoins['bonus_count'][3]) ? intval($buyCoins['bonus_count'][3]) : 0); ?>");
		var bonus3 = parseInt("<?php echo (isset($buyCoins['bonus_percent'][3]) ? intval($buyCoins['bonus_percent'][3]) : 0); ?>");
		
		if(qntCoins >= count3) { var bonus = bonus3; }
		else if(qntCoins >= count2) { var bonus = bonus2; }
		else if(qntCoins >= count1) { var bonus = bonus1; }
		else { var bonus = '0'; }
		if(bonus > 0) {
			var calculado = parseInt((qntCoins*bonus)/100);
			$('#bonus').text(calculado);
			$('.bonus').show();
		} else {
			$('#bonus').text('');
			$('.bonus').hide();
		}
		
		<?php } ?>
		
		var price = ((qntCoins * (preco/<?php echo trim($coinQntV); ?>)).toFixed(2)).replace(".", ",");
		$('#valor_total').text(''+price+'');
		
	});
	
});
</script>
