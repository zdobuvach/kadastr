<?php

namespace app\models;

use Yii;
use yii\base\Model;
use moonland\phpexcel\Excel;
use yii\httpclient\Client;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class Cadaster extends Model {

    public $cadasterIds = array();

    public function setCadasterIds() {        
        $client = new Client(['responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],]);

        $data = Excel::import(Yii::$app->params['cadaster']['fileName'], [
                    'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel. 
                    'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric. 
                    'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
        ]);
        //return $data;
        foreach ($data as $key => $value) {
            foreach ($value as $cadastralNumber) {

                $response = $client->createRequest()
                        ->setMethod('GET')
                        ->setUrl('https://soft.farm/api/open/cadastral/find-center-by-cadastral-number')
                        ->setData(['clientId' => Yii::$app->params['cadaster']['clientId'], 'cadastralNumber' => $cadastralNumber])
                        ->send();
                //die(var_dump($response->data['status']));
                //if($response->data['status'])
                $this->cadasterIds[] = [$cadastralNumber, $response->data["data"]['lat'], $response->data["data"]['lng']];
            }
            unset($data[$key]);
        }
        unset($data[$key]);
        return $this->cadasterIds;
    }

}
