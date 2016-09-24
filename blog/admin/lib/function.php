<?php

/**
 * 深度转义数据
 * @param  array $input 待转义的数组，通常为$_GET, _POST
 * @return array        处理过的数组
 */
function deepEscape($input) {

	foreach($input as $key => $value) {
		if (is_array($value)) {
			$input[$key] = deepEscape($value);
		} else {
			$input[$key] = addslashes($value);
		}
	}
	return $input;
}