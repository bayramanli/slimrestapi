<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// tüm sipariş listesini getir...
$app->get('/orders', function (Request $request, Response $response) {

    $db = new DB();
    $data = [];
    try{
        $sql = $db->read("orders");
        $orders = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach($orders as $key1 => $value) {
            $data[$key1]["id"] = $value["orders_id"];
            $data[$key1]["customerId"] = $value["users_id"];
            $total = 0;
            $sql = $db->wread("orders_products", "orders_id", $value["orders_id"]);
            $products = $sql->fetchAll(PDO::FETCH_ASSOC);
            foreach($products as $key2 => $value) {
                $data[$key1]["items"][$key2]["productId"] = $value["products_id"];
                $data[$key1]["items"][$key2]["quantity"] = $value["quantity"];
                $data[$key1]["items"][$key2]["unitPrice"] = $value["unit_price"];
                $data[$key1]["items"][$key2]["total"] = $value["quantity"]*$value["unit_price"];
                $total += $value["quantity"]*$value["unit_price"]; 
            }
            $data[$key1]["total"] = $total;
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

// sipariş detayı
$app->get('/orders/{id}', function (Request $request, Response $response) {
    $data = [];
    $id = $request->getAttribute("id");
    $db = new Db();
    try{
        $sql = $db->wread("orders", "orders_id", $id);
        if($sql->rowCount() > 0) {
            $order = $sql->fetch(PDO::FETCH_ASSOC);

            $data["id"] = $order["orders_id"];
            $data["customerId"] = $order["users_id"];
            $total = 0;
            
            $sql = $db->wread("orders_products", "orders_id", $order["orders_id"]);
            $products = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($products as $key => $value) {
                $data["items"][$key]["productId"] = $value["products_id"];
                $data["items"][$key]["quantity"] = $value["quantity"];
                $data["items"][$key]["unitPrice"] = $value["unit_price"];
                $data["items"][$key]["total"] = $value["quantity"]*$value["unit_price"];
                $total += $value["quantity"]*$value["unit_price"]; 
            }
            $data["total"] = $total;

            return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json')
            ->withJson($data);
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

// yeni sipariş ekle...
$app->post('/orders/add', function (Request $request, Response $response) {
    $retCode = true;
    $message = "";
    $users_id = $request->getParam("customerId");
    $orders_description = $request->getParam("description");
    $items = $request->getParam("items");

    $db = new Db();
    try{
        if(count($items) < 0) {
            $retCode = false;
            $message = "Lütfen sipariş için ürün seçiniz...";
        }
        //products control
        foreach ($items as $key => $value) {
            $sql = $db->wread("products", "products_id", $value["productId"]);
            if($sql->rowCount() > 0) {
                $product = $sql->fetch(PDO::FETCH_ASSOC);
                if($value["quantity"] > $product["stock"]) {
                    $retCode = false;
                    $message = "Ürün stoğu yeterli değildir...";
                    break;
                }
            } else {
                $retCode = false;
                $message = "Ürün Bulunamadı...";
                break;
            }
        }
        
        if($retCode) {
            $values = ["users_id" => $users_id, "orders_description" => $orders_description];

            $sql = $db->insert("orders", $values);

            if($sql["status"]) {
                //$count = 0;
                foreach ($items as $key => $value) {
                    $values = [
                        "products_id" => $value["productId"],
                        "orders_id" => $sql["id"],
                        "quantity" => $value["quantity"],
                        "unit_price" => $value["unitPrice"],
                        "total_price" => $value["unitPrice"]*$value["quantity"]
                    ];
                    $sql = $db->insert("orders_products", $values);
                }
                if($sql["status"]){
                    $retCode = true;
                    $message = "Sipariş başarılı bir şekilde eklenmiştir...";

                } else {
                    $retCode = false;
                    $message = "Sipariş ekleme işlemi sırasında bir hata oluştu.";
                }
            } else {
                $retCode = false;
                $message = "Sipariş ekleme işlemi sırasında bir hata oluştu...";
            }
        }

        if($retCode) {
            return $response->withStatus(200)->withHeader("Content-Type", 'application/json')->withJson(array("message"  => "Sipariş başarılı bir şekilde eklenmiştir.."));
        } else {
            return $response->withStatus(500)->withHeader("Content-Type", 'application/json')
            ->withJson(array(
                "error" => array(
                    "message"  => $message
                )
            ));
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

// siparişi sil
$app->delete('/orders/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute("id");

    $db = new Db();
    $retCode = true;
    try{
        $sql = $db->wread("orders_products", "orders_id", $id);
        $products = $sql->fetchAll(PDO::FETCH_ASSOC);
            
        foreach($products as $key => $value) {
            $sql = $db->delete("orders_products", "id", $value["id"]);
            if(!$sql) {
                $retCode = false;
                break;
            }
        }

        if($retCode) {
            $sql = $db->delete("orders", "orders_id", $id);
            if(!$sql["status"]) {
                $retCode = false;
            }
        }

        if($retCode){
            return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json')
            ->withJson(array(
                "text"  => "Sipariş başarılı bir şekilde silinmiştir.."
            ));

        } else {
            return $response
            ->withStatus(500)
            ->withHeader("Content-Type", 'application/json')
            ->withJson(array(
                "error" => array(
                    "text"  => "Silme işlemi sırasında bir hata oluştu."
                )
            ));
        }

    }catch(PDOException $e){
        return $response->withJson(
            array(
                "error" => array(
                    "text"  => $e->getMessage(),
                    "code"  => $e->getCode()
                )
            )
        );
    }
    $db = null;
});
