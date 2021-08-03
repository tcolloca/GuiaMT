"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const googleMapsApi_1 = require("./googleMapsApi");
const landTypes_1 = require("./landTypes");
const random_1 = require("../utils/random");
const perlin_1 = require("../utils/perlin");
const vector_1 = require("../utils/vector");
class Chunk {
    constructor(image, mapTile) {
        this.image = image;
        this.mapTile = mapTile;
    }
}
exports.Chunk = Chunk;
class SuperChunk {
    constructor(chunks) {
        this.noiseGen = new perlin_1.Simplex(new random_1.Random("Game Wars"));
        this.chunks = chunks;
    }
    getWidth() {
        return this.chunks.length * googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT + googleMapsApi_1.GoogleMapsApi.EXTRA_OFFSET;
    }
    getHeight() {
        return this.chunks[0].length * googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH + googleMapsApi_1.GoogleMapsApi.EXTRA_OFFSET;
    }
    getId() {
        return this.chunks[0][0].mapTile.toWorldPosition().toString();
    }
    buildTerrainMap(tileWidth, tileHeight) {
        if (this.terrainMap != null) {
            return this.terrainMap;
        }
        var canvas = this.drawToCanvas();
        var context = canvas.getContext("2d");
        var imageData = context.getImageData(0, 0, this.getWidth(), this.getHeight());
        var tilesMapWidth = this.getWidth() / tileWidth;
        var tilesMapHeight = this.getHeight() / tileHeight;
        this.terrainMap = [];
        for (var y = 0; y < tilesMapHeight; y++) {
            this.terrainMap.push([]);
            for (var x = 0; x < tilesMapWidth; x++) {
                var type = this.getType(imageData.data, x * tileWidth, y * tileHeight, tileWidth, tileHeight);
                this.terrainMap[y].push(type);
            }
        }
        return this.terrainMap;
    }
    buildKingdomMap(tileWidth, tileHeight, octaveCount, scale, lacunarity, persistance) {
        if (this.kingdomMap != null) {
            return this.kingdomMap;
        }
        var tilesMapWidth = this.getWidth() / tileWidth;
        var tilesMapHeight = this.getHeight() / tileHeight;
        this.kingdomMap = [];
        for (var y = 0; y < tilesMapHeight; y++) {
            this.kingdomMap.push([]);
            for (var x = 0; x < tilesMapWidth; x++) {
                var kingdom = this.getKingdom(x, y, tileWidth, tileHeight, octaveCount, scale, lacunarity, persistance);
                this.kingdomMap[y].push(kingdom);
            }
        }
        return this.kingdomMap;
    }
    getType(data, x, y, maskWidth, maskHeight) {
        var neighs = this.getNeighs(data, x, y, maskWidth, maskHeight);
        var totalValue = 0;
        var totalWeight = 0;
        for (var dx = 0; dx < maskWidth; dx++) {
            for (var dy = 0; dy < maskHeight; dy++) {
                var pixel = neighs[dy][dx];
                if (pixel.length != 0) {
                    var avg = pixel.reduce((x, y) => x + y, 0) / 3;
                    var diff = 255 - (avg > 127 ? 255 : 0);
                    if (diff > 0) {
                        totalValue++;
                    }
                }
            }
        }
        if (totalValue / maskWidth * maskHeight < 0.05) {
            return landTypes_1.TerrainType.LAND;
        }
        else {
            return landTypes_1.TerrainType.ROAD;
        }
    }
    getNeighs(data, x, y, maskWidth, maskHeight) {
        var neighs = [];
        for (var dy = 0; dy < maskHeight; dy++) {
            neighs.push([]);
            for (var dx = 0; dx < maskWidth; dx++) {
                neighs[dy].push([]);
                var newX = x + dx;
                var newY = y + dy;
                var pos = (newY * this.getWidth() + newX) * 4; // *4 for 4 ints per pixel
                if (pos >= 0 && pos < data.length) {
                    neighs[dy][dx].push(data[pos]);
                    neighs[dy][dx].push(data[pos + 1]);
                    neighs[dy][dx].push(data[pos + 2]);
                }
            }
        }
        return neighs;
    }
    drawToCanvas() {
        var mapTileWidth = googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH;
        var mapTileHeight = googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT;
        var logoHeight = googleMapsApi_1.GoogleMapsApi.GOOGLE_LOGO_HEIGHT;
        var extraOffset = googleMapsApi_1.GoogleMapsApi.EXTRA_OFFSET;
        var canvas = document.createElement("canvas");
        var context = canvas.getContext("2d");
        canvas.width = this.getWidth();
        canvas.height = this.getHeight();
        for (var dy = 0; dy < this.chunks.length; dy++) {
            for (var dx = 0; dx < this.chunks[0].length; dx++) {
                var chunk = this.chunks[dy][dx];
                var srcX = extraOffset;
                var srcY = logoHeight + extraOffset;
                var width = mapTileWidth + extraOffset;
                var height = mapTileHeight + extraOffset;
                var dstX = dx * mapTileWidth;
                var dstY = dy * mapTileHeight;
                context.drawImage(chunk.image, srcX, srcY, width, height, dstX, dstY, width, height);
            }
        }
        return canvas;
    }
    getKingdom(x, y, tileWidth, tileHeight, octaveCount, scale, lacunarity, persistance) {
        var worldPos = this.getWorldPosition(x * tileWidth, y * tileHeight);
        var noise = this.noiseGen.getNoise(worldPos, octaveCount, scale, lacunarity, persistance);
        if (noise < 0.5) {
            return landTypes_1.KingdomType.SNOW;
        }
        else {
            return landTypes_1.KingdomType.BEACH;
        }
    }
    getNoise(key, worldPos, octaveCount, scale, lacunarity, persistance) {
        var normLat = (worldPos.lat + 85) * scale / (85 * 2);
        var normLong = (worldPos.lng + 180) * scale / (180 * 2);
        var frequency = 1;
        var amplitude = 1;
        var totalAmplitude = 0;
        var noise = 0;
        for (var i = 0; i < octaveCount; i++) {
            noise += ((this.noiseGen.noise(normLat * frequency, normLong * frequency) + 1) / 2) * amplitude;
            totalAmplitude += amplitude;
            frequency *= lacunarity;
            amplitude *= persistance;
        }
        return noise / totalAmplitude;
    }
    getWorldPosition(dx, dy) {
        var tileX = Math.floor(dx / googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH);
        var tileY = Math.floor(dy / googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT);
        if (tileX >= this.chunks[0].length) {
            tileX--;
        }
        if (tileY >= this.chunks.length) {
            tileY--;
        }
        var tileDx = dx - tileX * googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH;
        var tileDy = dy - tileY * googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT;
        return this.chunks[tileY][tileX].mapTile.getWorldPosition(tileDx, tileDy);
    }
    getOffset(lat, lng) {
        var tileX = 0;
        var tileY = 0;
        for (var dy = 0; dy < this.chunks.length - 1; dy++) {
            for (var dx = 0; dx < this.chunks[0].length - 1; dx++) {
                var tile = this.chunks[dy][dx].mapTile;
                var start = tile.getStart();
                if (lat <= start.lat) {
                    tileY = dy;
                }
                if (lng >= start.lng) {
                    tileX = dx;
                }
            }
        }
        return this.chunks[tileY][tileX].mapTile.getOffset(lat, lng)
            .add(new vector_1.Vector2(tileX * googleMapsApi_1.GoogleMapsApi.MAP_TILE_WIDTH, tileY * googleMapsApi_1.GoogleMapsApi.MAP_TILE_HEIGHT));
    }
    getStart() {
        return this.chunks[0][0].mapTile.getStart();
    }
    getEnd() {
        return this.chunks[this.chunks.length - 1][this.chunks[0].length - 1].mapTile.getEnd();
    }
    containsWorldPosition(lat, lng) {
        var start = this.getStart();
        var end = this.getEnd();
        return lat >= end.lat && lat < start.lat
            && lng >= start.lng && lng < end.lng;
    }
}
exports.SuperChunk = SuperChunk;
