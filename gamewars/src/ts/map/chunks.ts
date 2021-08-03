import { MapTile } from "./mapTile";
import { GoogleMapsApi } from "./googleMapsApi";
import { TerrainType, KingdomType } from "./landTypes";
import { WorldPosition } from "./worldPosition";
import { Random } from "../utils/random";
import { Simplex } from "../utils/perlin";
import { Vector2 } from "../utils/vector";

export class Chunk {
    image: HTMLImageElement;
    mapTile: MapTile;

    constructor(image: HTMLImageElement, mapTile: MapTile) {
        this.image = image;
        this.mapTile = mapTile;
    }
}

export class SuperChunk {
    private chunks: Chunk[][];
    private terrainMap: TerrainType[][];
    private kingdomMap: KingdomType[][];  
    private noiseGen: Simplex = new Simplex(new Random("Game Wars"));
 
    constructor(chunks: Chunk[][]) {
        this.chunks = chunks;
    }

    public getWidth(): number {
        return this.chunks.length * GoogleMapsApi.MAP_TILE_HEIGHT + GoogleMapsApi.EXTRA_OFFSET;
    }

    public getHeight(): number {
        return this.chunks[0].length * GoogleMapsApi.MAP_TILE_WIDTH + GoogleMapsApi.EXTRA_OFFSET;
    }

    public getId(): string {
        return this.chunks[0][0].mapTile.toWorldPosition().toString();
    }

    public buildTerrainMap(tileWidth: number, tileHeight: number): TerrainType[][] {
        if (this.terrainMap != null) {
            return this.terrainMap;
        }
        var canvas: HTMLCanvasElement = this.drawToCanvas();
        var context: CanvasRenderingContext2D = canvas.getContext("2d");
        var imageData: ImageData = context.getImageData(0, 0, this.getWidth(), this.getHeight());
        var tilesMapWidth: number = this.getWidth() / tileWidth;
        var tilesMapHeight: number = this.getHeight() / tileHeight;
        this.terrainMap = [];
        for (var y: number = 0; y < tilesMapHeight; y++) {
            this.terrainMap.push([]);
            for (var x: number = 0; x < tilesMapWidth; x++) {
                var type: TerrainType = this.getType(imageData.data, x * tileWidth, y * tileHeight,
                    tileWidth, tileHeight);
                this.terrainMap[y].push(type);
            }
        }
        return this.terrainMap;
    }

    public buildKingdomMap(tileWidth: number, tileHeight: number, octaveCount: number, 
    	scale: number, lacunarity: number, persistance: number): KingdomType[][] {
    	if (this.kingdomMap != null) {
            return this.kingdomMap;
        }

    	var tilesMapWidth: number = this.getWidth() / tileWidth;
        var tilesMapHeight: number = this.getHeight() / tileHeight;

        this.kingdomMap = [];
        for (var y: number = 0; y < tilesMapHeight; y++) {
            this.kingdomMap.push([]);
            for (var x: number = 0; x < tilesMapWidth; x++) {
                var kingdom: number = this.getKingdom(x, y, tileWidth, tileHeight, octaveCount, 
                	scale, lacunarity, persistance);
                this.kingdomMap[y].push(kingdom);
            }
        }
        return this.kingdomMap;
    }

    private getType(data: Uint8ClampedArray, x: number, y: number, 
        maskWidth: number, maskHeight: number): TerrainType {
        var neighs: number[][][] = this.getNeighs(data, x, y, maskWidth, maskHeight);
        var totalValue: number = 0;
        var totalWeight: number = 0;

        for (var dx: number = 0; dx < maskWidth; dx++) {
            for (var dy: number = 0; dy < maskHeight; dy++) {
                var pixel: number[] = neighs[dy][dx];
                if (pixel.length != 0) {
                    var avg: number =  pixel.reduce((x, y) => x + y, 0) / 3;
                    var diff: number = 255 - (avg > 127 ? 255 : 0);
                    if (diff > 0) {
                        totalValue++;
                    }
                }
            }
        }
        if (totalValue / maskWidth * maskHeight < 0.05) {        	
            return TerrainType.LAND;
        } else {
            return TerrainType.ROAD;
        }
    }

    private getNeighs(data: Uint8ClampedArray, x: number, y: number, 
        maskWidth: number, maskHeight: number): number[][][] {
        var neighs: number[][][] = [];
        for (var dy: number = 0; dy < maskHeight; dy++) {
            neighs.push([]);
            for (var dx: number = 0; dx < maskWidth; dx++) {
                neighs[dy].push([]);
                var newX: number = x + dx;
                var newY: number = y + dy;
                var pos: number = (newY * this.getWidth() + newX) * 4; // *4 for 4 ints per pixel
                if (pos >= 0 && pos < data.length) {
                    neighs[dy][dx].push(data[pos]); 
                    neighs[dy][dx].push(data[pos + 1]);
                    neighs[dy][dx].push(data[pos + 2]);       
                }
            }
        }
        return neighs;
    }

