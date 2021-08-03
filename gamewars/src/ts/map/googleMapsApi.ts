import { BigNumber } from "../utils/bignumber";
import { Vector2 } from "../utils/vector";
import { MapTile } from "./mapTile";
import { WorldPosition } from "./worldPosition";
import { Chunk } from "./chunks";

export class GoogleMapsApi {

    public static EXTRA_OFFSET: number = 16 * 2;
    public static GOOGLE_LOGO_HEIGHT: number = 25;
    public static MAP_TILE_WIDTH: number = 256;
    public static MAP_TILE_HEIGHT: number = 256;

    private roadColor: string = '0x000000';
    private landscapeColor: string = '0xFFFFFF';
    private poiColor: string = this.landscapeColor;
    private transitColor: string = this.landscapeColor;
    private waterColor: string = '0xFFFFFF';
    private apiKey: string = 'AIzaSyBuDD2LfL30v4DbyyAPClrm8umK9lxBD_M';

    public getChunk(chunkMapTile: MapTile): Promise<Chunk> {
        var tileCenter: WorldPosition = chunkMapTile.getCenter();
        // We need to keep it centered after adding offset and logo.
        var width: number = GoogleMapsApi.EXTRA_OFFSET * 2 + GoogleMapsApi.MAP_TILE_WIDTH;
        var height: number = (GoogleMapsApi.EXTRA_OFFSET + GoogleMapsApi.GOOGLE_LOGO_HEIGHT) * 2  
            + GoogleMapsApi.MAP_TILE_HEIGHT;
    	var url: string = 'https://maps.googleapis.com/maps/api/staticmap?'
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

        var result: Promise<Chunk> = new Promise((resolve, reject) => {
            var xhr: XMLHttpRequest = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var url: any = window.URL;
                    var img: HTMLImageElement = new Image();
                    img.onload = function (event) {
                        resolve(new Chunk(event.target as HTMLImageElement, chunkMapTile));
                    };
                    img.src = url.createObjectURL(this.response);
                }
            }
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();   
        });

        return result;
    }
}