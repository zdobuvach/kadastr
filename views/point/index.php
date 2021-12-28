<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\ListView;

$colors = ['outside' => 'text-danger',
    'inside' => 'text-success',
    'vertex' => 'text-primary',
    'boundary' => 'text-info'
];
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

    <div class="row">
        <div class="col-lg-3">
            <?php
            foreach ($provider->getModels() as $value) {

                echo '<p class="' . $colors[$value["check"]] . '" onclick="cMarker(\'' . $value['lng'] . '\',\'' . $value['lat'] . '\',\'' .
                $value["cadastr_id"] . '\')">' .
                $value["cadastr_id"] . ' ' .
                $value["check"] . '</p>';
            }
            ?>
            <?php
            echo LinkPager::widget(['pagination' => $provider->pagination]);
            ?>

        </div>
        <div style="col-lg-9">            
            <div id="map" style="width: 600px; height: 600px;"></div>
        </div>


    </div>

</div>
<script type="text/javascript">
    var poligonVertex = <?= $poligon ?>;

    var mbAttr = '';
    var mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    var hillUrl = 'http://tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png';
    var ukrUrl = '<?= $mapURL ?>';
    //var ukrUrl = 'https://m1.land.gov.ua/map/ortho10k_all/{z}/{x}/{y}.jpg';
    //var ukrUrl = 'http://ows.mundialis.de/services/service?';

    var grayscale = L.tileLayer(mbUrl, {id: 'mapbox/light-v9', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    var streets = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    var hill = L.tileLayer(hillUrl, {id: 'mapbox/hill', tileSize: 512, zoomOffset: -1, attribution: mbAttr});
    //var ukr = L.tileLayer(ukrUrl, {id: '10', tileSize: 256, zoomOffset: -1, attribution: mbAttr});

    var ukr = L.tileLayer.wms(ukrUrl, {layers: 'kadastr', format: 'image/png'});
    //var ukr = L.tileLayer.wms(ukrUrl, {layers: 'TOPO-OSM-WMS'});
    var map = L.map('map', {
        center: poligonVertex[0],
        zoom: 12,
        layers: [streets, ukr]
    });

    var baseLayers = {
        'Grayscale': grayscale,
        'Streets': streets,
        'Hill': hill,
        //'Kadastr': ukr
    };
    var overlayMaps = {
        "Kadastr": ukr
    };

    var layerControl = L.control.layers(baseLayers, overlayMaps).addTo(map);

    var marker = L.marker(poligonVertex[0]).addTo(map)
            .bindPopup('<b>Hello world!</b><br />I am a popup.');
    map.removeLayer(marker);

    var polygon = L.polygon(
            poligonVertex
            ).addTo(map).bindPopup('I am a polygon.');

    function cMarker(clng, clat, cnumber) {
        if (!map.hasLayer(marker)) {            
            map.addLayer(marker);
        }
        marker.setLatLng({lng: clng, lat: clat});
        marker.bindPopup(cnumber);
        marker.openPopup();
        map.flyTo([clat, clng], 15, {
            speed: 0.5, pitch: 60, bearing: 180,
            animate: true,
            duration: 2 // in seconds
        });
        map.map.flyTo({speed: 0.5, zoom: 15, pitch: 60, bearing: 180, center: [clng, clat]});
        
        //map.icons.marker.addWithPopup({lng: clng, lat: clat}, 'marker-sm.png', cnumber);
    }
</script>