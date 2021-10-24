<?php
	
	function debug($arr, $die = false) {
		echo '<pre>' . print_r($arr, true) . '</pre>';
		if ($die)
        die;
	}
	
	function redirect($http = false) {
		if ($http) {
			$redirect = $http;
			} else {
			$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
		}
		header("Location: $redirect");
		exit;
	}
	
	function h($str) {
		return htmlspecialchars($str, ENT_QUOTES);
	}
	
	function prRep($data = [], $pattern = []) {
		foreach ($data as &$v) {
			foreach ($pattern as $key => $val) {
				$v = preg_replace('#@' . $key . '@#', $val, $v);
			}
		}
		
		return $data;
	}
	
	function detectIp() {
		$ip = '';
		$client = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote = @$_SERVER['REMOTE_ADDR'];
		
		if (filter_var($client, FILTER_VALIDATE_IP))
        $ip = $client;
		elseif (filter_var($forward, FILTER_VALIDATE_IP))
        $ip = $forward;
		else
        $ip = $remote;
		
		return $ip;
	}
	
	function phoneFormat($phone) {
		$phone = preg_replace('#[^\d]+#', '', $phone);
		if (substr($phone, 0, 1) == 8)
        $phone[0] = 7;
		return $phone;
	}
	
	function words($words, $arr = true, $min_len = 2) {
		$arr = (int) $arr;
		
		$rezult = $arr ? [] : '';
		$words = mb_strtolower($words);
		$words = preg_replace("/\.|~|,|;|:|!|\?|@|\"/", "", $words);
		$words = preg_replace("/\t|\n|\r|x0B/", " ", $words);
		$words = preg_replace("/ {2,}/", " ", $words);
		
		if ($words == "")
        return $rezult;
		
		$array_words = explode(" ", $words);
		$temp = array();
		
		foreach ($array_words as $value) {
			if (mb_strlen($value) >= $min_len) {
				$temp[$value] = $value;
			}
		}
		
		$array_words = $temp;
		asort($array_words);
		
		$temp_words = '';
		$words = '';
		if (!$arr && $array_words) {
			foreach ($array_words as $value) {
				$temp_words .= $value . ' ';
			}
			$temp_words = rtrim($temp_words, ' ');
		}
		$words = $temp_words;
		if ((count($array_words) === 0 && $arr == 1) || ($arr == 0 && $words == ''))
        return $rezult;
		
		
		if ($arr == 1) {
			$rezult = $array_words;
			} else {
			$rezult = $words;
		}
		
		return $rezult;
	}
	
	function getWord($number = 0, $suffix = ["вариант", "варианта", "вариантов"]) {
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number % 100;
		$suffix_key = ($mod > 7 && $mod < 20) ? 2 : $keys[min($mod % 10, 5)];
		return $suffix[$suffix_key];
	}
	
	function datexpl($dat = '2018-09-25') {
		$arr = explode('-', $dat);
		return "$arr[2].$arr[1].$arr[0]";
	}
	
	function datreturnexpl($dat = '2018-09-25') {
		$arr = explode('-', $dat);
		return $arr[2] . '.' . $arr[1] . '.' . $arr[0];
	}
	
	function dattimereturnexpl($dat = '2018-08-19 00:20:42') {
		$arr_all = explode(' ', $dat);
		$arr = explode('-', $arr_all[0]);
		$arr_time = explode(':', $arr_all[1]);
		return $arr[2] . '.' . $arr[1] . '.' . $arr[0] . ' ' . $arr_time[0] . ':' . $arr_time[1];
	}
	
	function datexplrus($dat = '19.08.2018') {
		$arr = explode('.', $dat);
		return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
	}
	
	function diveltostring($params = 0, $full = false, $ses = true, $dann = '') {
		$params = (int) $params;
		
		
		if ($params > 1) {
			return false;
		}
		$arr = ['delivery', 'delivery_pvz'];
		if ($ses) {
			$data = $_SESSION[$arr[$params]] ?? null;
			} else {
			$data = $dann;
		}
		
		if (!$data) {
			return false;
		}
		
		$data = unserialize($data);
		if ($full) {
			$rezultfull = '';
			foreach ($data as $k => $v) {
				$rezultfull .= $k . ': ' . $v . ', ';
			}
			$rezultfull = rtrim($rezultfull, ', ');
			return $rezultfull;
		}
		$symbol_left = $_SESSION['cart.currency']['symbol_left'] ?? '';
		$symbol_right = $_SESSION['cart.currency']['symbol_right'] ?? '';
		if ($params == 0) {
			if ($data['receiverCityId'] == 44 || $data['err']) {
				return false;
			}
			$null_city_delivery = \ishop\App::$app->getProperty('null_city_delivery');
			if (isset($null_city_delivery[$data['receiverCityId']])) {
				return false;
			}
			$rezult = 'Стоимость курьерской доставки в город: <span class="fw-bold">' . $data['geo_city'] . '</span> - <span class="fw-bold">' . $symbol_left . $data['price'] . ' ' . $symbol_right . '</span>. Диапазон даты доставки: <span class="fw-bold">' . $data['deliveryDateMin'] . ' - ' . $data['deliveryDateMax'] . '</span>.';
			$rezult .= ' Город и способ доставки можно изменить в момент подтверждения заказа менеджером.';
			} elseif ($params == 1) {
			$rezult = 'Стоимость доставки до пункта выдачи заказов в городе: <span class="fw-bold">' . $data['cityName'] . '</span> - <span class="fw-bold">' . $symbol_left . $data['price'] . ' ' . $symbol_right . '</span>. Выбран ПВЗ по адресу: <span class="fw-bold">' . $data['adress'] . '</span>.';
			$rezult .= ' Режим работы: ' . $data['workTime'] . '. Диапазон даты доставки соответствует курьерской. ';
			$rezult .= 'ПВЗ можно изменить в момент подтверждения заказа менеджером.';
		}
		
		return $rezult;
	}
	
	function delSef($alias = '') {
		if (!$alias)
        return false;
		
		$sef_id = \R::getCell('SELECT `id` FROM `sef` WHERE `alias` = ?', [$alias]);
		$sef = \R::load('sef', $sef_id);
		if ($sef) {
			\R::trash($sef);
			return true;
			} else {
			return false;
		}
	}
	
	function my_mb_ucfirst($str) {
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc . mb_substr($str, 1);
	}
	
	function uid_social_link($uid = '', $provider = 'vk') {
		if (!$uid) {
			return '/';
		}
		if ($provider == 'vk') {
			return '//vk.com/id' . $uid;
		}
		return '//www.facebook.com/' . $uid;
	}
	
	function file_get_img_file_curl($url, $file) {
		$rezult = false;
		if ($url) {
			try {
				$http_code = 0;
				$ch = curl_init($url);
				$fp = fopen($file, "w");
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				if (!curl_errno($ch)) {
					$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				}
				curl_close($ch);
				fclose($fp);
				if ($http_code == 200) {
					$rezult = true;
					} else {
					unlink($file);
				}
				} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}
		return $rezult;
	}
	
	function imgSrcToWebP($src) {
		$pattern = "/\.(png|jpg|gif)/i";
		$newSrc = preg_replace($pattern, ".webp", $src);
		return $newSrc;
	}
	
	function getTypeImg($src) {
		$rezult = 'image/jpeg';
		
		$extSuccess = [
        'png' => 'image/png',
        'gif' => 'image/gif'
		];
		
		foreach ($extSuccess as $ext => $type) {
			$pattern = "/\.$ext$/i";
			if (preg_match($pattern, $src)) {
				return $type;
			}
		}
		
		return $rezult;
	}
	
	function getDiscontProc() {
		$discont = 0;
		
		if (!empty($_SESSION['promo'])) {
			if($_SESSION['promo']) {
				$discont = 100 - $_SESSION['promo'] * 100;
			}			
		}
		
		return $discont;
	}
	
	function getDiscontPrice($price = 0) {		
		
		if (!empty($_SESSION['promo'])) {
			if($_SESSION['promo']) {
				$price =  $price * $_SESSION['promo'];
			}			
		}
		
		return $price;
	}
