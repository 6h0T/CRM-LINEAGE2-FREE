<?php if((!$indexing) || ($logged != 1)) { exit; }

if($funct['servic'] != 1) { fim($LANG[40003], 'ERROR', './'); }

require('private/classes/classServices.php');
?>

<ul class="breadcrumb">
	<li><a href="./?module=services&page=list"><i class='fa fa-suitcase'></i> <?php echo $LANG[10010]; ?></a></li>
	<li><?php echo $LANG[40001]; ?></li>
</ul>

<h1><?php echo $LANG[10010]; ?></h1>

<div class='pddInner' style='padding-bottom:10px;'>
	<?php echo $LANG[10066]; ?>
</div>

<div class='mult'>
	
	<div class='selec'>
		<?php
		
		$findChars = Services::findChars($_SESSION['acc']);
		if(count($findChars) > 0) {
			for($i=0, $c=count($findChars); $i < $c; $i++) {
				$heroEndAtual[$i] = ((!empty($findChars[$i]['hero_end'])) ? (!empty($heroMultMil) ? (trim($findChars[$i]['hero_end'])/1000) : trim($findChars[$i]['hero_end'])) : 0);
				if($heroEndAtual[$i] > time()) { $heroEndAtual[$i] = date('d F, Y H:i', $heroEndAtual[$i]); } else { $heroEndAtual[$i] = 0; }
				$vipEndAtual[$i] = ((!empty($findChars[$i]['vip_end'])) ? (!empty($vipMultMil) ? (trim($findChars[$i]['vip_end'])/1000) : trim($findChars[$i]['vip_end'])) : 0);
				if($vipEndAtual[$i] > time()) { $vipEndAtual[$i] = date('d F, Y H:i', $vipEndAtual[$i]); } else { $vipEndAtual[$i] = 0; }
				$aioEndAtual[$i] = (empty($findChars[$i]['aio']) ? 0 : ((!empty($findChars[$i]['aio_end'])) ? (!empty($aioMultMil) ? (trim($findChars[$i]['aio_end'])/1000) : trim($findChars[$i]['aio_end'])) : 0));
				if($aioEndAtual[$i] > time()) { $aioEndAtual[$i] = date('d F, Y H:i', $aioEndAtual[$i]); } else { $aioEndAtual[$i] = 0; }
				echo "
				<div".($i == 0 ? " class='actived'" : "")." data-id='".$findChars[$i]['obj_Id']."' data-hero='".$heroEndAtual[$i]."' data-vip='".$vipEndAtual[$i]."' data-aio='".$aioEndAtual[$i]."'>
					<img src='imgs/avatar/".genAvatar($findChars[$i]['base_class'], $findChars[$i]['sex'])."' />
					<div>".$findChars[$i]['char_name']."</div>
				</div>
				";
			}
		}
		for($i=1; $i <= intval(7-count($findChars)); $i++) {
			echo "<span></span>";
		}
		
		$xpldColors = @explode(',', trim($hexColors));
		
		?>
	</div>
	
	<div class='opts'>
		
		<div class='charSelectedTxt'><?php echo $LANG[39016]; ?>: <b><?php echo trim($findChars[0]['char_name']); ?></b></div>
		
		<style type='text/css'>
			select.thecolors option { font-weight: bold !important; text-shadow: 2px 2px 8px #000 !important; }
		</style>
		
		<?php if(!empty($service['actv']['namecolor'])) { ?>
		<form action='./?module=services&engine=namecolor' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Nickname color</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['namecolor']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39019]; ?></div>
			<div class='work'>
				<?php echo $LANG[39020]; ?><br /><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39021]; ?>:</div>
						<div class='camp'>
							<select name='cor' class='thecolors'>
								<option value='0'><?php echo $LANG[12008]; ?></option>
								<?php
								if(count($xpldColors) == 0) {
									echo "<option value=''>".$LANG[39040]."</option>";
								} else {
									for($i=0, $c=count($xpldColors); $i < $c; $i++) {
										$xpldColors[$i] = strtoupper($xpldColors[$i]);
										echo "<option value='".$xpldColors[$i]."' style='color: #".$xpldColors[$i].";'>#".$xpldColors[$i]."</option>";
									}
								}
								?>
							</select>
						</div>
					</div>
				</label>
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['titlecolor'])) { ?>
		<form action='./?module=services&engine=titlecolor' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Title color</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['titlecolor']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39022]; ?>.</div>
			<div class='work'>
				<?php echo $LANG[39020]; ?><br /><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39021]; ?>:</div>
						<div class='camp'>
							<select name='cor' class='thecolors'>
								<option value='0'><?php echo $LANG[12008]; ?></option>
								<?php
								if(count($xpldColors) == 0) {
									echo "<option value=''>".$LANG[39040]."</option>";
								} else {
									for($i=0, $c=count($xpldColors); $i < $c; $i++) {
										$xpldColors[$i] = strtoupper($xpldColors[$i]);
										echo "<option value='".$xpldColors[$i]."' style='color: #".$xpldColors[$i].";'>#".$xpldColors[$i]."</option>";
									}
								}
								?>
							</select>
						</div>
					</div>
				</label>
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['removekarma'])) { ?>
		<form action='./?module=services&engine=removekarma' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Remove Karma</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['removekarma']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39023]; ?>.</div>
			<div class='work'>
				<?php echo $LANG[39024]; ?><br />
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['pkreset'])) { ?>
		<form action='./?module=services&engine=pkreset' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>PK Counter Reset</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['pkreset']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39025]; ?></div>
			<div class='work'>
				<?php echo $LANG[39026]; ?><br />
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['changename'])) { ?>
		<form action='./?module=services&engine=changename' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Character Nickname</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['changename']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39027]; ?></div>
			<div class='work'>
				<?php echo $LANG[39028]; ?><br /><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'>Nickname:</div>
						<div class='camp'><input type='text' name='nickname' autocomplete='off' maxlength='16' /></div>
					</div>
				</label>
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39029]; ?>:</div>
						<div class='camp'><input type='text' name='nickname2' autocomplete='off' maxlength='16' /></div>
					</div>
				</label>
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['clanname'])) { ?>
		<form action='./?module=services&engine=clanname' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Clan Name</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['clanname']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39030]; ?></div>
			<div class='work'>
				<?php echo $LANG[39028]; ?> <?php echo $LANG[39031]; ?><br /><br />
				<div style='text-align:center;font-weight:bold;'><?php echo $LANG[11999]; ?></div><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[10095]; ?>:</div>
						<div class='camp'><input type='text' name='name' autocomplete='off' maxlength='16' /></div>
					</div>
				</label>
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39032]; ?>:</div>
						<div class='camp'><input type='text' name='name2' autocomplete='off' maxlength='16' /></div>
					</div>
				</label>
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['sexchange'])) { ?>
		<form action='./?module=services&engine=sexchange' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Sex/Gender Change</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['sexchange']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39033]; ?></div>
			<div class='work'>
				<?php echo $LANG[39034]; ?><br /><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39035]; ?>:</div>
						<div class='camp'>
							<select name='gender'>
								<option value='0'><?php echo $LANG[39036]; ?></option>
								<option value='1'><?php echo $LANG[39037]; ?></option>
							</select>
						</div>
					</div>
				</label>

				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['transferchar'])) { ?>
		<form action='./?module=services&engine=transferchar' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Transfer Character</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['transferchar']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39038]; ?></div>
			<div class='work'>
				<?php echo $LANG[39039]; ?><br /><br />
				<label class='formpadrao'>
					<div>
						<div class='desc'>Account:</div>
						<div class='camp'>
							<select name='newacc'>
								<?php
								$accs = Services::findAccs($_SESSION['acc']);
								if(count($accs) == 0) {
									echo "<option value=''>".$LANG[39040]."</option>";
								} else {
									for($i=0, $c=count($accs); $i < $c; $i++) {
										echo "<option value='".$accs[$i]['login']."'>".$accs[$i]['login']."</option>";
									}
								}
								?>
							</select>
						</div>
					</div>
				</label>

				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['unstuck'])) { ?>
		<form action='./?module=services&engine=unstuck' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Unstuck</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['unstuck']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39061]; ?></div>
			<div class='work'>
				<?php echo $LANG[39062]; ?><br />
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['nobless'])) { ?>
		<form action='./?module=services&engine=nobless' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Set Noblesse</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['nobless']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39063]; ?></div>
			<div class='work'>
				<?php echo $LANG[39064]; ?><br />
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['hero'])) { ?>
		<form action='./?module=services&engine=hero' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Set Hero</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['hero']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39065]; ?></div>
			<div class='work'>
				<?php echo $LANG[39066]; ?><br /><br />
				<div class='charHeroEnd atualEnd <?php if($heroEndAtual[0] != '0') { echo "'>".sprintf($LANG[39055], 'hero').'<b>'.$heroEndAtual[0].'</b>.'; } else { echo "invis'>"; } ?></div>
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['basechange'])) { ?>
		<form action='./?module=services&engine=changebaseclass' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Change Base Class</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['basechange']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39068]; ?></div>
			<div class='work'>
				<?php echo $LANG[39069]; ?><br /><br />
				<?php $class = array(88 => 'Duelist', 89 => 'Dreadnought', 90 => 'Phoenix Knight', 91 => 'Hell Knight', 92 => 'Sagittarius', 93 => 'Adventurer', 94 => 'Archmage', 95 => 'Soultaker', 96 => 'Arcana Lord', 97 => 'Cardinal', 98 => 'Hierophant', 99 => 'Eva Templar', 100 => 'Sword Muse', 101 => 'Wind Rider', 102 => 'Moonlight Sentinel', 103 => 'Mystic Muse', 104 => 'Elemental Master', 105 => 'Eva Saint', 106 => 'Shillien Templar', 107 => 'Spectral Dancer', 108 => 'Ghost Hunter', 109 => 'Ghost Sentinel', 110 => 'Storm Screamer', 111 => 'Spectral Master', 112 => 'Shillien Saint', 113 => 'Titan', 114 => 'Grand Khauatari', 115 => 'Dominator', 116 => 'Doomcryer', 117 => 'Fortune Seeker', 118 => 'Maestro', 131 => 'Doombringer', 132 => 'Male Soulhound', 133 => 'Female Soulhound', 134 => 'Trickster'); asort($class); ?>
				<label class='formpadrao'>
					<div>
						<div class='desc'>Class:</div>
						<div class='camp'>
							<select name='sclass'>
								<option value='0'><?php echo $LANG[12008]; ?></option>
								<?php foreach($class as $key => $value) {
									echo "<option value='".$key."'>".$value."</option>";
								} ?>
							</select>
						</div>
					</div>
				</label>

				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['vip'])) { ?>
		<form action='./?module=services&engine=vip' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Set Vip</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['vip']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[39070]; ?></div>
			<div class='work'>
				<?php echo $LANG[39071]; ?><br /><br />
				<div class='charVipEnd atualEnd <?php if($vipEndAtual[0] != '0') { echo "'>".sprintf($LANG[39055], 'Vip').'<b>'.$vipEndAtual[0].'</b>.'; } else { echo "invis'>"; } ?></div>
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39067]; ?></div>
						<div class='camp'>
							<select name='periodo'>
								<?php
								for($i=1, $c=12; $i <= $c; $i++) {
									echo "<option value='".$i."'>".(30*$i)." ".$LANG[12014]."s (".$i." ".($i > 1 ? $LANG[39072] : $LANG[39073]).")</option>";
								}
								?>
							</select>
						</div>
					</div>
				</label>					
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		
		if(!empty($service['actv']['aio'])) { ?>
		<form action='./?module=services&engine=aio' class='service usarJquery'>
			<input type='hidden' name='cid' class='charSelected' value='<?php echo intval($findChars[0]['obj_Id']); ?>' />
			<div class='title'><?php echo $LANG[39014]; ?>: <b>Set AIO</b> <div class='costs'><?php echo $LANG[39015]; ?>: <b><?php echo "".intval(trim($service['cost']['aio']))." ".$coinName."'s"; ?></b></div></div>
			<div class='desc'><?php echo $LANG[40059]; ?></div>
			<div class='work'>
				<?php echo $LANG[40060]; ?><br /><br />
				<div class='charAioEnd atualEnd <?php if($aioEndAtual[0] != '0') { echo "'>".sprintf($LANG[39055], 'AIO').'<b>'.$aioEndAtual[0].'</b>.'; } else { echo "invis'>"; } ?></div>
				<label class='formpadrao'>
					<div>
						<div class='desc'><?php echo $LANG[39067]; ?></div>
						<div class='camp'>
							<select name='periodo'>
								<?php
								for($i=1, $c=12; $i <= $c; $i++) {
									echo "<option value='".$i."'>".(30*$i)." ".$LANG[12014]."s (".$i." ".($i > 1 ? $LANG[39072] : $LANG[39073]).")</option>";
								}
								?>
							</select>
						</div>
					</div>
				</label>					
				<div class='confirmChar invis'><?php echo $LANG[39016]; ?>: <b></b> <a href='#' class='default'><?php echo $LANG[10121]; ?></a></div>
			</div>
			<input type='submit' class='default executeService' value='<?php echo $LANG[39017]; ?>' />
		</form>
		<?php
		}
		?>
		
	</div>
	
