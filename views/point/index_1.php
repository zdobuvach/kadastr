<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;

$colors = ['outside' => 'text-danger',
    'inside' => 'text-success',
    'vertex' => 'text-primary',
    'boundary' => 'text-info'
];
$this->registerJsFile('https://api.mqcdn.com/sdk/mapquest-gl-js/v0.4.0/mapquest-gl.js');
$this->registerCssFile('https://api.mqcdn.com/sdk/mapquest-gl-js/v0.4.0/mapquest-gl.css');
?>

<script type="text/javascript">
    var map;
    window.onload = function () {
        map = new mqgl.Map('map', '<?= $map ?>');

        map.load(function () {

            map.draw.polygon(
<?= $poligon ?>
            );
            map.fitBounds();
        });
    };
    //    map.map.flyTo({speed: 0.5, zoom: 15, pitch: 60, bearing: 180, center: [37.941512839,48.837407408]});
    // marker = map.icons.marker.addWithPopup({ lng: 37.941512839, lat: 48.837407408 }, 'marker-sm.png', 'Denver, CO');
    function cMarker(clng, clat, cnumber) {
        map.map.flyTo({speed: 0.5, zoom: 15, pitch: 60, bearing: 180, center: [clng, clat]});
        map.icons.marker.addWithPopup({lng: clng, lat: clat}, 'marker-sm.png', cnumber);
    }
</script>

<div class="body-content">

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
        </div>
        <div style="col-lg-9">            
            <div id="map" style="width: 600px; height: 600px;"></div>
        </div>


    </div>

</div>


<?php
echo LinkPager::widget(['pagination' => $provider->pagination]);
?>

