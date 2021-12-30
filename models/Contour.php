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
class Contour extends Model {    

    public function getPolygons($path) {     
        

        $output = null;
        $retval = null;

        $shapeFileName = $path . 'shape.json';
        $contourFileName = $path . 'contour.shp';
        $cutShapeFileName = $path . 'cutShape.shp';
        $cutShapeFileNameJson = $path . 'cutShape.json';

        $command = [
            ["ogr2ogr $shapeFileName " . $path . Yii::$app->params['cadaster']['shapeFileName'], $shapeFileName],
            ["ogr2ogr -f 'ESRI Shapefile' -a_srs EPSG:4326 $contourFileName " . $path . Yii::$app->params['cadaster']['contourFileName'], $contourFileName],
            ["ogr2ogr -clipsrc " . $path . Yii::$app->params['cadaster']['shapeFileName'] . " $cutShapeFileName  $contourFileName", $cutShapeFileName],
            ["ogr2ogr $cutShapeFileNameJson $cutShapeFileName", $cutShapeFileNameJson],
        ];

        foreach ($command as $value) {
            if (file_exists($value[1])) {
                unlink($value[1]);
            }
            exec($value[0], $output, $retval);

            if (file_exists($value[1])) {
                Yii::debug('Good ' . $value[0]);
            } else {
                Yii::error($value[0]);
            }
        }

        $polygons['Contour'] = $this->getJsonFile($path . Yii::$app->params['cadaster']['contourFileName'])[0];
        $polygons['Shape'] = $this->getJsonFile($shapeFileName);
        $polygons['Trimmed shape'] = $this->getJsonFile($cutShapeFileNameJson);

        return json_encode($polygons);
    
    }
        public function getJsonFile($jsonFileName) {
        $result = [''];
        if (file_exists($jsonFileName)) {
            $json = file_get_contents($jsonFileName);
            $result = json_decode($json)->features[0]->geometry->coordinates[0];
        } else {
            Yii::error($jsonFileName);
        }
        return $result;
    }

}
