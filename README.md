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

```php
use Jyun\Mapsapi\TwddMap\Geocoding;

$geocode = Geocoding::geocode('台北市內湖區瑞光路335號');

$reverseGeocode = Geocoding::reverseGeocode('25.0396476673,121.505226616');
```

