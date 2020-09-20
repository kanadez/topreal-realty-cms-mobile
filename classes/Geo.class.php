<?php

use Database\TinyMVCDatabase as DB;

class Geo{
    public static function getForGoogleAC($place_id, $locale){ // отдает данные для базы автозапонения
        if (strlen($place_id) > 11){        
            $jsonUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place_id."&language=".$locale."&key=AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0";

            $geocurl = curl_init();
            curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
            curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
            curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
            curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

            $geofile = curl_exec($geocurl);
            curl_close($geocurl);
            $decoded = json_decode($geofile, true);
            $response = [
                "placeid" => $decoded["result"]["place_id"],
                "short_name" => $decoded["result"]["name"],
                "long_name" => $decoded["result"]["formatted_address"],
                "lat" => $decoded["result"]["geometry"]["location"]["lat"],
                "lng" => $decoded["result"]["geometry"]["location"]["lng"]
            ];

            return $response;
        }
    }
    
    public static function getLatLngByAddress($address){ // отдает координаты на карте по полному адресу 
        $jsonUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false";

        $geocurl = curl_init();
        curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
        curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
        curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

        $geofile = curl_exec($geocurl);
        curl_close($geocurl);
        $decoded = json_decode($geofile, true);

        return $decoded["results"][0]["geometry"]["location"];
    }

    public static function getFullAddress($place_id){ // отдает полный адрес по place_id
        if (strlen($place_id) > 11){        
            $jsonUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place_id."&language=en&key=AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0";

            $geocurl = curl_init();
            curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
            curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
            curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
            curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

            $geofile = curl_exec($geocurl);
            curl_close($geocurl);
            $decoded = json_decode($geofile, true);

            return $decoded["result"]["formatted_address"];
        }
        else{
            $synonim = Synonim::load(intval($place_id));
            return $synonim->text;
        }
    }
    
    public static function getTrueLocation($street, $house_number, $status){ // отдает коодинаты на карте зависимо от статуса недвиж. Если брокер или кооперация, то дает только координаты улицы
        if ($street == null){
            return null;
        }
        
        if ($status != 7 && $status != 5){
            return Geo::getLatLngByAddress(($house_number != null ? $house_number.", " : "").Geo::getFullAddress($street));
        }
        else{
            return Geo::getLatLngByAddress(Geo::getFullAddress($street));
        }
    }

    public static function getAddressForAllLocales($place_id){ // отдает адрес на всех языках системы
        $locales = ["en","he","fr","ru","el","es","it","de","pt","ar","da","nl","hu","tr","lt","sr","pl","fa","cs","ro","sv"];
        $response = [];
        
        for ($i = 0; $i < count($locales); $i++){
            $address = GoogleAC::getLongNameByLocale(strval($place_id), $locales[$i]);
            
            if ($address === FALSE){
                $params = [
                    "placeid" => $place_id,
                    "language" => $locales[$i],
                    "key" => "AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0"
                ];
                $jsonUrl = "https://maps.googleapis.com/maps/api/place/details/json?".http_build_query($params);
                $geocurl = curl_init();
                curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
                curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
                curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
                curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

                $geofile = curl_exec($geocurl);
                curl_close($geocurl);
                $decoded = json_decode($geofile, true);

                array_push($response, $decoded["result"]["address_components"][0]["long_name"]);
                
                GoogleAC::staticAddNotExisting(
                    $decoded["result"]["address_components"][0]["short_name"], 
                    $decoded["result"]["address_components"][0]["long_name"], 
                    $decoded["result"]["geometry"]["location"]["lat"], 
                    $decoded["result"]["geometry"]["location"]["lng"], 
                    $place_id, 
                    $locales[$i]
                );
            }
            else{
                array_push($response, $address);
            }
        }
        
        return $response;
    }
    
    public static function getFullByPlaceid($place_id){ // отдает поллную инфу по месту на основе place_id 
        $jsonUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place_id."&language=en&key=AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0";

        $geocurl = curl_init();
        curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
        curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
        curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

        $geofile = curl_exec($geocurl);
        curl_close($geocurl);
        $decoded = json_decode($geofile, true);

        return $decoded["result"];
    }
    
    public static function getAddressByLatLng($lat, $lng, $locale){ // отдает адрес по коодинатам (обартно getLatLngByAddress)
        $jsonUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($lat).",".urlencode($lng)."&language=".urlencode($locale)."&key=AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0";

        $geocurl = curl_init();
        curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
        curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
        curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

        $geofile = curl_exec($geocurl);
        curl_close($geofile);
        $decoded = json_decode($geofile, true);

        return $decoded["results"];
    }
    
    public static function containsLocation($contour, $lat, $lng){ // определяет, попала ли точка в полигон
        $contour_decoded = json_decode($contour, true);
        $polySides = count($contour_decoded);
        $polyX = [];
        $polyY = [];

        for ($i = 0; $i < count($contour_decoded); $i++){
            array_push($polyX, $contour_decoded[$i]["lat"]);
            array_push($polyY, $contour_decoded[$i]["lng"]);
        }

        $x = $lat;
        $y = $lng;

        $j = $polySides-1 ;
        $oddNodes = 0;

        for ($i=0; $i<$polySides; $i++) {
            if ($polyY[$i]<$y && $polyY[$j]>=$y ||  $polyY[$j]<$y && $polyY[$i]>=$y){
                if ($polyX[$i]+($y-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$x){
                    $oddNodes=!$oddNodes;
                }
            }

            $j=$i; 
        }

        return $oddNodes; 
    }
    
    public function getPlaceIdByAddress($address){ // отдает place_id по полному адресу, предварительно проверив адрес в нашей базе
        global $googleac;
    
        $query = DB::createQuery()->select('placeid')->where('short_name LIKE ? OR long_name LIKE ?'); 
        $places = $googleac->getList($query, [$address, $address]);

        if (count($places) > 0){
            return $places[0]->placeid;
        }
        
        $jsonUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=AIzaSyB9Wn9uRK8mlCHzA20yrPJzJzTVsz3mws0";

        $geocurl = curl_init();
        curl_setopt($geocurl, CURLOPT_URL, $jsonUrl);
        curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
        curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

        $geofile = curl_exec($geocurl);
        curl_close($geofile);
        $decoded = json_decode($geofile, true);

        return $decoded["results"][0]["place_id"];
    }
}