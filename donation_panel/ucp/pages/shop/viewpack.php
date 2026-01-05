<?php if((!$indexing) || ($logged != 1)) { exit; }

if($funct['shopon'] != 1) { fim($LANG[40003], 'ERROR', './'); }

require('private/classes/classShop.php');

$id = !empty($_GET['id']) ? intval($_GET['id']) : 0;

$pack = Shop::findPack($id);
if(count($pack) == 0) {
	fim($LANG[39058]);
}

?>

<ul class="breadcrumb">
	<li><a href="./?module=shop&page=list"><i class='fa fa-shopping-cart'></i> Shop</a></li>
	<li><a href="./?module=shop&page=list"><?php echo $LANG[40002]; ?></a></li>
	<li><?php echo $pack[0]['nome']; ?></li>
</ul>


<h1>Shop &raquo; <?php echo $pack[0]['nome']; ?></h1>

<div class='shopItemList'>
	
	<?php echo $LANG[39042]; ?><br /><br />
	
	<div>
		
		<form id='buyItemForm'>
			
			<div class='packImg'>
				<img src='imgs/shop/<?php echo (!empty($pack[0]['imagem']) ? (file_exists('imgs/shop/'.$pack[0]['imagem']) ? $pack[0]['imagem'] : 'unknow.jpg') : 'unknow.jpg'); ?>' />
			</div>
			
			<table cellspacing='0' cellpadding='0' border='0' class='default itemList'>
			
				<tr>
					<th style='width:20px;'></th>
					<th style='width:32px;'></th>
					<th><?php echo $LANG[12013]; ?></th>
					<th><?php echo $LANG[10070]; ?></th>
				</tr>
				
				<?php
				$consulta = Shop::listItens($id);
				if(count($consulta) == 0) {
					echo'<tr><td colspan="4">'.$LANG[39043].'</td></tr>';
				} else {
					for($i=0, $c=count($consulta); $i < $c; $i++) {
						echo"
						<tr".(($i % 2 == 0) ? " class='two'" : '').">
							<td><input class='sumPrice' type='checkbox' name='itens[]' data-price='".$consulta[$i]['valor']."' value='".$consulta[$i]['sitem_id']."' /></td>
							<td>".(file_exists('icons/itens/'.intval($consulta[$i]['id_ingame']).'.png') ? "<img width='32' height='32' src='icons/itens/".intval($consulta[$i]['id_ingame']).".png' />" : "<img width='32' height='32' src='imgs/icons.php?type=1&id=".intval($consulta[$i]['id_ingame'])."' />")."</td>
							<td>".$consulta[$i]['nome'] . "&nbsp;" . (!empty($consulta[$i]['sa']) ? " <b>".$consulta[$i]['sa']."</b>&nbsp;" : "") . (!empty($consulta[$i]['enchant']) ? " <b>+".$consulta[$i]['enchant']."</b>&nbsp;" : "") . (!empty($consulta[$i]['amount']) ? " (".$consulta[$i]['amount'].")" : "")."</td>
							<td><b>".$consulta[$i]['valor']."</b> ".$coinName."'s</td>
						</tr>
						";
					}
				}
				
				if($pack[0]['valor'] > 0 && $i > 1) {
					echo "
					<tr class='pin'>
						<td><input class='sumPrice' type='checkbox' name='itens[]' data-price='".$pack[0]['valor']."' value='all' /></td>
						<td><img width='32' height='32' src='imgs/shop/".(!empty($pack[0]['imagem']) ? (file_exists('imgs/shop/'.$pack[0]['imagem']) ? $pack[0]['imagem'] : 'unknow.jpg') : 'unknow.jpg')."' /></td>
						<td style='font-weight: bold;'>".$LANG[39060]."</td>
						<td><b>".$pack[0]['valor']."</b> ".$coinName."'s</td>
					</tr>
					";
				}
				
				?>
				
			</table>
			
		</form>
		
	</div>
	
</div>

<div class='pddInner'>
	
	<table class='donateBox' border='0' cellpadding='0' cellspacing='0'>
		
		<tr>
			<th><i class='fa fa-user'></i> <?php echo $LANG[10056]; ?></th>
			<th><i class='fa fa-dollar'></i> <?php echo $LANG[10058]; ?></th>
			<th></th>
		</tr>
		
		<tr>
			
			<td>
				<select style='min-width: 170px;' id='personagem'>
				<?php
				$listChars = Shop::listChars($_SESSION['acc']);
				if(count($listChars) > 0) {
					echo "<option value='0' selected>".$LANG[12008]."</option>";
					for($i=0, $c=count($listChars); $i < $c; $i++) {
						echo "<option value='".$listChars[$i]['obj_Id']."'>".$listChars[$i]['char_name']."</option>";
					}
				} else { echo "<option value='0'>".$LANG[12100]."</option>"; }
				?>
				</select>
			</td>
			
			<td style='text-align:center;'>
				<span style='color:#237200;font-size:15px;'><b><span id='valor_total'>0</span> <?php echo $coinName."'s"; ?></b></span>
			</td>
			
			<td style='width:80px;'>
				<input id='buyTrigger' type='button' class='default' value='<?php echo $LANG[10011]; ?>' />
			</td>
			
		</tr>
		
	</table>
	
</div>



<script>
$(function() {
	
	$('.sumPrice').prop('checked', false);
	
	$('.sumPrice').change(function(){
		
		var isChecked = $(this).prop('checked');
		var price = $(this).attr('data-price');
		
		if($(this).val() == 'all') {
			
			if(isChecked == true) {
				$('input.sumPrice[value!="all"]').prop('checked', false).prop('disabled', true);
				$('#valor_total').text(parseInt(price));
			} else {
				$('input.sumPrice[value!="all"]').prop('checked', false).prop('disabled', false);
				$('#valor_total').text('0');
			}
			
		} else {
			
			$('input.sumPrice[value="all"]').prop('checked', false);
			
			var priceTotal = $('#valor_total').text();
			
			if(isChecked == true) {
				$('#valor_total').text(parseInt(parseInt(priceTotal)+parseInt(price)));
			} else {
				$('#valor_total').text(parseInt(parseInt(priceTotal)-parseInt(price)));
			}
			
		}
		
		
	});
	
	
	$('#buyTrigger').click(function() {
		
		var submitButton = $(this);
		var theForm = $('#buyItemForm');
	
		if(!$(submitButton).hasClass('loading')) {
			
			$(submitButton).attr('data-oldtext', ''+$(submitButton).val()+'').addClass('loading').val('<?php echo $LANG[20001]; ?>');
			
			$.ajax({
				type: 'POST',
				url: './?module=shop&engine=buyitem',
				cache: false,
				data: $(theForm).serialize()+'&char='+$('select#personagem').val()+'&pack=<?php echo $id; ?>&isJS=1',
				dataType: 'json',
				timeout: 20000,
				async: false,
				success: function(data)
				{
					
					$(submitButton).val(''+$(submitButton).attr('data-oldtext')+'').removeClass('loading');
					atualAlert(data.msg, data.act, data.url);
					
				},
			    error: function(jqXHR, textStatus){
			    	$(submitButton).val(''+$(submitButton).attr('data-oldtext')+'').removeClass('loading');
			    	if(textStatus == 'timeout') {
				        atualAlert('<?php echo $LANG[10064]; ?>');
			    	} else if(textStatus != 'abort') {
				        atualAlert('<?php echo $LANG[10065]; ?> #3');
				    }
			    }
			});
			
		}
		
	});
	
});
</script>