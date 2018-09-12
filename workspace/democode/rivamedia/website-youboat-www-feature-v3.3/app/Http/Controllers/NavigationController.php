<?php namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Http\Requests;
    use App\Http\Controllers\Controller;

    class NavigationController extends SearchController
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //$this->middleware(['clearcache']);
        }

        public static function getAdsTypesList(){
            $array = SearchController::getGateWayAdsTypes([11]);
            foreach($array as $key => $val) {
                $adstype = SearchController::getAdsType($val[0]);
                $array[$adstype['id']] = $adstype['rewrite_url'] . '#' . $val['count'];
            }

            //$array = SearchController::getAdsTypes('active', true);
            //$array = json_decode(json_encode($array), true);
            return $array;
        }

        public static function getAdsCategoriesList($_adsTypeId = '') {
            $array = SearchController::getGateWayAdsCategories($_adsTypeId);
            $return = [];
            foreach($array as $key => $val) {
                $category = SearchController::getCategory($val[0]);
                $return[$category['id']] = trim($category['rewrite_url']) . '#' . $val['count'];
            }
            //$array = SearchController::getCategories($_adsTypeId, true);
            //$array = json_decode(json_encode($array), true);
            return $return;
        }

        public static function getAdsManufacturersList($min = 1){
            $array = SearchController::getGateWayAdsManufacturers();
            $return = [];
            foreach($array as $key => $val) {
                if ($val['count'] >= $min) {
                    //debug($val['count']);
                    $manufacturer = SearchController::getManufacturer($val[0]);
                    //debug($manufacturer['id']);
                    //debug(trim($manufacturer['rewrite_url']) . '#' . $val['count']);
                    $return[$manufacturer['id']] = trim($manufacturer['rewrite_url']) . '#' . $val['count'];
                }
            }
            //$array = SearchController::getManufacturers('', true);
            return $return;
        }

        public static function getAdsModelsList($_manufacturersId = ''){
            return SearchController::getModels($_manufacturersId, true);
        }

        public static function getAdsManufacturersEnginesList(){
            return SearchController::getManufacturersEngines('', true);
        }

        public static function getAdsModelsEnginesList($_manufacturersenginesId = ''){
            return SearchController::getModelsEngines($_manufacturersenginesId, true);
        }
    }
