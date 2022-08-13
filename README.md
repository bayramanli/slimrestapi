# PHP Slim Framework RESTfull API

### Veritabanı bağlantısı ve bilgileri
Veritabanı bağlantısı için ```src/config/config.php``` dosyasında, aşağıdaki bilgiler düzenlenmelidir.

```php
define('DBHOST','localhost'); // veritabanı sunucu adı
define('DBUSER','root'); // veritabanı kullanıcı adı
define('DBPASS',''); // veritabanı şifre
define('DBNAME','slimrestapi'); // veritabanı adı
```

### POSTMAN Collection
```
Slim Rest Api.postman_collection.json dosyası POSTMAN'e yüklenerek test edilebilir.
```
### API Links:
```
Sipariş Listeleme:
Link: slimrestapi/api/orders
Metod: GET

Sipariş Detayı:
Link: slimrestapi/api/orders/4
Metod: GET

Sipariş Ekleme:
Link: slimrestapi/api/orders/add
Metod: POST

Sipariş Silme:
Link: slimrestapi/api/orders/9
Metod: DELETE

Tüm Siparişler için İndirimleri Listeleme
Link: slimrestapi/api/discounts
Metod: GET

Tek Sipariş için İndirimleri Listeleme
Link: slimrestapi/api/discounts/4
Metod: GET
```

### Sipariş Listeleme
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/siparis_listeleme.PNG)

### Sipariş Detayı
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/siparis_detayı.PNG)

### Sipariş Ekleme
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/siparis_ekleme.PNG)

### Sipariş Silme
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/siparis_silme.PNG)

### Tüm Siparişler için indirimleri listeleme
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/tum_siparis_inidirim_listeleme.PNG)

### Tek Sipariş için indirimleri Listeleme
![slimrestapi](https://github.com/bayramanli/slimrestapi/blob/master/images/tek_siparis_indirim_listeleme.PNG)
