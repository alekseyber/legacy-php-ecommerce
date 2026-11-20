<?php
	
	namespace app\controllers;
	
	use app\models\Cart;
	use app\models\Order;
	use app\widgets\greatjs\Greatjs;
	use app\models\Product;
	use ishop\App;
	use app\widgets\filter\Filter;
	use app\widgets\delivery\Delivery;
	
	class CartController extends AppController {
		
		public function addAction() {
			
			
			$data = !empty($_POST['cardData']) ? $_POST['cardData'] : null;
			$cardData['pid'] = $data['pid'] ?? 0;
			// $cardData['nosizeselect'] = $data['nosizeselect'] ? 1 : 0;
			$cardData['nosizeselect'] = $data['nosizeselect'] ?? 0;
			$cardData['product_model'] = $data['product_model'] ?? 0;
			$cardData['edit'] = $data['edit'] ?? 0;
			if (!$cardData['pid'] || !$cardData['product_model']) {
				if ($this->json) {
					exit(json_encode([
                    'message' => 'Данные продукта не переданы',
                    'succes' => false,
                    'content' => '',
					]));
				}
				$_SESSION['error'] = 'Ошибка, попробуйте позже.';
				redirect();
			}
			
			$cardData['pid'] = (int) $cardData['pid'];
			$cardData['product_model'] = (int) $cardData['product_model'];
			$cardData['nosizeselect'] = (int) $cardData['nosizeselect'];
			$cardData['edit'] = (int) $cardData['edit'];
			
			$product = Product::getProductAddCard($cardData);
			
			if (!$product) {
				if ($this->json) {
					
					exit(json_encode([
                    'message' => 'Продукт, переданный в корзину, не найден в базе',
                    'succes' => false,
                    'content' => '',
					]));
					//                throw new \Exception('Продукт, переданный в корзину, не найден в базе', 500);
					} else {
					$_SESSION['error'] = 'Ошибка, попробуйте позже.';
					redirect();
				}
			}
			
			$text = 'добавлен в корзину.';
			
			$cart = new Cart();
			$ID = $cart->addToCart($product);
			
			if ($cardData['edit'] && $cardData['edit'] != $ID) {
				$cart->deleteItem($cardData['edit']);
				$text = 'обновлен в корзине.';
			}
			
			$cart = $_SESSION['cart'];
			asort($cart);
			$_SESSION['cart'] = $cart;
			
			if ($this->json) {
				exit(json_encode([
                'content' => $this->loadViewContent('cartadd_modal', compact('ID')),
                'succes' => true,
                'message' => 'Продукт: ' . $product['title'] . ' ' . $text,
				]));
				
				// $this->loadView('cartadd_modal', compact('ID'));
			}
			$_SESSION['success'] = 'Продукт: <strong>' . $product['title'] . '</strong> ' . $text;
			redirect();
		}
		
		public function addfidAction() {
			$id = $_GET['id'] ?? 0;
			$id = (int) $id;
			if (!$id) {
				$_SESSION['error'] = 'Ошибка, товар не передан в корзину.';
				redirect(PATH . '/cart/view');
			}
			$sql_part = 'SELECT product_id, modification_id, modificationregistr_id, product_model FROM registr '
			. 'JOIN product ON registr.product_id = product.id WHERE registr.id = ? LIMIT 1';
			$data = \R::getRow($sql_part, [$id]);
			if (!$data) {
				$_SESSION['error'] = 'Ошибка, товар не найден в БД.';
				redirect(PATH . '/cart/view');
			}
			if ($data['product_model'] == '1' || $data['product_model'] == '4') {
				$cardData['pid'] = $data['modificationregistr_id'];
				} else {
				$cardData['pid'] = $data['modification_id'];
			}
			$cardData['product_model'] = $data['product_model'];
			$cardData['edit'] = 0;
			$product = Product::getProductAddCard($cardData);
			
			if (!$product) {
				$_SESSION['error'] = 'Ошибка, попробуйте позже.';
				redirect(PATH . '/cart/view');
			}
			$text = 'добавлен в корзину.';
			$cart = new Cart();
			$ID = $cart->addToCart($product);
			$cart = $_SESSION['cart'];
			asort($cart);
			$_SESSION['cart'] = $cart;
			$_SESSION['success'] = 'Продукт: <strong>' . $product['title'] . '</strong> ' . $text;
			redirect(PATH . '/cart/view');
		}
		
		public function showAction() {
			if ($this->json) {
				exit(json_encode(['content' => $this->loadViewContent('cart_modal')]));
			}
			$this->loadView('cart_modal');
		}
		
		public function cartminiAction() {
			if ($this->json) {
				exit(json_encode([
                'content' => $this->loadViewContent('cart_mini'),
                'succes' => true,
                'message' => '',
				]));
				
				//$this->loadView('cart_mini');
			}
			throw new \Exception('Страница не найдена', 404);
		}
		
		public function cartAction() {
			if ($this->json) {
				$delifinf = isset($_GET['delifinf']);
				if ($delifinf) {
					// $this->loadView('delifinf');
					exit(json_encode(['content' => $this->loadViewContent('delifinf')]));
				}
				exit(json_encode(['content' => $this->loadViewContent('cart')]));
				
				//  $this->loadView('cart');
			}
			throw new \Exception('Страница не найдена', 404);
		}
		
		public function deleteAction() {
			$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
			if (isset($_SESSION['cart'][$id])) {
				$cart = new Cart();
				$cart->deleteItem($id);
			}
			if ($this->json) {
				exit(json_encode(['succes' => true, 'message' => 'Успех']));
			}
			redirect();
		}
		
		public function changeitemqtyAction() {
			$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
			$qty = !empty($_GET['qty']) ? (int) $_GET['qty'] : null;
			if (isset($_SESSION['cart'][$id])) {
				$cart = new Cart();
				$cart->changeqtyItem($id, $qty);
			}
			if ($this->json) {
				exit(json_encode(['succes' => true, 'message' => 'Успех']));
			}
			redirect();
		}
		
		public function clearAction() {
			unset($_SESSION['cart']);
			unset($_SESSION['cart.qty']);
			unset($_SESSION['cart.sum']);
			unset($_SESSION['cart.currency']);
			if ($this->json) {
				exit(json_encode(['succes' => true, 'message' => 'Успех']));
			}
			redirect();
		}
		
		public function cuponAction() {
			
			if ($this->json) {
				$code = !empty($_POST['cupon_name']) ? $_POST['cupon_name'] : '';
				$rezult = Cart::promocod($code);
				exit(json_encode($rezult));
				} else {
				$code = !empty($_POST['cupon_name']) ? $_POST['cupon_name'] : '';
				$rezult = Cart::promocod($code);
				if ($rezult['succes']) {
					$_SESSION['success'] = $rezult['message'];
					} else {
					$_SESSION['error'] = $rezult['message'];
				}
				
				redirect();
			}
		}
		
		public function viewAction() {
			
			$product_ids = App::$app->getProperty('product_ids');
			$uri = App::$app->getProperty('filter_default_uri');
			$leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
			]);
			
			new Delivery(['addHtml' => false]);
			
			$code = !empty($_GET['cupon']) ? $_GET['cupon'] : '';
			//debug($code, 1);
			if ($code) {
				Cart::promocod($code);
			} 
			
			if ($this->json) {
				
				$res = [
                'content' => $this->loadViewContent('view'),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
				];
				exit(json_encode($res));
			}
			
			App::$app->setProperty('filter_content', $leftFilter);
			$this->setMeta('Корзина');
		}
		
		public function orderdoneAction() {
			
			if (isset($_SESSION['order_succes'])) {
				$order_succes = $_SESSION['order_succes'];
				unset($_SESSION['order_succes']);            
				
				$order_num = $order_succes['order_num'];
				$cart_sum = $order_succes['cart_sum'];
				$cart_currency = $order_succes['cart_currency'];
				$cart_data = $order_succes['cart_data'];            
				
				$message = 'Заказ № ' . $order_num . ' принят.';
				
				$args = [$order_num, $cart_sum, $cart_currency, $cart_data];
				$argsNames = ["order_num", "cart_sum", "cart_currency", "cart_data"];
				$res = \R::findOne('headerstr', "current = 1");
				$orderdoneJs = $res ? $res->orderdone : '';
				$js = new Greatjs($args, $argsNames, $orderdoneJs);
				
				
				// $product_ids = App::$app->getProperty('product_ids');
				// $uri = App::$app->getProperty('filter_default_uri');
				// $leftFilter = new Filter([
				//     'ids' => $product_ids,
				//     'uri' => $uri,
				// ]);
				
				
				if ($this->json) {
					
					$rezult = [
                    'content' => $this->loadViewContent('orderdone', compact('order_num', 'cart_data', 'cart_currency')),
                    'succes' => true,
                    'message' => $message,
                    'historyset' => 1,
                    'scrolltop' => 1,
                    'filter' => '',
                    'js' => $js->getJsData(),
					];
					exit(json_encode($rezult));
				}
				
				$this->setJs($js, false, 'Order Done', true);
				$this->setMeta($message);
				$this->set(compact('order_num', 'cart_data', 'cart_currency'));
				} else {
				if ($this->json) {
					exit(json_encode(['succes' => false, 'message' => 'Заказ не отправлен']));
				}
				redirect();
			}
		}
		
		public function checkoutAction() {
			//        if (empty($_SESSION['cart'])) {
			//            redirect();
			//        }
			$orderDone = false;
			$message = 'Корзина пуста или данные не переданы';
			if (!empty($_POST) && !empty($_SESSION['cart'])) {
				$order = new Order();
				$data = $_POST;
				$order->load($data);
				$valid = true;
				if (!$order->validate($data)) {
					$order->getErrors();
					$_SESSION['form_data'] = $data;
					$valid = false;
					$message = 'Данные не валидны';
					// redirect();
				}
				if ($valid) {
					if (!isset($_SESSION['geo_data'])) {
						if (App::$app->getProperty('detectadresip_on')) {
							$geo = \ishop\Geoip::instance();
							$geo_data = $geo->get();
							} else {
							$geo_data = \ishop\Geoip::getIp();
						}
						$_SESSION['geo_data'] = $geo_data;
					}
					
					$order->hChek();
					// сохранение заказа
					// Аудит 2026-11: результат сохранения проверяется. Раньше при сбое
					// транзакции клиент всё равно видел подтверждение, а админ получал письмо.
					if ($order->saveOrder()) {
						$order->mailOrder();
						$orderDone = true;
						$message = 'Заказ № ' . $_SESSION['order_num'] . ' принят.';
						
						$order_succes = [
                    'order_num' => $_SESSION['order_num'],
                    'cart_sum' => $_SESSION['cart.sum'],
                    'cart_currency' => $_SESSION['cart.currency'],
                    'cart_data' => $_SESSION['cart']
						];
						$_SESSION['order_succes'] = $order_succes;
						unset($_SESSION['order_num']);
						unset($_SESSION['cart']);
						unset($_SESSION['cart.qty']);
						unset($_SESSION['cart.sum']);
						unset($_SESSION['cart.currency']);
					} else {
						// корзину не чистим: человек не должен потерять набранное из-за сбоя
						$orderDone = false;
						$message = 'Не удалось оформить заказ. Попробуйте ещё раз или позвоните нам — корзина сохранена.';
						$_SESSION['error'] = $message;
						$_SESSION['form_data'] = $data;
					}
				}
			}
			
			if ($this->json) {
				
				$rezult = [
                'content' => '',
                'succes' => $orderDone,
                'message' => $message
				];
				
				if ($orderDone) {
					$rezult['location'] = '/cart/orderdone';
					$rezult['clearSuccess'] = true;
					$rezult['qorder'] = isset($_POST['qorder']);
					$rezult['order'] = true;
				}
				
				exit(json_encode($rezult));
			}
			
			if ($orderDone) {
				redirect(PATH . '/cart/orderdone');
			}
			redirect();
		}
		
	}
