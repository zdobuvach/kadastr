<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\ListView;

$this->registerCssFile('https://unpkg.com/leaflet@1.7.1/dist/leaflet.css', [
    'integrity' => 'sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==',
    'crossorigin' => "",
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', [
    'position' => $this::POS_HEAD,
    'integrity' => 'sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==',
    'crossorigin' => "",
]);

$this->title = $title;
?>

<div class="body-content">
    <h1><?= Html::encode($title) ?></h1>


    <div id="map" style="width: 100%; height: 600px;"></div>
</div>
<script type="text/javascript">

    var poligonsVertex = <?= $poligons ?>;
    var colors = ['ff00FF', '0000FF', 'FF0000', 'f0f00F', '0ff0f0']
    color = colors[Math.floor(Math.random() * colors.length)];
    var mbAttr = '';
    var mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    var hillUrl = 'http://tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png';
    var ukrUrl = '<?= $mapURL ?>';

    var grayscale = L.tileLayer(mbUrl, {id: 'mapbox/light-v9', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    var streets = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    var hill = L.tileLayer(hillUrl, {id: 'mapbox/hill', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    var ukr = L.tileLayer.wms(ukrUrl, {layers: 'kadastr', format: 'image/png'});
    startPoint = poligonsVertex['Shape'][0].slice(0);
    startPoint.reverse();
    var map = L.map('map', {
        center: startPoint,
        zoom: 16,
        layers: [streets, ukr]
    });

    var polygons = new Array();
    var polygonslayMaps = {};
    // poligonsVertex.forEach(function(poliVer, index, array) {
    for (let index in poligonsVertex) {
        poliVer = poligonsVertex[index].map(item => item.reverse());
        console.log(poliVer);
        polygon = L.polygon(
                poliVer
                ).addTo(map).bindPopup('I am a polygon ' + index);
        color = '#' + colors[Math.floor(Math.random() * colors.length)];
        polygon.setStyle({fillColor: color});
        polygons.push(polygon);
        polygonslayMaps["Kadastr"] = ukr;
        polygonslayMaps['Polygon ' + index] = polygon;        
    }   

    var baseLayers = {
        'Grayscale': grayscale,
        'Streets': streets,
        'Hill': hill,
        //'Kadastr': ukr
    };
    var overlayMaps = polygonslayMaps;
    var layerControl = L.control.layers(baseLayers, overlayMaps).addTo(map);



</script>