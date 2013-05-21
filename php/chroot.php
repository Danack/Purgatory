
function rebaseSocketName($socketName){
	$cwd = getcwd();

	if(strpos($cwd, CHROOT) !== false){
		$socketName = CHROOT.$socketName;
	}

	return $socketName;
}


function getRebasedSysTempName(){

	$fileTest = sys_get_temp_dir();

	$cwd = getcwd();

	if(strpos($cwd, CHROOT) !== false){
		$socketName = CHROOT.$fileTest;
	}

	return $socketName;
}


