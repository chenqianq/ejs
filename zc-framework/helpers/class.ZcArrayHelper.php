<?php

class ZcArrayHelper {

	/**
	 * 过滤要更新的数组
	 *
	 * @param array $array
	 * @param array $columns
	 * @return array $ret
	 */
	public static function filterColumns($array, $columns = array()) {
		if (is_string($columns)) {
			$columns = explode(',', $columns);
		}
		$ret = array ();
		foreach ($array as $k => $v) {
			if (in_array($k, $columns)) {
				$ret[$k] = $v;
			}
		}
		return $ret;
	}

	public static function getSub(&$array, $keys) {
		if (is_string($keys)) {
			$keys = explode(',', $keys);
		}
		$kc = count($keys);
		$key = $keys[0];

		$ret = array ();
		foreach ($array as $arr) {
			if ($kc === 1) {
				$ret[] = $arr[$key];
			} else {
				$temp = array ();
				foreach ($keys as $k) {
					$temp[$k] = $arr[$k];
				}
				$ret[] = $temp;
			}
		}

		return $ret;
	}

	public static function changeKey(&$array, $keyColumn, $forceTwoLevel = false) {
		$hasTwoLevel = false;
		if ($forceTwoLevel) {
			$hasTwoLevel = true;
		} else {
			$allKeys = array ();
			foreach ($array as $arr) {
				$key = $arr[$keyColumn];
				if (in_array($key, $allKeys)) {
					$hasTwoLevel = true;
					break;
				}
				$allKeys[] = $key;
			}
		}

		$ret = array ();
		foreach ($array as $arr) {
			$key = $arr[$keyColumn];
			if ($hasTwoLevel) {
				$ret[$key][] = $arr;
			} else {
				$ret[$key] = $arr;
			}
		}

		return $ret;
	}

	public static function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			return array_map(array (
					'ZcArrayHelper',
					'objectToArray'
			), $d);
		} else {
			return $d;
		}
	}

	public static function arrayToObject($d) {
		if (is_array($d)) {
			return (object)array_map(array (
					'ZcArrayHelper',
					'arrayToObject'
			), $d);
		} else {
			return $d;
		}
	}

	public static function mapNameValue($array, $keyField, $valueField) {
		$ret = array();
		foreach ($array as $a) {
			$ret[$a[$keyField]] = $a[$valueField];
		}
		return $ret;
	}

	public static function buildReadableDebugTrace($sfs) {
		$outSfs = array();
		foreach ($sfs as $sf) {
			$outSfs[] = sprintf('%s[%d] %s%s%s', $sf['file'], $sf['line'], $sf['class'], $sf['type'], $sf['function']);
		}
		return $outSfs;
	}
}

