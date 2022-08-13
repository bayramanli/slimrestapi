<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//tüm siparişler için indirimleri hesaplama...
$app->get('/discounts', function (Request $request, Response $response) {

	$db = new DB();
	$data = [];
	try{
		$sql = $db->read("orders");
		$orders = $sql->fetchAll(PDO::FETCH_ASSOC);

		foreach($orders as $key1 => $value) {
			$data[$key1]["orderId"] = $value["orders_id"];
			$data[$key1]["discounts"] = [];
			$total = 0;
			$total_discounts = 0;
			$category1 = 0;
			$category1_price = 0;
			$sql = $db->wread("orders_products", "orders_id", $value["orders_id"]);
			$products = $sql->fetchAll(PDO::FETCH_ASSOC);
			foreach($products as $key2 => $value) {
				$sql = $db->wread("products", "products_id", $value["products_id"]);
				$product = $sql->fetch(PDO::FETCH_ASSOC);
				//2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
				if($product["category_id"] == 2 && $value["quantity"] >= 6) {
					$discount["discountReason"] = "BUY_5_GET_1";
					$discount["discountAmount"] = $value["unit_price"];
					$discount["subTotal"] = ($value["quantity"] - 1)*$value["unit_price"];
					array_push($data[$key1]["discounts"], $discount);
					$total_discounts += $value["unit_price"];
				}

				//1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
				if($product["category_id"] == 1) {
					$category1 += $value["quantity"];
					if($category1_price == 0) {
						$category1_price = $value["quantity"]*$value["unit_price"];
					} else {
						if($category1_price > ($value["quantity"]*$value["unit_price"])) {
							$category1_price = $value["quantity"]*$value["unit_price"];
						}
					}
				}

				$total += $value["quantity"]*$value["unit_price"]; 
			}

			//Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
			if($total >= 1000) {
				$discount["discountReason"] = "10_PERCENT_OVER_1000";
				$discount["discountAmount"] = $total*0.10;
				$discount["subTotal"] = $total - $total*0.10;
				array_push($data[$key1]["discounts"],$discount);
				$total_discounts += $total*0.10;
			}

			//1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
			if($category1 >= 2) {
				$discount["discountReason"] = "BUY_2_PERCENT_20";
				$discount["discountAmount"] = $category1_price*0.20;
				$discount["subTotal"] = $total - $category1_price*0.20;
				array_push($data[$key1]["discounts"],$discount);
				$total_discounts += $category1_price*0.20;
			}

			$data[$key1]["totalDiscount"] = $total_discounts;
			$data[$key1]["discountedTotal"] = $total - $total_discounts;
		}

		return $response
		->withStatus(200)
		->withHeader("Content-Type", 'application/json')
		->withJson($data);

	}catch(PDOException $e){
		return $response->withJson(
			array(
				"error" => array(
					"message"  => $e->getMessage(),
					"code"  => $e->getCode()
				)
			)
		);
	}
	$db = null;
});

//tek bir sipariş için indirimleri hesaplama...
$app->get('/discounts/{id}', function (Request $request, Response $response) {
	$id = $request->getAttribute("id");
	$db = new DB();
	$data = [];
	try{
		$sql = $db->wread("orders", "orders_id", $id);

		if($sql->rowCount() > 0) {
			$order = $sql->fetch(PDO::FETCH_ASSOC);
			$data["orderId"] = $order["orders_id"];
			$data["discounts"] = [];
			$total = 0;
			$total_discounts = 0;
			$category1 = 0;
			$category1_price = 0;
			$sql = $db->wread("orders_products", "orders_id", $order["orders_id"]);
			$products = $sql->fetchAll(PDO::FETCH_ASSOC);
			foreach($products as $key2 => $value) {
				$sql = $db->wread("products", "products_id", $value["products_id"]);
				$product = $sql->fetch(PDO::FETCH_ASSOC);
				//2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
				if($product["category_id"] == 2 && $value["quantity"] >= 6) {
					$discount["discountReason"] = "BUY_5_GET_1";
					$discount["discountAmount"] = $value["unit_price"];
					$discount["subTotal"] = ($value["quantity"] - 1)*$value["unit_price"];
					array_push($data["discounts"], $discount);
					$total_discounts += $value["unit_price"];
				}

				//1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
				if($product["category_id"] == 1) {
					$category1 += $value["quantity"];
					if($category1_price == 0) {
						$category1_price = $value["quantity"]*$value["unit_price"];
					} else {
						if($category1_price > ($value["quantity"]*$value["unit_price"])) {
							$category1_price = $value["quantity"]*$value["unit_price"];
						}
					}
				}

				$total += $value["quantity"]*$value["unit_price"]; 
			}

			//Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
			if($total >= 1000) {
				$discount["discountReason"] = "10_PERCENT_OVER_1000";
				$discount["discountAmount"] = $total*0.10;
				$discount["subTotal"] = $total - $total*0.10;
				array_push($data["discounts"],$discount);
				$total_discounts += $total*0.10;
			}

			//1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
			if($category1 >= 2) {
				$discount["discountReason"] = "BUY_2_PERCENT_20";
				$discount["discountAmount"] = $category1_price*0.20;
				$discount["subTotal"] = $total - $category1_price*0.20;
				array_push($data["discounts"],$discount);
				$total_discounts += $category1_price*0.20;
			}

			$data["totalDiscount"] = $total_discounts;
			$data["discountedTotal"] = $total - $total_discounts;

			return $response->withStatus(200)->withHeader("Content-Type", 'application/json')->withJson($data);
		} else {
			return $response->withJson(
                array(
                    "error" => array(
                        "message" => "Kayıt bulunamadı."
                    )
                )
            );
		}

	}catch(PDOException $e){
		return $response->withJson(
			array(
				"error" => array(
					"message"  => $e->getMessage(),
					"code"  => $e->getCode()
				)
			)
		);
	}
	$db = null;
});

