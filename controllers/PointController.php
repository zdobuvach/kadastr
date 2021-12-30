<?php

namespace app\controllers;

use Yii;
use app\models\CadastralNumbers;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use app\models\Contour;

class PointController extends \yii\web\Controller {

    public $pointOnVertex = true; // Check if the point sits exactly on one of the vertices
    public $poligon = array();

    public function actionIndex() {
        return $this->viewPointsToMap('getPointFromCheckPattern', false);
    }

    public function actionInside() {
        return $this->viewPointsToMap('getPointFromCheckPattern', 'inside');
    }

    public function actionOutside() {
        return $this->viewPointsToMap('getPointFromCheckPattern', 'outside');
    }

    public function actionContour() {
        $title = 'Contour Original';
        $model = new Contour();

        return $this->render('contour', [
                    'poligons' => $model->getPolygons(Yii::$app->params['cadaster']['pathGdalOriginal']),
                    'title' => $title
        ]);
    }

    public function actionMy() {
        $title = 'Contour My interpretation';
        $model = new Contour();

        return $this->render('contour', [
                    'poligons' => $model->getPolygons(Yii::$app->params['cadaster']['pathGdal']),
                    'title' => $title
        ]);
    }

    public function viewPointsToMap($funGenArrayPoint, $checkPattern) {
        $poligon = $this->getPoligonCoor(Yii::$app->params['cadaster']['fileNameKmlPoligon']);
        $query = CadastralNumbers::find();
        $data = $query->asArray()->all();
        $data = $this->$funGenArrayPoint($data, $poligon, $checkPattern);
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
                'defaultPageSize' => 5,
                'totalCount' => count($data),
            ],
            'sort' => [
                'attributes' => ['check'],
            ],
        ]);
        $checkPattern = $checkPattern ? $checkPattern : 'All';
        $title = 'Points ' . $checkPattern;
        return $this->render('index', ['provider' => $provider,
                    'mapURL' => Yii::$app->params['cadaster']['mapURL'],
                    'poligon' => json_encode($this->poligon),
                    'title' => $title
        ]);
    }

    public function getPointFromCheckPattern($data, $poligon, $checkPattern = false) {
        $result = array();
        foreach ($data as $key => $point) {
            $check = $this->checkPointInPolygon($point, $poligon);
            //echo $check;
            //var_dump(!$checkPattern || ($checkPattern == $check));
            if (!$checkPattern || ($checkPattern == $check)) {
                $data[$key]['check'] = $check;
                $result[] = $data[$key];
            }
            unset($data[$key]);
        }
        return $result;
    }

    public function checkPointInPolygon($point, $vertices, $pointOnVertex = true) {
        //var_dump($vertices);
        //die();
        $this->pointOnVertex = $pointOnVertex;

// Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }

// Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            if ($vertex1['lng'] == $vertex2['lng'] and $vertex1['lng'] == $point['lng'] and $point['lat'] > min($vertex1['lat'], $vertex2['lat']) and $point['lat'] < max($vertex1['lat'], $vertex2['lat'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['lng'] > min($vertex1['lng'], $vertex2['lng']) and $point['lng'] <= max($vertex1['lng'], $vertex2['lng']) and $point['lat'] <= max($vertex1['lat'], $vertex2['lat']) and $vertex1['lng'] != $vertex2['lng']) {
                $xinters = ($point['lng'] - $vertex1['lng']) * ($vertex2['lat'] - $vertex1['lat']) / ($vertex2['lng'] - $vertex1['lng']) + $vertex1['lat'];
                if ($xinters == $point['lat']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['lat'] == $vertex2['lat'] || $point['lat'] <= $xinters) {
                    $intersections++;
                }
            }
        }
// If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }

    public function pointOnVertex($point, $vertices) {
        foreach ($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

    public function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", $pointString);
        $result = array("lng" => $coordinates[0], "lat" => $coordinates[1]);
        unset($coordinates[2]);
        $this->poligon[] = $coordinates;
        return $result;
    }

    public function getPoligonCoor($kmlFileName) {
        $result = array();
        $coorStr = '';
        if (file_exists($kmlFileName)) {
            $kml = simplexml_load_file($kmlFileName);
            $coorStr = (string) $kml->Document->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;
            $coorStr = trim($coorStr);
            foreach (explode(" ", $coorStr) as $vertex) {
                $result[] = $this->pointStringToCoordinates($vertex);
            }
        } else
            Yii::warning($kmlFileName . " Файл не знайдено!");
        //var_dump($coorStr);

        return $result;
    }

}
