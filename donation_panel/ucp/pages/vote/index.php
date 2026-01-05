<?php if((!$indexing) || ($logged != 1)) { exit; }
require('private/classes/classVote.php');
?>

<ul class="breadcrumb">
	<li><a href='./?module=vote&page=index'><i class='fa fa-thumbs-up'></i> <?php echo $LANG[50001]; ?></a></li>
	<li><?php echo $LANG[50002]; ?></li>
</ul>

<?php
$userIP = !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : 
		  (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

$totalVotes = Vote::getTotalVotes($_SESSION['acc']);
$totalRewards = Vote::getTotalRewards($_SESSION['acc']);
?>

<h1><?php echo $LANG[50001]; ?></h1>
<div class='pddInner'>
	<?php echo $LANG[50003]; ?>
	<br /><br />
	<b><?php echo $LANG[50004]; ?>:</b> <?php echo $voteReward; ?> <?php echo $coinName_mini; ?>'s<br />
	<b><?php echo $LANG[50005]; ?>:</b> <?php echo intval($totalVotes[0]['total']); ?><br />
	<b><?php echo $LANG[50006]; ?>:</b> <?php echo intval($totalRewards[0]['total']); ?> <?php echo $coinName_mini; ?>'s
</div>

<h1><?php echo $LANG[50007]; ?></h1>
<div class='pddInner'>
	
	<table class='tbl1' border='0' cellpadding='0' cellspacing='0'>
		<tr>
			<th style='width:200px;'><?php echo $LANG[50008]; ?></th>
			<th style='width:150px;'><?php echo $LANG[50009]; ?></th>
			<th style='width:150px;'><?php echo $LANG[50010]; ?></th>
			<th><?php echo $LANG[50011]; ?></th>
		</tr>
		<?php
		$hasActiveTopsites = false;
		foreach($topsites as $id => $topsite) {
			if($topsite['actived'] == 1) {
				$hasActiveTopsites = true;
				$voteStatus = Vote::canVote($_SESSION['acc'], $id, $voteCooldown);
				
				$statusClass = $voteStatus['can_vote'] ? 'success' : 'warning';
				$statusText = $voteStatus['can_vote'] ? $LANG[50012] : Vote::formatTimeRemaining($voteStatus['time_remaining']);
				
				echo "
				<tr>
					<td>
						<img src='imgs/topsites/".$topsite['image']."' alt='".$topsite['name']."' style='height:30px;vertical-align:middle;margin-right:10px;' onerror=\"this.style.display='none'\"/>
						<b>".$topsite['name']."</b>
					</td>
					<td>".$voteReward." ".$coinName_mini."'s</td>
					<td><span class='rmsg ".$statusClass."' style='padding:5px 10px;'>".$statusText."</span></td>
					<td>";
				
				if($voteStatus['can_vote']) {
					echo "<a href='".$topsite['vote_url']."' target='_blank' class='default' onclick=\"openVoteWindow(this, ".$id.")\">".$LANG[50013]."</a> ";
					echo "<a href='#' class='default' data-topsite='".$id."' onclick=\"claimReward(this, ".$id."); return false;\" style='margin-left:10px;'>".$LANG[50014]."</a>";
				} else {
					echo "<span class='default' style='opacity:0.5;cursor:not-allowed;'>".$LANG[50015]."</span>";
				}
				
				echo "</td>
				</tr>";
			}
		}
		
		if(!$hasActiveTopsites) {
			echo "<tr><td colspan='4' style='text-align:center;'>".$LANG[50016]."</td></tr>";
		}
		?>
	</table>
	
	<br />
	<div class='rmsg info'><?php echo $LANG[50017]; ?></div>
	
	<div style='display:table;width:100%;margin-top:20px;'>
		<a href='./?module=vote&page=history' class='default' style='float:right;'><?php echo $LANG[50018]; ?></a>
	</div>
</div>

<script>
function openVoteWindow(element, topsiteId) {
	// Abre la ventana de votación
	window.open(element.href, '_blank');
}

function claimReward(element, topsiteId) {
	if($(element).hasClass('processing')) return;
	
	$(element).addClass('processing').text('<?php echo $LANG[50019]; ?>');
	
	$.ajax({
		type: 'POST',
		url: './?module=vote&engine=claim_vote',
		cache: false,
		data: { topsite_id: topsiteId, isJS: 1 },
		dataType: 'json',
		success: function(data) {
			if(data.success) {
				alert(data.message);
				location.reload();
			} else {
				alert(data.message);
				$(element).removeClass('processing').text('<?php echo $LANG[50014]; ?>');
			}
		},
		error: function() {
			alert('<?php echo $LANG[50020]; ?>');
			$(element).removeClass('processing').text('<?php echo $LANG[50014]; ?>');
		}
	});
}
</script>
