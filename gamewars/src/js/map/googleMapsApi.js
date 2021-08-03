"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const chunks_1 = require("./chunks");
class GoogleMapsApi {
    constructor() {
        this.roadColor = '0x000000';
        this.landscapeColor = '0xFFFFFF';
        this.poiColor = this.landscapeColor;
        this.transitColor = this.landscapeColor;
        this.waterColor = '0xFFFFFF';
        this.apiKey = 'AIzaSyBuDD2LfL30v4DbyyAPClrm8umK9lxBD_M';
    }
    getChunk(chunkMapTile) {
        var tileCenter = chunkMapTile.getCenter();
        // We need to keep it centered after adding offset and logo.
        var width = GoogleMapsApi.EXTRA_OFFSET * 2 + GoogleMapsApi.MAP_TILE_WIDTH;
        var height = (GoogleMapsApi.EXTRA_OFFSET + GoogleMapsApi.GOOGLE_LOGO_HEIGHT) * 2
            + GoogleMapsApi.MAP_TILE_HEIGHT;
        var url = 'https://maps.googleapis.com/maps/api/staticmap?'
            + 'center=' + tileCenter.lat + ',' + tileCenter.lng
            + '&zoom=' + chunkMapTile.zoom
            + '&size=' + width + 'x' + height
            + '&maptype=roadmap'
            + '&style=feature:road|color:' + this.roadColor
            + '&style=feature:landscape|color:' + this.landscapeColor
            + '&style=feature:poi|color:' + this.poiColor
            + '&style=feature:transit|color:' + this.transitColor
            + '&style=feature:water|color:' + this.waterColor
            + '&style=feature:all|element:labels|visibility:off'
            // + '&markers=color:blue|label:A|' + startLatLng[0] + ',' + startLatLng[1]
            // + '&markers=color:blue|label:B|' + endLatLng[0] + ',' + endLatLng[1]
            // + '&markers=color:blue|label:A|' + lat + ',' + lng
            // + '&markers=color:blue|label:B|' + prevChunkLat1 + ',' + prevChunkLng1
            // + '&markers=color:blue|label:S|' + prevChunkLat1 + ',' + bottomBoundLng1
            // + '&markers=color:blue|label:C|' + prevChunkLat2 + ',' + prevChunkLng2
            // + '&markers=color:blue|label:Q|' + bottomBoundChunkLat2 + ',' + prevChunkLng2
            + '&key=' + this.apiKey;
        var result = new Promise((resolve, reject) => {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var url = window.URL;
                    var img = new Image();
                    img.onload = function (event) {
                        resolve(new chunks_1.Chunk(event.target, chunkMapTile));
                    };
                    img.src = url.createObjectURL(this.response);
                }
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
        });
        return result;
    }
}
GoogleMapsApi.EXTRA_OFFSET = 16 * 2;
GoogleMapsApi.GOOGLE_LOGO_HEIGHT = 25;
GoogleMapsApi.MAP_TILE_WIDTH = 256;
GoogleMapsApi.MAP_TILE_HEIGHT = 256;
exports.GoogleMapsApi = GoogleMapsApi;
