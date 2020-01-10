<?php
	$patt = [1,2,3];
	function test($patt) {
		return $patt[0].($patt[1]+1);
	}
	echo test($patt);
