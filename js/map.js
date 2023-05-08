const southWest = L.latLng(15.5777,120.7113);
const northEast = L.latLng(15.3974,120.5136);
const bounds = L.latLngBounds(southWest, northEast);


let map = L.map('map', {
    maxBounds: bounds,
    minZoom: 11,
    maxZoom: 18
}).setView([15.480288, 120.588008], 11);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

let polygon = 
{"type":"FeatureCollection",
"features":[{"type":"Feature",
"geometry":{
    "type":"Polygon",
    "coordinates":[
        [
            [120.5171404,15.5450873],[120.5397019,15.4975681],[120.5353889,15.4869913],
            [120.5395249,15.4747854],[120.539873,15.4738057],[120.5399307,15.4736929],
            [120.5414263,15.4696428],[120.5459139,15.4575378],[120.5458495,15.4461057],
            [120.5347774,15.4419795],[120.5300621,15.4095558],[120.5307865,15.4098505],
            [120.5317358,15.4100677],[120.5328141,15.4100365],[120.5336345,15.409773],
            [120.5341973,15.4093545],[120.5347604,15.4077552],[120.5355332,15.4064065],
            [120.5366118,15.4050718],[120.541842,15.4023414],[120.5446749,15.4025275],
            [120.5472341,15.4039707],[120.5477329,15.4051653],[120.5512894,15.4085627],
            [120.568235,15.4061269],[120.57728,15.4033653],[120.5795168,15.4040943],
            [120.5821078,15.4051183],[120.5920521,15.4045896],[120.5951204,15.4058119],
            [120.5971028,15.4064469],[120.5984689,15.4063928],[120.6000699,15.4087287],
            [120.6006047,15.4084581],[120.6019334,15.4091152],[120.620155,15.4087812],
            [120.6484013,15.4116625],[120.6509893,15.4119307],[120.6517781,15.4131719],
            [120.6688971,15.4193894],[120.6710026,15.4251452],[120.6718903,15.4326225],
            [120.6731367,15.4365443],[120.6753991,15.4410633],[120.6772818,15.4457627],
            [120.6817395,15.4492682],[120.6883685,15.4643433],[120.6946079,15.4833093],
            [120.6954224,15.5109331],[120.6930714,15.5187675],[120.6898853,15.5232189],
            [120.692527,15.5264187],[120.69136,15.5298916],[120.6875941,15.5280464],
            [120.6846491,15.5240303],[120.6816879,15.5236737],[120.6745184,15.5218517],
            [120.6730187,15.5221585],[120.6705554,15.5265268],[120.6687022,15.5269002],
            [120.6683281,15.5287726],[120.6679798,15.5329841],[120.6661096,15.5340005],
            [120.663987,15.5344568],[120.6626405,15.5363834],[120.6625628,15.5377062],
            [120.6615553,15.5393421],[120.6597287,15.5377838],[120.6576124,15.5340858],
            [120.6549248,15.5322949],[120.650298,15.5326671],[120.6442074,15.5311443],
            [120.640786,15.5335706],[120.6375461,15.5352532],[120.630761,15.5389805],
            [120.6276972,15.5433737],[120.6299959,15.5446875],[120.6279266,15.5509279],
            [120.6221748,15.5548216],[120.6221302,15.5556878],[120.6217904,15.5557354],
            [120.6210727,15.5556556],[120.6196987,15.5551954],[120.6184679,15.5552579],
            [120.6168207,15.5554387],[120.615025,15.5554523],[120.6128011,15.5552961],
            [120.6104402,15.5568534],[120.6095964,15.5570188],[120.6066498,15.5567429],
            [120.6051745,15.5570048],[120.6049388,15.556051],[120.6044942,15.5558023],
            [120.6019227,15.5558821],[120.6015496,15.5560913],[120.6008792,15.5561417],
            [120.600813,15.5561659],[120.6007353,15.5561814],[120.6006757,15.5561368],
            [120.6006521,15.5560867],[120.5961549,15.5573256],[120.5957394,15.5577387],
            [120.5940233,15.558373],[120.5920919,15.5585796],[120.5909764,15.5586349],
            [120.5906278,15.5587451],[120.5904104,15.558714],[120.5901961,15.5585935],
            [120.5754002,15.5619664],[120.5742275,15.5623404],[120.5735692,15.5628783],
            [120.5730222,15.563402],[120.5722743,15.5646451],[120.5702461,15.5665859],
            [120.5686198,15.5697821],[120.5683623,15.5704987],[120.5674037,15.5724141],
            [120.5664885,15.5731856],[120.5656448,15.5732822],[120.5645866,15.573172],
            [120.5637426,15.5723591],[120.5627414,15.5705403],[120.5621407,15.5700443],
            [120.5610243,15.5697407],[120.5600943,15.5702506],[120.5594074,15.5716976],
            [120.5585633,15.5721108],[120.5573338,15.5722053],[120.5561181,15.5715597],
            [120.5552741,15.5695344],[120.5549306,15.568101],[120.554716,15.5677151],
            [120.5541581,15.5677286],[120.5530562,15.5680733],[120.5515965,15.5691897],
            [120.5512093,15.5697805],[120.5503956,15.5699475],[120.5488076,15.5695064],
            [120.5479639,15.5696167],[120.5474776,15.5692033],[120.546819,15.5691895],
            [120.5460323,15.5690378],[120.5451317,15.568473],[120.5445426,15.5675142],
            [120.5431932,15.5650373],[120.5425602,15.5643965],[120.5415276,15.5636959],
            [120.5409985,15.5624006],[120.5399682,15.5606641],[120.5387093,15.5596583],
            [120.5374791,15.5578669],[120.5366636,15.5554549],[120.5329014,15.5514446],
            [120.5298687,15.5501077],[120.5290399,15.5489506],[120.5265642,15.5446504],
            [120.5247657,15.5454183],[120.5212886,15.5446185],[120.5171404,15.5450873]]]},
            "properties":
            {"place_id":354340465,
            "licence":"Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright",
            "osm_type":"relation",
            "osm_id":15585454,
            "boundingbox":["15.4023414","15.5732822","120.5171404","120.6954224"],
            "lat":"15.4861218","lon":"120.5893473",
            "display_name":"Tarlac City, Tarlac, Central Luzon, Philippines",
            "class":"boundary",
            "type":"administrative",
            "importance":0.5770730099093615,
            "icon":"https://nominatim.openstreetmap.org/ui/mapicons/poi_boundary_administrative.p.20.png"}}]};
let polygonStyle = {
    color: 'rgb(0, 39, 116)',
    weight: 2,
    opacity: 1,
    fillColor: '#ff0000',
    fillOpacity: 0,
}
let tarlacCity = L.geoJSON(polygon, {
    style: polygonStyle
}).addTo(map);