</div>

<input type='hidden' id='l39055' value='<?php echo sprintf($LANG[39055], 'hero'); ?>' />
<input type='hidden' id='l39056' value='<?php echo sprintf($LANG[39055], 'Vip'); ?>' />
<input type='hidden' id='l39057' value='<?php echo sprintf($LANG[39055], 'AIO'); ?>' />

<script>
$(document).ready(function(){
	
	$('.executeService').click(function(){
		
		$('.confirmChar b').text($('.charSelectedTxt b').text());
		
		$('.confirmChar').addClass('invis');
		
		$('.executeService').removeClass('actived');
		$(this).addClass('actived');
		
		if($(this).val() == '<?php echo $LANG[39017]; ?>') {
			
			$('.executeService').val('<?php echo $LANG[39017]; ?>');
			$(this).val('<?php echo $LANG[39018]; ?>').removeClass('sucesso');
			
			$(this).parent().children('.work').children('.confirmChar').removeClass('invis');
			$(this).parent('form').removeClass('sucedido');
			
			return false;
			
		} else {
			
			$('.executeService').val('<?php echo $LANG[39017]; ?>');
			
		}
		
	});
	
	$('.confirmChar a').click(function(e){
		
		e.preventDefault();
		$('.confirmChar b').text($('.charSelectedTxt b').text());
		$('.confirmChar').addClass('invis');
		$('.executeService').removeClass('actived').val('<?php echo $LANG[39017]; ?>');
		
	});
	
});
</script>

<br />