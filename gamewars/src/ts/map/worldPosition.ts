import { Vector2 } from "../utils/vector";
import { MapTile } from "./mapTile";

export class WorldPosition {
    _lat: number;
    _lng: number;

    constructor(lat: number, lng: number) {
        this._lat = lat;
        this._lng = lng;
    }

    public toMapTile(zoom: number): MapTile {
        var zoomPow: number = 1 << zoom;
        var x: number = Math.floor((this.lng + 180) / 360 * zoomPow);
        var latRads: number = this.toRadians(this.lat);
        var y: number = Math.floor((1 - Math.log(Math.tan(latRads) + 1 / Math.cos(latRads)) / Math.PI) / 2 * zoomPow);
        if (x < 0) {
            x = 0;
        } else if (x >= zoomPow) {
            x = zoomPow - 1;
        }
        if (y < 0) {
            y = 0;
        } else if (y >= zoomPow) {
            y = zoomPow - 1;
        }
       return new MapTile(x, y, zoom);
    }

    public toVector2() {
        return new Vector2(this.lat, this.lng);
    }

    public toString(): string {
        return "(" + this.lat + ";" + this.lng + ")";
    }

    get lat(): number {
        return this._lat;
    }

    get lng(): number {
        return this._lng;
    }

    private toRadians(angle: number): number {
        return angle * Math.PI / 180;
    }
}