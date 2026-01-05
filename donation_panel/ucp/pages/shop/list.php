<?php if((!$indexing) || ($logged != 1)) { exit; }

if($funct['shopon'] != 1) { fim($LANG[40003], 'ERROR', './'); }

require('private/classes/classShop.php');

$cat = !empty($_GET['cat']) ? intval($_GET['cat']) : 0;

?>

<ul class="breadcrumb">
	<li><a href="./?module=shop&page=list"><i class='fa fa-shopping-cart'></i> Shop</a></li>
	<li><?php echo $LANG[40002]; ?></li>
</ul>

<h1>Shop</h1>

<div class='pddInner' style='padding-bottom:0;'>
	
	<div class='wSelectBg'>
		<?php echo $LANG[39041]; ?>: 
		<select id='selectCatego'>
			<option value='0'><?php echo $LANG[39059]; ?></option>
			<?php
			$listCatego = Shop::listCatego();
			if(count($listCatego) > 0) {
				for($i=0, $c=count($listCatego); $i < $c; $i++) {
					echo "<option value='".$listCatego[$i]['scat_id']."'".($listCatego[$i]['scat_id'] == $cat ? ' selected' : '').">".$listCatego[$i]['nome']."</option>";
				}
			}
			?>
		</select>
		<script>
			$('body').on('change', 'select#selectCatego', function(){
				var cat = $('select#selectCatego').val();
				document.location.href='./?module=shop&page=list&cat='+cat;
			});
		</script>
	</div>
	
</div>

<div class='shopPacks'>
	<?php
	$listPacks = Shop::listPacks($cat);
	if(count($listPacks) > 0) {
		for($i=0, $c=count($listPacks); $i < $c; $i++) {
			echo "
			<a href='./?module=shop&page=viewpack&id=".$listPacks[$i]['spack_id']."' style='background-image: url(imgs/shop/".(!empty($listPacks[$i]['imagem']) ? (file_exists('imgs/shop/'.$listPacks[$i]['imagem']) ? $listPacks[$i]['imagem'] : 'unknow.jpg') : 'unknow.jpg').");'>
				<div>".(strlen($listPacks[$i]['nome']) > 18 ? trim(substr($listPacks[$i]['nome'], 0, 18)).'...' : $listPacks[$i]['nome'])."</div>
			</a>";
		}
	}
	?>
</div>

