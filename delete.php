#!/usr/bin/php
<?php
	rmdir_recursive("/home/mctom03/public_html/Project/uploads/example");
	unlink("/home/mctom03/public_html/Project/uploads/3gibson/example");

	function rmdir_recursive($dir) {
		foreach(scandir($dir) as $file) {
			if ('.' === $file || '..' === $file) continue;
			if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
			else unlink("$dir/$file");
		}
		rmdir($dir);
	}
?>