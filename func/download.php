<?php
	$functionto = $_GET['ins'];
	
	$file = $_GET['fil'];
	$filepath ='';
	if($functionto == 'extra'){
		$filepath= '../extrafile/'.$file;
	}
	

	if (file_exists($filepath))
{
    $size = filesize($path);
	header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=".$file);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
	header('Content-Type:application/octet-stream');
    header('Pragma: public');
    ob_clean();
    flush();
    readfile($filepath); //showing the path to the server where the file is to be download
	exit;
}

?>