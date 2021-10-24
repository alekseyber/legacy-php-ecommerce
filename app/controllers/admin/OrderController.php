<?php
	
	namespace app\controllers\admin;
	
	use ishop\libs\Pagination;
	use app\models\admin\Orders;
	use ishop\libs\Mypdf;
	
	class OrderController extends AppController {
		
		public function indexAction() {
			$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
			$perpage = 50;
			
			$count = \R::count('orders');
			$pagination = new Pagination($page, $perpage, $count);
			$start = $pagination->getStart();
			$acquirers_id = $_GET['acquirers_id'] ?? 0;
			$acquirers_id = (int) $acquirers_id;
			$order_id = $_GET['order_id'] ?? 0;
			$order_id = (int) $order_id;
			$orderstatus_id = $_GET['orderstatus_id'] ?? 0;
			$orderstatus_id = (int) $orderstatus_id;
			$dat_deliv = $_GET['dat_deliv'] ?? '';
			$cupon_id = $_GET['cupon_id'] ?? 0;
			$phone = '';
			$where = '';
			$order_num = '';
			$arrwhere = [];
			
			if ($acquirers_id) {
				$phone = \R::getCell('SELECT `phone` FROM `acquirers` WHERE `id` = ?', [$acquirers_id]);
				if ($phone) {
					$where = "WHERE `orders`.`acquirers_id` = ?";
					$arrwhere = [$acquirers_id];
				}
			}
			if ($order_id) {
				$order_num = \R::getCell('SELECT `order_num` FROM `orders` WHERE `id` = ?', [$order_id]);
				if ($order_num) {
					$where = "WHERE `orders`.`id` = ?";
					$arrwhere = [$order_id];
				}
			}
			if ($orderstatus_id) {
				$where = "WHERE `orders`.`orderstatus_id` = $orderstatus_id";
				$arrwhere = [$orderstatus_id];
			}
			if ($dat_deliv) {
				$dat_sql = datexplrus($dat_deliv);
				
				$where = "WHERE `orders`.`dat_deliv` = ?";
				$arrwhere = [$dat_sql];
			}
			if ($cupon_id) {
				$where = "WHERE `orders`.`cupon_id` = $cupon_id";
				$arrwhere = [$cupon_id];
			}
			
			
			$orders = \R::getAll("SELECT `orders`.`id`, `orders`.`order_num`, `orders`.`orderstatus_id`, `orders`.`acquirers_id`, 
            `acquirers`.`status` AS acquirers_st, `orderstatus`.`status`, `orderstatus`.`action`, `orders`.`date`, 
            `orders`.`update_at`, `orders`.`currency`, `orders`.`phone`, `orders`.`name`, `orders`.`geo_city`, `orders`.`address`, 
            ROUND(SUM(`orderproduct`.`price`), 2) AS `sum` FROM `orders`
			JOIN `acquirers` ON `orders`.`acquirers_id` = `acquirers`.`id`
			JOIN `orderstatus` ON `orders`.`orderstatus_id` = `orderstatus`.`id`
			JOIN `orderproduct` ON `orders`.`id` = `orderproduct`.`order_id`
			$where GROUP BY `orders`.`id` ORDER BY `orderstatus`.`sortvalue` ASC, `orders`.`id` DESC LIMIT $start, $perpage", $arrwhere);
			$orderstatus = \R::getAssoc('SELECT `id`, `status` FROM `orderstatus` ORDER BY `sortvalue`');
			
			$this->setMeta('Список заказов');
			$this->set(compact('orders', 'pagination', 'count', 'orderstatus', 'phone', 'order_num', 'orderstatus_id', 'dat_deliv'));
		}
		
		public function typeaheadAction() {
			if ($this->isAjax()) {
				$query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
				if ($query) {
					$acquirers = \R::getAll("SELECT id, phone FROM acquirers WHERE phone LIKE ? LIMIT 11", ["%{$query}%"]);
					echo json_encode($acquirers);
				}
			}
			die;
		}
		
		public function typeaheadordernumAction() {
			if ($this->isAjax()) {
				$query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
				if ($query) {
					$orders = \R::getAll("SELECT id, order_num FROM orders WHERE order_num LIKE ? LIMIT 11", ["%{$query}%"]);
					echo json_encode($orders);
				}
			}
			die;
		}
		
		public function acquirersAction() {
			
			die;
		}
		
		public function changeStatusAction() {
			if (!empty($_POST)) {
				$id = $_POST['id'] ?? 0;
				$id = (int) $id;
				$value = $_POST['value'] ?? 0;
				$value = (int) $value;
				if ($id && $value) {
					$res = Orders::changeStatus($value, $id);
					} else {
					$res = ['succes' => false, 'title' => 'Данные не переданы', 'message' => 'Данные не переданы'];
				}
				exit(json_encode($res));
			}
			die;
		}
		
		public function viewAction() {
			$order_id = $this->getRequestID();
			$sql = "SELECT `acquirers`.`status` AS `blockl`, `orderstatus`.`action`, `orders`.*, ROUND(SUM(`orderproduct`.`price`), 2) AS `sum` FROM `orders`
			JOIN `acquirers` ON `orders`.`acquirers_id` = `acquirers`.`id`
			JOIN `orderstatus` ON `orders`.`orderstatus_id` = `orderstatus`.`id`
			JOIN `orderproduct` ON `orders`.`id` = `orderproduct`.`order_id`
			WHERE `orders`.`id` = ?
			GROUP BY `orders`.`id` ORDER BY `orderstatus`.`sortvalue`, `orders`.`id` LIMIT 1";
			$order = \R::getRow($sql, [$order_id]);
			if (!$order) {
				throw new \Exception('Страница не найдена', 404);
			}
			$order['cupon'] = '';
			if ($order['cupon_id']) {
				$data_cupon = \R::getRow('SELECT * FROM `cupons` WHERE `id` = 1 LIMIT 1', [$order['cupon_id']]);
				$order['cupon'] = $order['cupon_id'] . ' ' . $data_cupon['code'] . ' ' . $data_cupon['value'];
			}
			if ($order['dat_deliv'] == '0000-00-00') {
				$order['dat_deliv'] = '';
			}
			
			$order['dat_deliv'] = $order['dat_deliv'] ? datreturnexpl($order['dat_deliv']) : '';
			
			$order['orders_count'] = \R::count('orders', 'acquirers_id = ?', [$order['acquirers_id']]);
			$orders_acquirers = [];
			if ($order['orders_count'] > 1) {
				$orders_acquirers = \R::getAssoc('SELECT `orders`.`id`, `orders`.`order_num`, `orderstatus`.`status`  FROM `orders` JOIN `orderstatus` ON `orders`.`orderstatus_id` = `orderstatus`.`id` WHERE `acquirers_id` = ? ORDER BY `date` ASC', [$order['acquirers_id']]);
			}
			
			$orderstatus = \R::getAssoc('SELECT `id`, `status` FROM `orderstatus` ORDER BY `sortvalue`');
			$order_products = \R::findAll('orderproduct', "order_id = ?", [$order_id]);
			if ($order['delivery']) {
				$order['delivery'] = diveltostring(0, true, $ses = false, $order['delivery']);
			}
			if ($order['delivery_pvz']) {
				$order['delivery_pvz'] = diveltostring(0, true, $ses = false, $order['delivery_pvz']);
			}
			$order['kurier'] = Orders::itemKurier($order_id);
			$this->setMeta("Заказ №{$order['order_num']}");
			$this->set(compact('order', 'order_products', 'orderstatus', 'orders_acquirers'));
		}
		
		public function printAction() {
			$order_id = $this->getRequestID();
			$sql = "SELECT `acquirers`.`status` AS `blockl`, `orderstatus`.`action`, `orders`.*, ROUND(SUM(`orderproduct`.`price`), 2) AS `sum` FROM `orders`
			JOIN `acquirers` ON `orders`.`acquirers_id` = `acquirers`.`id`
			JOIN `orderstatus` ON `orders`.`orderstatus_id` = `orderstatus`.`id`
			JOIN `orderproduct` ON `orders`.`id` = `orderproduct`.`order_id`
			WHERE `orders`.`id` = ?
			GROUP BY `orders`.`id` ORDER BY `orderstatus`.`sortvalue`, `orders`.`id` LIMIT 1";
			$order = \R::getRow($sql, [$order_id]);
			if (!$order) {
				throw new \Exception('Страница не найдена', 404);
			}
			$order_products = \R::findAll('orderproduct', "order_id = ?", [$order_id]);
			$link = ADMIN . '/order/view?id=' . $order['id'];
			//$barcodeobj = new \TCPDF2DBarcode($link, 'QRCODE,H');
			$html = $this->loadViewContent('print', compact('order', 'order_products'));
			
			
			$namefile = 'order-' . $order['order_num'];
			$pdf = new Mypdf('P', 'mm', 'A4', true, 'UTF-8');
			$pdf->SetFont('dejavusans', '', 20, '', true);
			$pdf->SetMargins(8, 8, 8);
			$pdf->AddPage(); // Добавляем страницу
			$pdf->SetXY(45, 8); // Установка текущей точки (в мм)
			$pdf->SetDrawColor(100, 100, 0); // Установка цвета (RGB)
			$pdf->SetTextColor(0, 0, 0); // Установка цвета текста (RGB)
			/* Выводим надпись.
				- Ширина 30 мм
				- Высота 10 мм
				- Текст "Hello, World"
			*/
			
			$style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => false,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
			);
			$image_file = PATH . '/images/logo_winter_5.jpg';
			$pdf->Image($image_file, 8, 8, 40, '', 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
			//$pdf->Image($image_file, 8, 8, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			$link = ADMIN . '/order/view?id=' . $order['id'];
			$pdf->write1DBarcode($link, 'C128', '', '', '', 10, 0.2, $style, 'N');
			$pdf->SetXY(170, 8); // Установка текущей точки (в мм)
			$pdf->Cell(0, 0, $order['geo_city']);
			$pdf->SetXY(0, 20); // Установка текущей точки (в мм)
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->Output($namefile . '.pdf'); // Выводим в браузер
			die;
		}
		
		public function cartAction() {
			if ($this->isAjax()) {
				$id = $_REQUEST['id'] ?? 0;
				$id = (int) $id;
				if ($id) {
					$order_products = \R::findAll('orderproduct', "order_id = ?", [$id]);
					if ($order_products) {
						$summ = \R::getCell('SELECT SUM(`price`) AS summ FROM `orderproduct` WHERE `id` = ?', [$id]);
						$currency = \R::getCell('SELECT `currency` FROM `orders` WHERE `id` = ?', [$id]);
						$this->loadView('cart', compact('order_products', 'summ', 'currency'));
					} else
                    throw new \Exception('Страница не найдена', 404);
				}
			}
			
			die;
		}
		
		public function changeAction() {
			$data = $_POST;        
			$id = $data['id'] ?? 0;
			$id = (int) $id;
			
			if ($data && $id) {
				$order = new Orders();
				$data['update_at'] = date('Y-m-d H:i:s');
				if ($data['dat_deliv']) {
					$data['dat_deliv'] = datexplrus($data['dat_deliv']);
					$data['dat_deliv'] = $data['dat_deliv'] . ' 12:00:01';
				}
				$order->load($data);
				if (!$order->validate($data)) {
					$order->getErrors();
					$err = $_SESSION['error'];
					unset($_SESSION['error']);
					$res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
					exit(json_encode($res));
				}
				
				if ($order->update('orders', $id)) {
					//   debug($data,1);
					$newproduct = $order->saveCart($id, $data);
					$res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Заказ сохранен успешно', 'newproduct' => $newproduct, 'id' => $id];
					$res['kurier'] = Orders::itemKurier($id);
					exit(json_encode($res));
					} else {
					$res = ['succes' => false, 'error' => 'Ошибка БД'];
					exit(json_encode($res));
				}
			}
			die;
		}
		
		public function newProductAction() {
			/* $data = [
				'items' => [
				[
				'id' => 1,
				'text' => 'Товар 1',
				],
				[
				'id' => 2,
				'text' => 'Товар 2',
				],
				]
			]; */
			$tabl = 'indexproduct';
			$q = isset($_GET['q']) ? $_GET['q'] : '';
			$data['items'] = [];
			
			$result = \R::getAssoc('SELECT ids, title FROM ' . $tabl . ' WHERE sortvalue > 1 AND title LIKE ? LIMIT 10', ["%{$q}%"]);
			if ($result) {
				$i = 0;
				foreach ($result as $id => $title) {
					$res = ['key' => $id, 'title' => $title];
					$res = json_encode($res);
					$data['items'][$i]['id'] = $res;
					$data['items'][$i]['text'] = $title;
					$i++;
				}
			}
			echo json_encode($data);
			die;
		}
		
		public function deleteAction() {
			$order_id = $this->getRequestID();
			$order = \R::load('orders', $order_id);
			if ($order) {
				Orders::changeStatus(5, $order_id);
			}
			\R::trash($order);
			$_SESSION['success'] = 'Заказ удален';
			redirect(ADMIN . '/order');
		}
		
		public function copyContactAction() {
			$id = $_GET['id'] ?? 0;
			$old_id = $_GET['old_id'] ?? 0;
			$id = (int) $id;
			$old_id = (int) $old_id;
			
			if ($id && $old_id) {
				Orders::copyContact($id, $old_id);
			}
			
			redirect();
		}
		
	}
