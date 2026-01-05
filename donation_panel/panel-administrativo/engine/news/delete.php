<?php

$nid = !empty($_GET['nid']) ? intval($_GET['nid']) : 0;

require('private/classes/classNews.php');

$findNew = News::findNew($nid);
if(count($findNew) == 0){
	fim('¡Noticia inexistente!');
}

$delete = News::deleteNew($nid);
if($delete) {
	if(file_exists('../'.$dir_newsimg.$findNew[0]['img']) && !empty($findNew[0]['img'])) {
		unlink('../'.$dir_newsimg.$findNew[0]['img']);
	}
	adminLog("Excluyó a notícia de ID ".$nid); // Admin Log
	fim('¡Noticia excluida con éxito!', 'OK', './?page=list&module=news');
} else {
	fim('Lo siento, ocurrió algún error. Por favor, inténtelo más tarde.');
}
