        ===============================================================================================

        use Jyun\Mapsapi\TwddMap\Geocoding;
        use Jyun\Mapsapi\Map8\Client      as Map8Client;
        use Jyun\Mapsapi\GoogleMap\Client as GoogleMapClient;

        ===============================================================================================
        
        # 共包

        $geocode        = Geocoding::geocode('貴陽街二段39號');
        $reverseGeocode = Geocoding::reverseGeocode('25.0396476673,121.505226616');

        ===============================================================================================

        # ENV file setting name is Key of MAP8_API_KEY
        $Map8Client = new Map8Client();

        # Set the Key in Map8Client
        $Map8Client = new Map8Client([
            'key' => 'xxxxx',
            'timeout' => 5
        ]);

        $geocode        = $Map8Client->geocode('貴陽街二段39號');
        $reverseGeocode = $Map8Client->reverseGeocode('25.0396476673,121.505226616');
        $reverseGeocode = $Map8Client->reverseGeocode([25.0396476673,121.505226616]);
        $directions     = $Map8Client->directions('25.0097038,121.4401783', '25.0108898,121.4346963');
        $distanceMatrix = $Map8Client->distanceMatrix('25.0097038,121.4401783', '25.0108898,121.4346963|25.0042938,121.4557153|25.0008008,121.4393843');

        ===============================================================================================

        # ENV file setting name is Key of GOOGLE_API_KEY
        $GoogleMapClient = new GoogleMapClient();

        # Set the Key in $GoogleMapClient
        $GoogleMapClient = new GoogleMapClient([
            'key' => 'xxxxxxx',
            'timeout' => 5
        ]);

        $geocode = $GoogleMapClient->geocode('貴陽街二段39號');
        $reverseGeocode = $GoogleMapClient->reverseGeocode('25.0396476673,121.505226616');
        $reverseGeocode = $GoogleMapClient->reverseGeocode([25.0396476673,121.505226616]);
        $directions     = $GoogleMapClient->directions('25.0097038,121.4401783', '25.0108898,121.4346963');
        $distanceMatrix = $GoogleMapClient->distanceMatrix('25.0097038,121.4401783', '25.0108898,121.4346963|25.0042938,121.4557153|25.0008008,121.4393843');