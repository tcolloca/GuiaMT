import { Vector2 } from "../utils/vector";
import { WorldPosition } from "./worldPosition";
import { GoogleMapsApi } from "./googleMapsApi";

export class MapTile {
    private _x: number;
    private _y: number;
    private _zoom: number;

    constructor(x: number, y: number, zoom: number) {
        this._x = x;
        this._y = y;
        this._zoom = zoom;
    }

    public delta(dx: number, dy: number): MapTile {
        return new MapTile(this.x + dx, this.y + dy, this.zoom);
    }

    public dist(other: MapTile): Vector2 {
        return this.toVector2().sub(other.toVector2());
    }

    public getStart(): WorldPosition {
        return this.toWorldPosition();
    }

    public getEnd(): WorldPosition {
        return this.delta(1, 1).toWorldPosition();
    }

    public getCenter(): WorldPosition {
        var midPoint: Vector2 = this.getStart().toVector2().avg(this.getEnd().toVector2());
        return new WorldPosition(midPoint.x, midPoint.y);
    }

    public floor(step: number): MapTile {
        var aux: Vector2 = this.toVector2().floor(step);
        return new MapTile(aux.x, aux.y, this.zoom);
    }

    public toWorldPosition(): WorldPosition {
        var zoomPow: number = 1 << this.zoom;
        var lng: number = this.x / zoomPow * 360 - 180;
        var aux: number = Math.PI - (2 * Math.PI * this.y / zoomPow);
        var lat: number = this.toDegrees(Math.atan(Math.sinh(aux)));
        return new WorldPosition(lat, lng);
    }

    public getWorldPosition(dx: number, dy: number): WorldPosition {
    	var normDx: number = dx / GoogleMapsApi.MAP_TILE_WIDTH;
    	var normDy: number = dy / GoogleMapsApi.MAP_TILE_HEIGHT;

    	var start: WorldPosition = this.getStart();
    	var end: WorldPosition = this.getEnd();

    	var latLerp: number = this.lerp(start.lat, end.lat, normDy);
    	var lngLerp: number = this.lerp(start.lng, end.lng, normDx);  

    	return new WorldPosition(latLerp, lngLerp);
    }

    public getOffset(lat: number, lng: number): Vector2 {
        var start: WorldPosition = this.getStart();
        var end: WorldPosition = this.getEnd();
        
        var normLat: number = (lat - start.lat) / (end.lat - start.lat);
        var normLng: number = (lng - start.lng) / (end.lng - start.lng);


        var dx: number = this.lerp(0, GoogleMapsApi.MAP_TILE_WIDTH, normLng);
        var dy: number = this.lerp(0, GoogleMapsApi.MAP_TILE_HEIGHT, normLat);  

        return new Vector2(dx, dy);
    }

    get x(): number {
        return this._x;
    }

    get y(): number {
        return this._y;
    }

    get zoom(): number {
        return this._zoom;
    }

    private toVector2(): Vector2 {
        return new Vector2(this.x, this.y);
    }

    private toDegrees(rads: number): number {
        return rads / Math.PI * 180;
    }

    private lerp(start: number, end: number, p: number): number {
    	return (end - start) * p + start;
    }
}