"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const vector_1 = require("../utils/vector");
const mapTile_1 = require("./mapTile");
class WorldPosition {
    constructor(lat, lng) {
        this._lat = lat;
        this._lng = lng;
    }
    toMapTile(zoom) {
        var zoomPow = 1 << zoom;
        var x = Math.floor((this.lng + 180) / 360 * zoomPow);
        var latRads = this.toRadians(this.lat);
        var y = Math.floor((1 - Math.log(Math.tan(latRads) + 1 / Math.cos(latRads)) / Math.PI) / 2 * zoomPow);
        if (x < 0) {
            x = 0;
        }
        else if (x >= zoomPow) {
            x = zoomPow - 1;
        }
        if (y < 0) {
            y = 0;
        }
        else if (y >= zoomPow) {
            y = zoomPow - 1;
        }
        return new mapTile_1.MapTile(x, y, zoom);
    }
    toVector2() {
        return new vector_1.Vector2(this.lat, this.lng);
    }
    toString() {
        return "(" + this.lat + ";" + this.lng + ")";
    }
    get lat() {
        return this._lat;
    }
    get lng() {
        return this._lng;
    }
    toRadians(angle) {
        return angle * Math.PI / 180;
    }
}
exports.WorldPosition = WorldPosition;