    private drawToCanvas(): HTMLCanvasElement {
        var mapTileWidth: number = GoogleMapsApi.MAP_TILE_WIDTH;
        var mapTileHeight: number = GoogleMapsApi.MAP_TILE_HEIGHT;
        var logoHeight: number = GoogleMapsApi.GOOGLE_LOGO_HEIGHT;
        var extraOffset: number = GoogleMapsApi.EXTRA_OFFSET;

        var canvas: HTMLCanvasElement = document.createElement("canvas") as HTMLCanvasElement;
        var context: CanvasRenderingContext2D = canvas.getContext("2d");
        canvas.width = this.getWidth();
        canvas.height = this.getHeight();     

        for (var dy: number = 0; dy < this.chunks.length; dy++) {
            for (var dx: number = 0; dx < this.chunks[0].length; dx++) {
                var chunk: Chunk = this.chunks[dy][dx];
                var srcX: number = extraOffset;
                var srcY: number = logoHeight + extraOffset;
                var width: number = mapTileWidth + extraOffset;
                var height: number = mapTileHeight + extraOffset;
                var dstX: number = dx * mapTileWidth;
                var dstY: number = dy * mapTileHeight;
                context.drawImage(chunk.image, srcX, srcY, width, height, 
                    dstX, dstY, width, height);
            }
        }
        return canvas;
    }

    private getKingdom(x: number, y: number, tileWidth: number, tileHeight: number, octaveCount: number, 
    	scale: number, lacunarity: number, persistance: number): KingdomType {
    	var worldPos: WorldPosition = this.getWorldPosition(x * tileWidth, y * tileHeight);
    	var noise: number = this.noiseGen.getNoise(worldPos, octaveCount, scale, lacunarity, persistance);
    	if (noise < 0.5) {
    		return KingdomType.SNOW;
    	} else {
    		return KingdomType.BEACH;
    	}
    }

    private getNoise(key: string, worldPos: WorldPosition, octaveCount: number, 
    	scale: number, lacunarity: number, persistance: number): number {
    	var normLat: number = (worldPos.lat + 85) * scale / (85 * 2) ;
    	var normLong: number = (worldPos.lng + 180) * scale / (180 * 2); 
    	var frequency: number = 1;
    	var amplitude: number = 1;
        var totalAmplitude: number = 0;
        var noise: number = 0;
    	for (var i: number = 0; i < octaveCount; i++) {
    		noise += ((this.noiseGen.noise(normLat * frequency, normLong * frequency) + 1) / 2) * amplitude;
            totalAmplitude += amplitude;
    		frequency *= lacunarity;
    		amplitude *= persistance;
    	}
    	return noise / totalAmplitude;
    }

    public getWorldPosition(dx: number, dy: number): WorldPosition {
    	var tileX: number = Math.floor(dx / GoogleMapsApi.MAP_TILE_WIDTH);
    	var tileY: number = Math.floor(dy / GoogleMapsApi.MAP_TILE_HEIGHT);

    	if (tileX >= this.chunks[0].length) {
    		tileX--;
    	}
    	if (tileY >= this.chunks.length) {
    		tileY--;
    	}

    	var tileDx: number = dx - tileX * GoogleMapsApi.MAP_TILE_WIDTH;
    	var tileDy: number = dy - tileY * GoogleMapsApi.MAP_TILE_HEIGHT;


    	return this.chunks[tileY][tileX].mapTile.getWorldPosition(tileDx, tileDy);
    }

     public getOffset(lat: number, lng: number): Vector2 {
     	var tileX: number = 0;
     	var tileY: number = 0;
     	for (var dy: number = 0; dy < this.chunks.length - 1; dy++) {
     		for (var dx: number = 0; dx < this.chunks[0].length - 1; dx++) {
     			var tile: MapTile = this.chunks[dy][dx].mapTile;
     			var start: WorldPosition = tile.getStart();
     			if (lat <= start.lat) {
     				tileY = dy;
     			}
     			if (lng >= start.lng) {
     				tileX = dx;
     			}
     		}
     	}
        return this.chunks[tileY][tileX].mapTile.getOffset(lat, lng)
        	.add(new Vector2(tileX * GoogleMapsApi.MAP_TILE_WIDTH, 
            	tileY * GoogleMapsApi.MAP_TILE_HEIGHT));
    }

    public getStart(): WorldPosition {
    	return this.chunks[0][0].mapTile.getStart();
    }

    public getEnd(): WorldPosition {
    	return this.chunks[this.chunks.length - 1][this.chunks[0].length - 1].mapTile.getEnd();
    }

    public containsWorldPosition(lat: number, lng: number): boolean {
        var start: WorldPosition = this.getStart();
        var end: WorldPosition = this.getEnd();
        return lat >= end.lat && lat < start.lat 
            && lng >= start.lng && lng < end.lng;
    } 
}