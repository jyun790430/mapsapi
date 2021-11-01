安裝套包
------------

在專案中執行下方指令:

    composer require jyun/mapsapi
    
安裝好後即可在專案內使用 :

```php
use Jyun\Mapsapi\TwddMap\Geocoding;
```

---

設定檔案
-----

在專案中有使遇到 Mysql, Mongo, Map8, GoogleMap, 都須個別配置 .env 請參照:
 
`目前 Mongo 因為資料太多錯誤決定停止使用` 
```php
Mysql, Mongo        # 自行配置
MAP8_API_KEY=""     # 圖霸 KEY
GOOGLE_API_KEY=""   # Google API KEY
```


### Directions API

```php
use Jyun\Mapsapi\TwddMap\Directions;

/**
 * Directions
 *
 * @param $origin
 * @param $destination
 * @param $mode ['driving', 'walking', 'bicycling'], default='driving'
 * @return array|mixed
*/
$directions = Directions::directions('25.0097038,121.4401783', '25.0108898,121.4346963');
```


### Geocoding API

Source 順序:
* geocode: Map8 -> GoogleMap (timeout = 2s)
* reverseGeocode: Mongo -> Map8 -> GoogleMap (timeout = 2s)

注意事項:
* Map8 超過台灣地區會回傳空值, 但設計上會繼續往 GoogleMap 查詢
* Source 查詢時可能回傳空值, 所以 zip, city_id, district_id 有可能為 null

```php
use Jyun\Mapsapi\TwddMap\Geocoding;

$geocode = Geocoding::geocode('台北市內湖區瑞光路335號');

$reverseGeocode = Geocoding::reverseGeocode('25.0396476673,121.505226616');
```

### API 統一回傳格式:
|參數|型別|說明|
|-------|--------|-----------------------------|
|source | string | 來源: Mongo, Map8, GoogleMap
|code   | int    | 按照 Http status: 200 為正常
|msg    | string | 回傳說明
|data   | array  | 若無資料則不回傳 Data
|trace  | array  | 若無追中錯誤則不回傳 trace


