"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const vector_1 = require("../utils/vector");
const worldPosition_1 = require("./worldPosition");
const googleMapsApi_1 = require("./googleMapsApi");
class MapTile {
    constructor(x, y, zoom) {
        this._x = x;
        this._y = y;
        this._zoom = zoom;
    }
    delta(dx, dy) {
        return new MapTile(this.x + dx, this.y + dy, this.zoom);
    }
    dist(other) {
        return this.toVector2().sub(other.toVector2());
    }
    getStart() {
        return this.toWorldPosition();
    }
    getEnd() {
        return this.delta(1, 1).toWorldPosition();
    }
    getCenter() {
        var midPoint = this.getStart().toVector2().avg(this.getEnd().toVector2());
        return new worldPosition_1.WorldPosition(midPoint.x, midPoint.y);
    }
    floor(step) {
        var aux = this.toVector2().floor(step);
        return new MapTile(aux.x, aux.y, this.zoom);
    }
    toWorldPosition() {
        var zoomPow = 1 << this.zoom;
        var lng = this.x / zoomPow * 360 - 180;
        var aux = Math.PI - (2 * Math.PI * this.y / zoomPow);
        var lat = this.toDegrees(Math.atan(Math.sinh(aux)));
        return new worldPosition_1.WorldPosition(lat, lng);
    }
    getWorldPosition(dx, dy) {
        var normDx = dx / googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH;
        var normDy = dy / googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT;
        var start = this.getStart();
        var end = this.getEnd();
        var latLerp = this.lerp(start.lat, end.lat, normDy);
        var lngLerp = this.lerp(start.lng, end.lng, normDx);
        return new worldPosition_1.WorldPosition(latLerp, lngLerp);
    }
    getOffset(lat, lng) {
        var start = this.getStart();
        var end = this.getEnd();
        var normLat = (lat - start.lat) / (end.lat - start.lat);
        var normLng = (lng - start.lng) / (end.lng - start.lng);
        var dx = this.lerp(0, googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH, normLng);
        var dy = this.lerp(0, googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT, normLat);
        return new vector_1.Vector2(dx, dy);
    }
    get x() {
        return this._x;
    }
    get y() {
        return this._y;
    }
    get zoom() {
        return this._zoom;
    }
    toVector2() {
        return new vector_1.Vector2(this.x, this.y);
    }
    toDegrees(rads) {
        return rads / Math.PI * 180;
    }
    lerp(start, end, p) {
        return (end - start) * p + start;
    }
}
exports.MapTile = MapTile;
