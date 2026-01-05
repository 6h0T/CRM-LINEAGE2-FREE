<?php if((!$indexing) || ($logged != 1)) { exit; }
require('private/classes/classVote.php');
?>

<ul class="breadcrumb">
	<li><a href='./?module=vote&page=index'><i class='fa fa-thumbs-up'></i> <?php echo $LANG[50001]; ?></a></li>
	<li><?php echo $LANG[50018]; ?></li>
</ul>

<h1><?php echo $LANG[50018]; ?></h1>
<div class='pddInner'>
	
	<?php
	$votes = Vote::getVoteHistory($_SESSION['acc'], 50);
	
	if(count($votes) > 0) {
		echo "
		<table class='tbl1' border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<th style='width:50px;'>#</th>
				<th style='width:180px;'>".$LANG[50008]."</th>
				<th style='width:120px;'>".$LANG[50009]."</th>
				<th style='width:180px;'>".$LANG[50021]."</th>
				<th>".$LANG[50022]."</th>
			</tr>
		";
		
		for($i = 0; $i < count($votes); $i++) {
			$statusClass = $votes[$i]['status'] == 1 ? 'success' : 'error';
			$statusText = $votes[$i]['status'] == 1 ? $LANG[50023] : $LANG[50024];
			
			echo "
			<tr>
				<td>".($i + 1)."</td>
				<td>".$votes[$i]['topsite_name']."</td>
				<td>".$votes[$i]['reward']." ".$coinName_mini."'s</td>
				<td>".date('d/m/Y H:i', $votes[$i]['vote_date'])."</td>
				<td><span class='rmsg ".$statusClass."' style='padding:3px 8px;'>".$statusText."</span></td>
			</tr>
			";
		}
		
		echo "</table>";
		
	} else {
		echo "<div class='rmsg info'>".$LANG[50025]."</div>";
	}
	?>
	
	<div style='display:table;width:100%;margin-top:20px;'>
		<a href='./?module=vote&page=index' class='default' style='float:right;'><?php echo $LANG[50026]; ?></a>
	</div>
</div>
