(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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

},{"../utils/perlin":11,"../utils/random":12,"../utils/vector":13,"./googleMapsApi":3,"./landTypes":5}],2:[function(require,module,exports){
"use strict";
//// <reference path="../utils/jimp.d.ts" />
Object.defineProperty(exports, "__esModule", { value: true });
const googleMapsApi_1 = require("./googleMapsApi");
const vector_1 = require("../utils/vector");
const worldPosition_1 = require("./worldPosition");
const chunks_1 = require("./chunks");
const mapPainter_1 = require("./mapPainter");
class GameMap {
    constructor() {
        this.gameHeight = Math.min(window.innerHeight, 400);
        this.gameWidth = window.innerWidth / window.innerHeight * this.gameHeight;
        this.game = new Phaser.Game(this.gameWidth, this.gameHeight, Phaser.AUTO, '', { preload: this.preload.bind(this), create: this.create.bind(this), update: this.update.bind(this) });
        this.superChunkMemory = 3;
        this.midMemory = Math.floor(this.superChunkMemory / 2);
        this.superChunkSize = 3;
        // private maxDist: number = window.innerWidth / 2 + 50;
        this.maxDist = this.gameWidth / 2 + 50;
        this.speed = 0.000025;
        this.zoom = 18;
        this.tileWidth = 16;
        this.tileHeight = 16;
        this.extraOffset = googleMapsApi_1.GoogleMapsApi.EXTRA_OFFSET;
        // private lat: number = 50;
        // private lng: number = 30;
        this.lat = -34.491126;
        this.lng = -58.493072;
        this.prevLat = null;
        this.prevLng = null;
        this.firstTime = true;
        this.firstSuperChunk = true;
        this.background = [];
        this.superChunks = [];
        for (var i = 0; i < this.superChunkMemory; i++) {
            this.background[i] = [];
            this.superChunks[i] = [];
            for (var j = 0; j < this.superChunkMemory; j++) {
                this.background[i][j] = null;
                this.superChunks[i][j] = null;
            }
        }
    }
    updateCoords(position) {
        this.lat = position.coords.latitude;
        this.lng = position.coords.longitude;
        if (this.firstSuperChunk) {
            this.showPosition(position);
            this.firstSuperChunk = false;
        }
    }
    showPosition(position) {
        // this.lat = position.coords.latitude;
        // this.lng = position.coords.longitude;
        this.getSuperChunk().then((superChunk) => {
            var map = new mapPainter_1.MapPainter(superChunk).drawSuperChunk(this.game, superChunk);
            this.background[this.midMemory][this.midMemory] = map.toBackgroundSprites(this.game, 0, 0);
            this.superChunks[this.midMemory][this.midMemory] = superChunk;
            this.player = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY, 'player');
            this.player.animations.add('up', [4, 3, 2, 1].map(n => 'up_' + n + '.png'), 10, true);
            this.player.animations.add('down', [4, 3, 2, 1].map(n => 'down_' + n + '.png'), 10, true);
            this.player.animations.add('left', [4, 3, 2, 1].map(n => 'left_' + n + '.png'), 10, true);
            this.player.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
        });
    }
    preload() {
        this.game.scale.scaleMode = Phaser.ScaleManager.EXACT_FIT;
        this.game.scale.refresh();
        // this.game.scale.setupScale(window.innerWidth, window.innerHeight);
        // this.game.scale.refresh();
        this.game.load.atlasJSONHash('map-tiles', 'assets/map-tiles.png', 'assets/map-tiles.json');
        this.game.load.atlasJSONHash('player', 'assets/player.png', 'assets/player.json');
        this.mapsApi = new googleMapsApi_1.GoogleMapsApi();
        this.cursors = this.game.input.keyboard.createCursorKeys();
    }
    create() {
        var game = this.game;
        // navigator.geolocation.getCurrentPosition(this.updateCoords.bind(this));
        this.showPosition(null);
        // Jimp.read("./assets/path.png");
    }
    update() {
        var speed = this.speed;
        var cursors = this.cursors;
        var lat = this.lat;
        var lng = this.lng;
        if (this.player == null) {
            return;
        }
        else if (this.firstTime) {
            this.player.animations.play('down');
            this.firstTime = false;
        }
        this.game.world.bringToTop(this.player);
        if (cursors.left.isDown || this.game.input.pointer1.isDown && this.game.input.pointer1.position.x < this.game.world.width * 0.33) {
            lng -= speed;
        }
        if (cursors.right.isDown || this.game.input.pointer1.isDown && this.game.input.pointer1.position.x > this.game.world.width * 0.66) {
            lng += speed;
        }
        if (cursors.down.isDown || this.game.input.pointer1.isDown && this.game.input.pointer1.position.y > this.game.world.height * 0.66) {
            lat -= speed;
        }
        if (cursors.up.isDown || this.game.input.pointer1.isDown && this.game.input.pointer1.position.y < this.game.world.height * 0.33) {
            lat += speed;
        }
        if (this.prevLat !== lat || this.prevLng !== lng) {
            this.playAnimation(lat, lng);
            this.prevLat = this.lat;
            this.prevLng = this.lng;
            this.lat = lat;
            this.lng = lng;
            var superChunkIndex = this.findContainingSuperChunk(lat, lng);
            this.shiftSuperChunks(superChunkIndex);
            this.updateBackground();
        }
    }
    playAnimation(lat, lng) {
        if (lat < this.prevLat) {
            this.player.animations.play('down');
        }
        else if (lat > this.prevLat) {
            this.player.animations.play('up');
        }
        else if (lng < this.prevLng) {
            this.player.animations.play('left');
        }
        else if (lng > this.prevLng) {
            this.player.animations.play('right');
        }
    }
    updateBackground() {
        var superChunk = this.superChunks[this.midMemory][this.midMemory];
        var offset = superChunk.getOffset(this.lat, this.lng);
        for (var dx = 0; dx < this.superChunkMemory; dx++) {
            for (var dy = 0; dy < this.superChunkMemory; dy++) {
                var chunkSprites = this.background[dy][dx];
                if (chunkSprites != null) {
                    chunkSprites.forEach((chunkSprite) => {
                        chunkSprite.sprite.position.x = (chunkSprite.sprite.width - this.extraOffset) * (dx - this.midMemory)
                            - offset.x + this.game.world.width / 2 + chunkSprite.dx;
                        chunkSprite.sprite.position.y = (chunkSprite.sprite.height - this.extraOffset) * (dy - this.midMemory)
                            - offset.y + this.game.world.height / 2 + chunkSprite.dy;
                    });
                }
            }
        }
        this.loadNeighSuperChunks(offset);
    }
    getSuperChunk(deltaX = 0, deltaY = 0) {
        var chunkPromises = [];
        console.log(this.lat, this.lng);
        var centerMapTile = new worldPosition_1.WorldPosition(this.lat, this.lng).toMapTile(this.zoom)
            .floor(this.superChunkSize)
            .delta(deltaX * this.superChunkSize, deltaY * this.superChunkSize);
        for (var dy = 0; dy < this.superChunkSize; dy++) {
            chunkPromises.push([]);
            for (var dx = 0; dx < this.superChunkSize; dx++) {
                var chunkMapTile = centerMapTile.delta(dx, dy);
                chunkPromises[dy].push(this.mapsApi.getChunk(chunkMapTile));
            }
        }
        return Promise.all(chunkPromises.map(row => Promise.all(row)))
            .then((chunks) => new chunks_1.SuperChunk(chunks));
    }
    findContainingSuperChunk(lat, lng) {
        for (var dx = 0; dx < this.superChunks[0].length; dx++) {
            for (var dy = 0; dy < this.superChunks.length; dy++) {
                if (this.superChunks[dy][dx] != null) {
                    if (this.superChunks[dy][dx].containsWorldPosition(lat, lng)) {
                        return new vector_1.Vector2(dx, dy);
                    }
                }
            }
        }
    }
    shiftSuperChunks(superChunkIndex) {
        if (superChunkIndex.x < this.midMemory) {
            this.shiftLeft();
        }
        else if (superChunkIndex.x > this.midMemory) {
            this.shiftRight();
        }
        if (superChunkIndex.y < this.midMemory) {
            this.shiftUp();
        }
        else if (superChunkIndex.y > this.midMemory) {
            this.shiftDown();
        }
    }
    shiftLeft() {
        for (var dy = 0; dy < this.superChunkMemory; dy++) {
            for (var dx = this.superChunkMemory - 1; dx > 0; dx--) {
                this.background[dy][dx] = this.background[dy][dx - 1];
                this.superChunks[dy][dx] = this.superChunks[dy][dx - 1];
            }
            this.background[dy][0] = null;
            this.superChunks[dy][0] = null;
        }
    }
    shiftRight() {
        for (var dy = 0; dy < this.superChunkMemory; dy++) {
            for (var dx = 0; dx < this.superChunkMemory - 1; dx++) {
                this.background[dy][dx] = this.background[dy][dx + 1];
                this.superChunks[dy][dx] = this.superChunks[dy][dx + 1];
            }
            this.background[dy][this.superChunkMemory - 1] = null;
            this.superChunks[dy][this.superChunkMemory - 1] = null;
        }
    }
    shiftUp() {
        for (var dx = 0; dx < this.superChunkMemory; dx++) {
            for (var dy = this.superChunkMemory - 1; dy > 0; dy--) {
                this.background[dy][dx] = this.background[dy - 1][dx];
                this.superChunks[dy][dx] = this.superChunks[dy - 1][dx];
            }
            this.background[0][dx] = null;
            this.superChunks[0][dx] = null;
        }
    }
    shiftDown() {
        for (var dx = 0; dx < this.superChunkMemory; dx++) {
            for (var dy = 0; dy < this.superChunkMemory - 1; dy++) {
                this.background[dy][dx] = this.background[dy + 1][dx];
                this.superChunks[dy][dx] = this.superChunks[dy + 1][dx];
            }
            this.background[this.superChunkMemory - 1][dx] = null;
            this.superChunks[this.superChunkMemory - 1][dx] = null;
        }
    }
    loadNeighSuperChunks(offset) {
        var superChunk = this.superChunks[this.midMemory][this.midMemory];
        var deltaX = 0;
        var deltaY = 0;
        if (offset.x < this.maxDist) {
            deltaX = -1;
        }
        else if (offset.x > superChunk.getWidth() - this.maxDist) {
            deltaX = 1;
        }
        if (offset.y < this.maxDist) {
            deltaY = -1;
        }
        else if (offset.y > superChunk.getHeight() - this.maxDist) {
            deltaY = 1;
        }
        if (deltaX === -1 && this.background[this.midMemory][this.midMemory - 1] === null) {
            this.loadChunk(offset, new vector_1.Vector2(deltaX, 0));
            if (deltaY != 0) {
                this.loadChunk(offset, new vector_1.Vector2(deltaX, deltaY));
            }
        }
        else if (deltaX === 1 && this.background[this.midMemory][this.midMemory + 1] === null) {
            this.loadChunk(offset, new vector_1.Vector2(deltaX, 0));
            if (deltaY != 0) {
                this.loadChunk(offset, new vector_1.Vector2(deltaX, deltaY));
            }
        }
        if (deltaY === -1 && this.background[this.midMemory - 1][this.midMemory] === null) {
            this.loadChunk(offset, new vector_1.Vector2(0, deltaY));
            if (deltaX != 0) {
                this.loadChunk(offset, new vector_1.Vector2(deltaX, deltaY));
            }
        }
        else if (deltaY === 1 && this.background[this.midMemory + 1][this.midMemory] === null) {
            this.loadChunk(offset, new vector_1.Vector2(0, deltaY));
            if (deltaX != 0) {
                this.loadChunk(offset, new vector_1.Vector2(deltaX, deltaY));
            }
        }
    }
    loadChunk(offset, delta) {
        console.log("Load...");
        var data = {
            delta: delta,
            lat: this.lat,
            lng: this.lng,
            zoom: this.zoom,
            superChunkSize: this.superChunkSize,
            mapsApi: this.mapsApi,
            game: this.game,
            midMemory: this.midMemory,
            extraOffset: this.extraOffset,
            background: this.background,
            superChunks: this.superChunks,
            getSuperChunk: this.getSuperChunk
        };
        this.background[this.midMemory + delta.y][this.midMemory + delta.x] = undefined;
        // var worker: ITypedWorker<SuperChunkLoaderData, void> = createWorker((data: SuperChunkLoaderData) => {
        data.getSuperChunk.bind(data)(delta.x, delta.y).then((superChunk) => {
            var map = new mapPainter_1.MapPainter(superChunk).drawSuperChunk(data.game, superChunk);
            var bitmap = map.bitmap;
            var positionX = (bitmap.width - data.extraOffset) * delta.x
                - offset.x + data.game.world.width / 2;
            var positionY = (bitmap.height - data.extraOffset) * delta.y
                - offset.y + data.game.world.height / 2;
            var backgroundSprites = map.toBackgroundSprites(data.game, positionX, positionY);
            data.background[data.midMemory + delta.y][data.midMemory + delta.x] = backgroundSprites;
            data.superChunks[data.midMemory + delta.y][data.midMemory + delta.x] = superChunk;
        });
        // };
        // worker.postMessage(data);
    }
}
exports.GameMap = GameMap;
new GameMap();

},{"../utils/vector":13,"./chunks":1,"./googleMapsApi":3,"./mapPainter":6,"./worldPosition":9}],3:[function(require,module,exports){
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

},{"./chunks":1}],4:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const hashmap_1 = require("../utils/hashmap");
const sprites_1 = require("./sprites");
const landTypes_1 = require("./landTypes");
const vector_1 = require("../utils/vector");
class Kingdom {
    static initialize() {
        this.kingdomMap = new hashmap_1.HashMap();
        this.kingdomMap.set(landTypes_1.KingdomType.SNOW, SnowKingdom.instance);
        this.kingdomMap.set(landTypes_1.KingdomType.BEACH, BeachKingdom.instance);
    }
    static of(info) {
        return this.kingdomMap.get(info.kingdomType);
    }
    constructor(sprites) {
        this._sprites = sprites;
    }
    get sprites() {
        return this._sprites;
    }
}
exports.Kingdom = Kingdom;
class SnowKingdom extends Kingdom {
    static get instance() {
        if (this._instance == null) {
            this._instance = new SnowKingdom();
        }
        return this._instance;
    }
    constructor() {
        var terrainMap = new hashmap_1.HashMap();
        terrainMap.set(landTypes_1.TerrainType.LAND, new sprites_1.TileSprite("snowy_land"));
        terrainMap.set(landTypes_1.TerrainType.ROAD, new sprites_1.TileSprite("snowy_path5"));
        terrainMap.set(landTypes_1.TerrainType.WATER, new sprites_1.TileSprite("snowy_path5"));
        var corners = ["snowy_path5", "snowy_path5", "snowy_path5", "snowy_path5"].map(name => new sprites_1.TileSprite(name));
        var sides = ["snowy_path4", "snowy_path2", "snowy_path6", "snowy_path8"].map(name => new sprites_1.TileSprite(name));
        var oppositeCorners = ["snowy_path5", "snowy_path5"].map(name => new sprites_1.TileSprite(name));
        var lShapes = ["snowy_path1", "snowy_path3", "snowy_path9", "snowy_path7"].map(name => new sprites_1.TileSprite(name));
        var cornersMap = new hashmap_1.HashMap();
        cornersMap.set({ kingdomType: landTypes_1.KingdomType.SNOW, terrainType: landTypes_1.TerrainType.ROAD }, corners);
        var sidesMap = new hashmap_1.HashMap();
        sidesMap.set({ kingdomType: landTypes_1.KingdomType.SNOW, terrainType: landTypes_1.TerrainType.ROAD }, sides);
        var oppositeCornersMap = new hashmap_1.HashMap();
        oppositeCornersMap.set({ kingdomType: landTypes_1.KingdomType.SNOW, terrainType: landTypes_1.TerrainType.ROAD }, oppositeCorners);
        var lShapesMap = new hashmap_1.HashMap();
        lShapesMap.set({ kingdomType: landTypes_1.KingdomType.SNOW, terrainType: landTypes_1.TerrainType.ROAD }, lShapes);
        super({
            minigames: ["flappybird", "flappybird", "binary-land"],
            terrain: terrainMap,
            corners: cornersMap,
            transparentCorners: corners,
            sides: sidesMap,
            transparentSides: sides,
            oppositeCorners: oppositeCornersMap,
            transparentOppositeCorners: oppositeCorners,
            lShapes: lShapesMap,
            transparentLShapes: lShapes,
            decorations: [
                new sprites_1.DecorationSprite("pipe", {
                    minigame: true,
                    anchorX: 24 - 12 + 16,
                    anchorY: 38 - 28 + 16,
                    hFlipable: true,
                    range: {
                        start: new vector_1.Vector2(-2, -3),
                        end: new vector_1.Vector2(-1, 0)
                    },
                    odds: {
                        1: 0.005 * 3,
                    }
                }),
                new sprites_1.DecorationSprite("snowy_tree", {
                    anchorX: 40 - 18 + 16,
                    anchorY: 44 - 28 + 16,
                    hFlipable: true,
                    range: {
                        start: new vector_1.Vector2(-2, -2),
                        end: new vector_1.Vector2(0, 0)
                    },
                    perlinNoiseProps: {
                        octaveCount: 1,
                        scale: 15000,
                        lacunarity: 1,
                        persistance: 1
                    },
                    odds: {
                        0.15: 0.1,
                        0.3: 1,
                        0.5: 0.3,
                        1: 0.05
                    }
                }),
                new sprites_1.DecorationSprite("small_cut_tree", {
                    hFlipable: true,
                    perlinNoiseProps: {
                        octaveCount: 3,
                        scale: 1000,
                        lacunarity: 2,
                        persistance: 0.5
                    },
                    odds: {
                        0.1: 0.005,
                        0.3: 0.01,
                        0.7: 0.002,
                        1: 0.0
                    }
                }),
                new sprites_1.DecorationSprite("snowy_small_plant", {
                    hFlipable: true,
                    perlinNoiseProps: {
                        octaveCount: 3,
                        scale: 1000,
                        lacunarity: 2,
                        persistance: 0.5
                    },
                    odds: {
                        0.1: 0,
                        0.3: 0.01,
                        0.7: 0.01,
                        1: 0.003
                    }
                }),
                new sprites_1.DecorationSprite("snowy_plant", {
                    hFlipable: true,
                    perlinNoiseProps: {
                        octaveCount: 3,
                        scale: 1000,
                        lacunarity: 2,
                        persistance: 0.5
                    },
                    odds: {
                        0.1: 0,
                        0.3: 0.01,
                        0.7: 0.02,
                        1: 0.003
                    }
                }),
            ]
        });
    }
}
exports.SnowKingdom = SnowKingdom;
// TODO: Update sprites.
class BeachKingdom extends Kingdom {
    static get instance() {
        if (this._instance == null) {
            this._instance = new BeachKingdom();
        }
        return this._instance;
    }
    constructor() {
        var terrainMap = new hashmap_1.HashMap();
        terrainMap.set(landTypes_1.TerrainType.LAND, new sprites_1.TileSprite("beach_land"));
        terrainMap.set(landTypes_1.TerrainType.ROAD, new sprites_1.TileSprite("beach_pond5"));
        terrainMap.set(landTypes_1.TerrainType.WATER, new sprites_1.TileSprite("beach_pond5"));
        var corners = ["beach_pond10", "beach_pond11", "beach_pond13", "beach_pond12"].map(name => new sprites_1.TileSprite(name));
        var sides = ["beach_pond4", "beach_pond2", "beach_pond6", "beach_pond8"].map(name => new sprites_1.TileSprite(name));
        var oppositeCorners = ["beach_pond14", "beach_pond14"].map(name => new sprites_1.TileSprite(name));
        var lShapes = ["beach_pond1", "beach_pond3", "beach_pond9", "beach_pond7"].map(name => new sprites_1.TileSprite(name));
        var cornersMap = new hashmap_1.HashMap();
        cornersMap.set({ kingdomType: landTypes_1.KingdomType.BEACH, terrainType: landTypes_1.TerrainType.ROAD }, corners);
        var sidesMap = new hashmap_1.HashMap();
        sidesMap.set({ kingdomType: landTypes_1.KingdomType.BEACH, terrainType: landTypes_1.TerrainType.ROAD }, sides);
        var oppositeCornersMap = new hashmap_1.HashMap();
        oppositeCornersMap.set({ kingdomType: landTypes_1.KingdomType.BEACH, terrainType: landTypes_1.TerrainType.ROAD }, oppositeCorners);
        var lShapesMap = new hashmap_1.HashMap();
        lShapesMap.set({ kingdomType: landTypes_1.KingdomType.BEACH, terrainType: landTypes_1.TerrainType.ROAD }, lShapes);
        super({
            minigames: ["piou-piou"],
            terrain: terrainMap,
            corners: cornersMap,
            transparentCorners: corners,
            sides: sidesMap,
            transparentSides: sides,
            oppositeCorners: oppositeCornersMap,
            transparentOppositeCorners: oppositeCorners,
            lShapes: lShapesMap,
            transparentLShapes: lShapes,
            decorations: [
                new sprites_1.DecorationSprite("pipe", {
                    minigame: true,
                    anchorX: 24 - 12 + 16,
                    anchorY: 38 - 28 + 16,
                    hFlipable: true,
                    range: {
                        start: new vector_1.Vector2(-2, -3),
                        end: new vector_1.Vector2(-1, 0)
                    },
                    odds: {
                        1: 0.02 * 3,
                    }
                }),
                new sprites_1.DecorationSprite("palm_tree", {
                    anchorX: 36 - 18 + 16,
                    anchorY: 44 - 28 + 16,
                    hFlipable: true,
                    range: {
                        start: new vector_1.Vector2(-2, 0),
                        end: new vector_1.Vector2(-1, 0)
                    },
                    perlinNoiseProps: {
                        octaveCount: 1,
                        scale: 15000,
                        lacunarity: 1,
                        persistance: 1
                    },
                    odds: {
                        0.15: 0.1,
                        0.3: 1,
                        0.5: 0.3,
                        1: 0.05
                    }
                }),
                new sprites_1.DecorationSprite("conch", {
                    hFlipable: true,
                    perlinNoiseProps: {
                        octaveCount: 3,
                        scale: 1000,
                        lacunarity: 2,
                        persistance: 0.5
                    },
                    odds: {
                        0.1: 0.03,
                        0.3: 0.01,
                        0.7: 0.02,
                        1: 0.0
                    }
                }),
                new sprites_1.DecorationSprite("shell", {
                    hFlipable: true,
                    perlinNoiseProps: {
                        octaveCount: 3,
                        scale: 1000,
                        lacunarity: 2,
                        persistance: 0.5
                    },
                    odds: {
                        0.1: 0,
                        0.3: 0.01,
                        0.7: 0.02,
                        1: 0.03
                    }
                }),
            ]
        });
    }
}
exports.BeachKingdom = BeachKingdom;
Kingdom.initialize();

},{"../utils/hashmap":10,"../utils/vector":13,"./landTypes":5,"./sprites":8}],5:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var TerrainType;
(function (TerrainType) {
    TerrainType[TerrainType["LAND"] = 0] = "LAND";
    TerrainType[TerrainType["ROAD"] = 1] = "ROAD";
    TerrainType[TerrainType["WATER"] = 2] = "WATER";
    TerrainType[TerrainType["TOTAL"] = 3] = "TOTAL";
})(TerrainType = exports.TerrainType || (exports.TerrainType = {}));
var KingdomType;
(function (KingdomType) {
    KingdomType[KingdomType["BEACH"] = 0] = "BEACH";
    KingdomType[KingdomType["SNOW"] = 1] = "SNOW";
    KingdomType[KingdomType["TOTAL"] = 2] = "TOTAL";
})(KingdomType = exports.KingdomType || (exports.KingdomType = {}));
var Decoration;
(function (Decoration) {
    Decoration[Decoration["NONE"] = 0] = "NONE";
    Decoration[Decoration["SHELL"] = 1] = "SHELL";
    Decoration[Decoration["CONCH"] = 2] = "CONCH";
    Decoration[Decoration["PALM_TREE"] = 3] = "PALM_TREE";
})(Decoration = exports.Decoration || (exports.Decoration = {}));
class TileInfo {
}
exports.TileInfo = TileInfo;

},{}],6:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const random_1 = require("../utils/random");
const landTypes_1 = require("./landTypes");
const kingdoms_1 = require("./kingdoms");
const perlin_1 = require("../utils/perlin");
class MapPainter {
    constructor(superChunk) {
        this.tileWidth = 16;
        this.tileHeight = 16;
        this.random = new random_1.Random(superChunk.getId());
    }
    drawSuperChunk(game, superChunk) {
        var tileWidth = this.tileWidth;
        var tileHeight = this.tileHeight;
        var bitmap = new Phaser.BitmapData(game, "chunk-bitmap", superChunk.getWidth(), superChunk.getHeight());
        console.log("Got chunk");
        var terrainMap = superChunk.buildTerrainMap(tileWidth, tileHeight);
        console.log("Build terrain");
        var kingdomMap = superChunk.buildKingdomMap(tileWidth, tileHeight, 1, 15000, 1, 1);
        console.log("Build kingdom");
        for (var dy = 0; dy < terrainMap.length - 1; dy++) {
            for (var dx = 0; dx < terrainMap[0].length - 1; dx++) {
                var neighs = [];
                for (var k = 0; k < 4; k++) {
                    neighs[k] = {
                        kingdomType: kingdomMap[dy + (k <= 1 ? 0 : 1)][dx + (k % 3 == 0 ? 0 : 1)],
                        terrainType: terrainMap[dy + (k <= 1 ? 0 : 1)][dx + (k % 3 == 0 ? 0 : 1)]
                    };
                }
                var tileBitmap = this.getTileBitmap(game, neighs);
                bitmap.draw(tileBitmap, dx * tileWidth, dy * tileHeight);
            }
        }
        console.log("Build bitmap");
        var minigames = [];
        var decorationsMap = [];
        var decorationOffset = 3;
        for (var dy = 0; dy < terrainMap.length - decorationOffset; dy++) {
            decorationsMap.push([]);
            for (var dx = 0; dx < terrainMap[0].length - decorationOffset; dx++) {
                decorationsMap[dy].push(landTypes_1.Decoration.NONE);
                if (dy > decorationOffset && dx > decorationOffset) {
                    var decorationSprites = kingdoms_1.Kingdom.of({ kingdomType: kingdomMap[dy][dx] }).sprites.decorations;
                    for (var decorationSprite of decorationSprites) {
                        var worldPos = superChunk.getWorldPosition(dx * tileWidth, dy * tileHeight);
                        var exists = this.existsDecoration(decorationSprite, worldPos);
                        // console.log(decorationSprite);
                        // console.log(exists);
                        if (!exists) {
                            continue;
                        }
                        var fits = this.fitsDecoration(decorationSprite, dx, dy, decorationsMap);
                        // console.log(fits);
                        if (!fits) {
                            continue;
                        }
                        decorationsMap[dy][dx] = landTypes_1.Decoration.CONCH;
                        var visible = this.isVisibleDecoration(decorationSprite, dx, dy, terrainMap);
                        if (visible) {
                            // console.log(dx * tileWidth - decorationSprite.props.anchorX, 
                            //     dy * tileHeight - decorationSprite.props.anchorY - 8);
                            var posX = dx * tileWidth - decorationSprite.props.anchorX - 8;
                            var posY = dy * tileHeight - decorationSprite.props.anchorY - 8;
                            var decoBitmap = decorationSprite.toBitmap(game);
                            bitmap.draw(decoBitmap, posX, posY);
                            this.fillRange(decorationSprite, dx, dy, decorationsMap);
                            if (decorationSprite.props.minigame) {
                                var rand = new random_1.Random(decorationSprite.name + worldPos.toString());
                                var minigameNames = kingdoms_1.Kingdom.of({ kingdomType: kingdomMap[dy][dx] }).sprites.minigames;
                                var val = rand.nextInt(0, minigameNames.length - 1);
                                var name = minigameNames[val];
                                minigames.push({
                                    name: name,
                                    dx: posX,
                                    dy: posY,
                                    width: decoBitmap.width,
                                    height: decoBitmap.height
                                });
                            }
                            break;
                        }
                    }
                    // var decoration: Decoration = this.getDecoration(terrainMap, decorationsMap, x, y); 
                    // if (decoration !== Decoration.NONE) {
                    //     decorationsMap[y][x] = decoration;
                    //     var decoSprite: DecorationSprite = this.getImageName(decoration);
                    //     bitmap.draw(game.make.sprite(0, 0, 'map-tiles', decoSprite.name), 
                    //         x * tileWidth - decoSprite.posX, y * tileHeight - decoSprite.posY);         
                    // }
                }
            }
        }
        return new PaintedMap(bitmap, minigames);
    }
    fitsDecoration(decorationSprite, x, y, decorationsMap) {
        if (decorationSprite.props.range == null) {
            return true;
        }
        var range = decorationSprite.props.range;
        return this.checkNeighs(decorationsMap, x + range.start.x, y + range.start.y, range.end.x + x, range.end.y + y, landTypes_1.Decoration.NONE);
    }
    isVisibleDecoration(decorationSprite, x, y, terrainMap) {
        if (terrainMap[y][x] !== landTypes_1.TerrainType.LAND) {
            return false;
        }
        if (decorationSprite.props.range == null) {
            return true;
        }
        var range = decorationSprite.props.range;
        return this.checkNeighs(terrainMap, x + range.start.x, y + range.start.y, range.end.x + x, range.end.y + y, landTypes_1.TerrainType.LAND);
    }
    existsDecoration(decorationSprite, worldPosition) {
        var randValue;
        var rand = new random_1.Random(decorationSprite.name + worldPosition.toString());
        if (decorationSprite.props.perlinNoiseProps != null) {
            var noiseGen = new perlin_1.Simplex(rand);
            var noiseProps = decorationSprite.props.perlinNoiseProps;
            randValue = noiseGen.getNoise(worldPosition, noiseProps.octaveCount, noiseProps.scale, noiseProps.lacunarity, noiseProps.persistance);
        }
        else {
            randValue = rand.next();
        }
        var key;
        for (var rangeMax in decorationSprite.props.odds) {
            if (randValue < Number(rangeMax)) {
                key = Number(rangeMax);
            }
        }
        var odds = decorationSprite.props.odds[rangeMax];
        var newRand = rand.next();
        return newRand < odds;
    }
    fillRange(decorationSprite, x, y, map) {
        if (decorationSprite.props.range == null) {
            return;
        }
        var range = decorationSprite.props.range;
        for (var dx = range.start.x + x; dx <= range.end.x + x; dx++) {
            for (var dy = range.start.y + y; dy <= range.end.y + y; dy++) {
                if (dx < 0 || dy < 0 || dx >= map.length[0] || dy >= map.length) {
                    continue;
                }
                map[dy][dx] = landTypes_1.Decoration.CONCH;
            }
        }
    }
    // private getDecoration(tilesMap: TerrainType[][], decorationsMap: Decoration[][],
    //     x: number, y: number): Decoration {
    //  //   console.log(y, x);
    //     if (tilesMap[y][x] !== TerrainType.LAND) {
    //         return Decoration.NONE;
    //     }
    //     var rand: number = this.random.next(0, 100);
    //     if (rand < 3) {
    //         return Decoration.CONCH;
    //     } else if (rand < 8) {
    //         return Decoration.SHELL;
    //     } else if (rand < 40) {
    //         if (this.checkNeighs(tilesMap, x - 1, y, x, y, TerrainType.LAND)
    //             && this.checkNeighs(decorationsMap, x - 1, y, x, y, Decoration.NONE)) {
    //             return Decoration.PALM_TREE;
    //         }
    //     }
    //     return Decoration.NONE;
    // }
    checkNeighs(map, startX, startY, endX, endY, value) {
        if (startX < 0 || startY < 0 || startX > endX
            || startY > endY || endX >= map.length[0] || endY >= map.length) {
            return false;
        }
        for (var dx = startX; dx <= endX; dx++) {
            for (var dy = startY; dy <= endY; dy++) {
                if (map[dy][dx] !== value) {
                    return false;
                }
            }
        }
        return true;
    }
    //    private getImageName(decoration: Decoration): DecorationSprite {
    //        switch (decoration) {
    //            case Decoration.CONCH:
    //                return new DecorationSprite(0, 0, "conch.png");
    //            case Decoration.SHELL:
    //                return new DecorationSprite(0, 0, "shell.png");
    //            case Decoration.PALM_TREE:
    //                return new DecorationSprite(36 - 18, 44 - 28, "palm_tree.png");
    //            default:
    //                throw Error("Uknown decoration: " + decoration);
    //        }
    //    }
    getTileBitmap(game, neighs) {
        var encoded = 0;
        for (var i = 0; i < 4; i++) {
            encoded += neighs[i].terrainType == landTypes_1.TerrainType.LAND ? 1 << i : 0;
        }
        if (encoded == 0) {
            return this.getBitmap(game, neighs, encoded, [0], [0, 0, 0, 0], null);
        }
        else if (encoded == 15) {
            return this.getBitmap(game, neighs, encoded, [15], [1, 1, 1, 1], null);
        }
        else if ((encoded & (encoded - 1)) == 0) {
            return this.getBitmap(game, neighs, encoded, [1, 2, 4, 8], [1, 0, 0, 0], "corners");
        }
        else if (encoded % 3 == 0) {
            return this.getBitmap(game, neighs, encoded, [9, 3, 6, 12], [1, 0, 0, 1], "sides");
        }
        else if (encoded % 5 == 0) {
            return this.getBitmap(game, neighs, encoded, [5, 10], [1, 0, 1, 0], "oppositeCorners");
        }
        else {
            return this.getBitmap(game, neighs, encoded, [11, 7, 14, 13], [1, 1, 0, 1], "lShapes");
        }
    }
    getBitmap(game, neighs, encoded, encodings, lands, type) {
        var rotation = 0;
        for (var i = 0; i < encodings.length; i++) {
            if (encodings[i] == encoded) {
                rotation = i;
                break;
            }
        }
        var rotated = this.rotateArray(neighs, -rotation);
        var transitionType = TransitionType.DEFINED;
        // console.log(rotated);
        for (var i = 0; i < lands.length && transitionType == TransitionType.DEFINED; i++) {
            for (var j = 0; j < lands.length && transitionType == TransitionType.DEFINED; j++) {
                if (lands[i] == lands[j] && rotated[i].kingdomType != rotated[j].kingdomType) {
                    transitionType = TransitionType.TRANSPARENCY;
                }
            }
        }
        return this.getBitmapFromRotated(game, rotated, rotation, lands, type, transitionType);
    }
    getBitmapFromRotated(game, rotated, rotation, lands, type, transitionType) {
        var bitmap;
        var land = rotated[0];
        var other;
        for (var i = 0; i < lands.length; i++) {
            if (lands[i] === 0) {
                other = rotated[i];
                break;
            }
        }
        // If transition is defined, return the corresponding tile.
        if (transitionType === TransitionType.DEFINED) {
            if (other == null) {
                return kingdoms_1.Kingdom.of(land).sprites.terrain.get(land.terrainType).toBitmap(game);
            }
            else if (land.terrainType === other.terrainType) {
                return kingdoms_1.Kingdom.of(other).sprites.terrain.get(other.terrainType).toBitmap(game);
            }
            // Get side that transitions to the next type.
            var tiles = kingdoms_1.Kingdom.of(land).sprites[type].get(other);
            if (tiles != null && tiles[rotation] != null) {
                return tiles[rotation].toBitmap(game);
            }
        }
        // console.log(transitionType);
        // console.log(land, other);
        // Get the corresponding subblocks for the land and other terrains.
        var backgroundSubBlocks = [];
        var landsSubBlocks = [];
        if (type != null) {
            var transparentType = "transparent" + type.charAt(0).toUpperCase() + type.slice(1);
        }
        for (var i = 0; i < 4; i++) {
            var refTile = rotated[i];
            if (lands[i] === 1) {
                // Find first tile that isn't land.
                for (var j = 0; j < lands.length; j++) {
                    if (lands[j] === 0) {
                        refTile = rotated[j];
                        break;
                    }
                }
                if (type != null) {
                    landsSubBlocks[i] = kingdoms_1.Kingdom.of(rotated[i]).sprites[transparentType][rotation]
                        .subBlock(game, (i + rotation) % 4);
                }
                else {
                    landsSubBlocks[i] = kingdoms_1.Kingdom.of(rotated[i]).sprites.terrain.get(rotated[i].terrainType)
                        .subBlock(game, (i + rotation) % 4);
                }
            }
            backgroundSubBlocks[i] = kingdoms_1.Kingdom.of(refTile).sprites.terrain.get(refTile.terrainType)
                .subBlock(game, (i + rotation) % 4);
        }
        // Place the subchunks in a bitmap in the right position.
        var bitMap = new Phaser.BitmapData(game, "tile", 16, 16);
        for (var i = 0; i < 4; i++) {
            var rotI = (i + rotation + 4) % 4;
            var x = rotI % 3 == 0 ? 0 : 8;
            var y = rotI <= 1 ? 0 : 8;
            bitMap.draw(backgroundSubBlocks[i], x, y);
            if (lands[i] === 1) {
                bitMap.draw(landsSubBlocks[i], x, y);
            }
        }
        return bitMap;
    }
    rotateArray(arr, shift) {
        var newArr = [];
        for (var i = 0; i < 4; i++) {
            newArr[i] = arr[(i - shift + 4) % 4];
        }
        return newArr;
    }
}
exports.MapPainter = MapPainter;
class PaintedMap {
    constructor(bitmap, minigames) {
        this._bitmap = bitmap;
        this._minigames = minigames;
    }
    toBackgroundSprites(game, positionX, positionY) {
        var backgroundSprites = [];
        backgroundSprites.push({ sprite: game.add.sprite(positionX + 0, positionY + 0, this.bitmap), dx: 0, dy: 0 });
        // backgroundSprites[0].sprite.inputEnabled = true;
        // backgroundSprites[0].sprite.events.onInputDown.add(this.listener, this);
        this.minigames.forEach((minigame) => {
            var sprite = game.add.sprite(positionX + minigame.dx, positionY + minigame.dy);
            sprite.width = minigame.width;
            sprite.height = minigame.height;
            sprite.inputEnabled = true;
            sprite.events.onInputDown.add(() => {
                window.location.href = "./games/" + minigame.name;
            });
            backgroundSprites.push({ sprite: sprite, dx: minigame.dx, dy: minigame.dy });
        });
        return backgroundSprites;
    }
    get bitmap() {
        return this._bitmap;
    }
    get minigames() {
        return this._minigames;
    }
}
exports.PaintedMap = PaintedMap;
var TransitionType;
(function (TransitionType) {
    TransitionType[TransitionType["DEFINED"] = 0] = "DEFINED";
    TransitionType[TransitionType["TRANSPARENCY"] = 1] = "TRANSPARENCY";
})(TransitionType = exports.TransitionType || (exports.TransitionType = {}));

},{"../utils/perlin":11,"../utils/random":12,"./kingdoms":4,"./landTypes":5}],7:[function(require,module,exports){
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

},{"../utils/vector":13,"./googleMapsApi":3,"./worldPosition":9}],8:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const random_1 = require("../utils/random");
class TileSprite {
    constructor(name) {
        this.name = name;
    }
    toBitmap(game) {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap = new Phaser.BitmapData(game, this.name, 16, 16);
        bitmap.draw(this.sprite);
        return bitmap;
    }
    subBlock(game, index) {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap = new Phaser.BitmapData(game, this.name, 8, 8);
        var x = index % 3 == 0 ? 0 : 8;
        var y = index <= 1 ? 0 : 8;
        bitmap.copyRect(this.sprite, new Phaser.Rectangle(x, y, 8, 8), 0, 0);
        return bitmap;
    }
}
exports.TileSprite = TileSprite;
class DecorationSprite {
    constructor(name, props) {
        this._name = name;
        this._props = props;
        if (this._props.anchorX == null) {
            this._props.anchorX = 0;
        }
        if (this._props.anchorY == null) {
            this._props.anchorY = 0;
        }
    }
    toBitmap(game) {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap = new Phaser.BitmapData(game, this.name, this.sprite.width, this.sprite.height);
        var flip = 1;
        // var flip: number = this.props.hFlipable && Math.random() < 0.5 ? -1 : 1;
        var rotate = 0;
        if (this.props.rotations != null) {
            rotate = this.props.rotations[new random_1.Random().nextInt(0, this.props.rotations.length - 1)];
        }
        return bitmap.copy(this.sprite, 0, 0, null, null, null, null, null, null, rotate, null, null, flip);
    }
    get name() {
        return this._name;
    }
    get props() {
        return this._props;
    }
}
exports.DecorationSprite = DecorationSprite;

},{"../utils/random":12}],9:[function(require,module,exports){
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

},{"../utils/vector":13,"./mapTile":7}],10:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class HashMap {
    constructor() {
        this.map = new Map();
    }
    get(key) {
        return this.map.get(this.hash(key));
    }
    set(key, value) {
        this.map.set(this.hash(key), value);
    }
    hash(key) {
        return JSON.stringify(key);
    }
}
exports.HashMap = HashMap;

},{}],11:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
// https://gist.github.com/banksean/304522
//
// Ported from Stefan Gustavson's java implementation
// http://staffwww.itn.liu.se/~stegu/simplexnoise/simplexnoise.pdf
// Read Stefan's excellent paper for details on how this code works.
//
// Sean McCullough banksean@gmail.com
/**
 * You can pass in a random number generator object if you like.
 * It is assumed to have a random() method.
 */
class Simplex {
    constructor(r) {
        this.grad3 = [[1, 1, 0], [-1, 1, 0], [1, -1, 0], [-1, -1, 0],
            [1, 0, 1], [-1, 0, 1], [1, 0, -1], [-1, 0, -1],
            [0, 1, 1], [0, -1, 1], [0, 1, -1], [0, -1, -1]];
        this.p = [];
        for (var i = 0; i < 256; i++) {
            this.p[i] = Math.floor(r.next() * 256);
        }
        // To remove the need for index wrapping, double the permutation table length 
        this.perm = [];
        for (var i = 0; i < 512; i++) {
            this.perm[i] = this.p[i & 255];
        }
        // A lookup table to traverse the simplex around a given point in 4D. 
        // Details can be found where this table is used, in the 4D noise method. 
        this.simplex = [
            [0, 1, 2, 3], [0, 1, 3, 2], [0, 0, 0, 0], [0, 2, 3, 1], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [1, 2, 3, 0],
            [0, 2, 1, 3], [0, 0, 0, 0], [0, 3, 1, 2], [0, 3, 2, 1], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [1, 3, 2, 0],
            [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0],
            [1, 2, 0, 3], [0, 0, 0, 0], [1, 3, 0, 2], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [2, 3, 0, 1], [2, 3, 1, 0],
            [1, 0, 2, 3], [1, 0, 3, 2], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [2, 0, 3, 1], [0, 0, 0, 0], [2, 1, 3, 0],
            [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0],
            [2, 0, 1, 3], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [3, 0, 1, 2], [3, 0, 2, 1], [0, 0, 0, 0], [3, 1, 2, 0],
            [2, 1, 0, 3], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [3, 1, 0, 2], [0, 0, 0, 0], [3, 2, 0, 1], [3, 2, 1, 0]
        ];
    }
    dot(g, x, y, z) {
        return g[0] * x + g[1] * y + (z != null ? g[2] * z : 0);
    }
    ;
    noise(xin, yin) {
        var n0, n1, n2; // Noise contributions from the three corners 
        // Skew the input space to determine which simplex cell we're in 
        var F2 = 0.5 * (Math.sqrt(3.0) - 1.0);
        var s = (xin + yin) * F2; // Hairy factor for 2D 
        var i = Math.floor(xin + s);
        var j = Math.floor(yin + s);
        var G2 = (3.0 - Math.sqrt(3.0)) / 6.0;
        var t = (i + j) * G2;
        var X0 = i - t; // Unskew the cell origin back to (x,y) space 
        var Y0 = j - t;
        var x0 = xin - X0; // The x,y distances from the cell origin 
        var y0 = yin - Y0;
        // For the 2D case, the simplex shape is an equilateral triangle. 
        // Determine which simplex we are in. 
        var i1, j1; // Offsets for second (middle) corner of simplex in (i,j) coords 
        if (x0 > y0) {
            i1 = 1;
            j1 = 0;
        } // lower triangle, XY order: (0,0)->(1,0)->(1,1) 
        else {
            i1 = 0;
            j1 = 1;
        } // upper triangle, YX order: (0,0)->(0,1)->(1,1) 
        // A step of (1,0) in (i,j) means a step of (1-c,-c) in (x,y), and 
        // a step of (0,1) in (i,j) means a step of (-c,1-c) in (x,y), where 
        // c = (3-sqrt(3))/6 
        var x1 = x0 - i1 + G2; // Offsets for middle corner in (x,y) unskewed coords 
        var y1 = y0 - j1 + G2;
        var x2 = x0 - 1.0 + 2.0 * G2; // Offsets for last corner in (x,y) unskewed coords 
        var y2 = y0 - 1.0 + 2.0 * G2;
        // Work out the hashed gradient indices of the three simplex corners 
        var ii = i & 255;
        var jj = j & 255;
        var gi0 = this.perm[ii + this.perm[jj]] % 12;
        var gi1 = this.perm[ii + i1 + this.perm[jj + j1]] % 12;
        var gi2 = this.perm[ii + 1 + this.perm[jj + 1]] % 12;
        // Calculate the contribution from the three corners 
        var t0 = 0.5 - x0 * x0 - y0 * y0;
        if (t0 < 0)
            n0 = 0.0;
        else {
            t0 *= t0;
            n0 = t0 * t0 * this.dot(this.grad3[gi0], x0, y0); // (x,y) of grad3 used for 2D gradient 
        }
        var t1 = 0.5 - x1 * x1 - y1 * y1;
        if (t1 < 0)
            n1 = 0.0;
        else {
            t1 *= t1;
            n1 = t1 * t1 * this.dot(this.grad3[gi1], x1, y1);
        }
        var t2 = 0.5 - x2 * x2 - y2 * y2;
        if (t2 < 0)
            n2 = 0.0;
        else {
            t2 *= t2;
            n2 = t2 * t2 * this.dot(this.grad3[gi2], x2, y2);
        }
        // Add contributions from each corner to get the final noise value. 
        // The result is scaled to return values in the interval [-1,1]. 
        return 70.0 * (n0 + n1 + n2);
    }
    ;
    // 3D simplex noise 
    noise3d(xin, yin, zin) {
        var n0, n1, n2, n3; // Noise contributions from the four corners 
        // Skew the input space to determine which simplex cell we're in 
        var F3 = 1.0 / 3.0;
        var s = (xin + yin + zin) * F3; // Very nice and simple skew factor for 3D 
        var i = Math.floor(xin + s);
        var j = Math.floor(yin + s);
        var k = Math.floor(zin + s);
        var G3 = 1.0 / 6.0; // Very nice and simple unskew factor, too 
        var t = (i + j + k) * G3;
        var X0 = i - t; // Unskew the cell origin back to (x,y,z) space 
        var Y0 = j - t;
        var Z0 = k - t;
        var x0 = xin - X0; // The x,y,z distances from the cell origin 
        var y0 = yin - Y0;
        var z0 = zin - Z0;
        // For the 3D case, the simplex shape is a slightly irregular tetrahedron. 
        // Determine which simplex we are in. 
        var i1, j1, k1; // Offsets for second corner of simplex in (i,j,k) coords 
        var i2, j2, k2; // Offsets for third corner of simplex in (i,j,k) coords 
        if (x0 >= y0) {
            if (y0 >= z0) {
                i1 = 1;
                j1 = 0;
                k1 = 0;
                i2 = 1;
                j2 = 1;
                k2 = 0;
            } // X Y Z order 
            else if (x0 >= z0) {
                i1 = 1;
                j1 = 0;
                k1 = 0;
                i2 = 1;
                j2 = 0;
                k2 = 1;
            } // X Z Y order 
            else {
                i1 = 0;
                j1 = 0;
                k1 = 1;
                i2 = 1;
                j2 = 0;
                k2 = 1;
            } // Z X Y order 
        }
        else {
            if (y0 < z0) {
                i1 = 0;
                j1 = 0;
                k1 = 1;
                i2 = 0;
                j2 = 1;
                k2 = 1;
            } // Z Y X order 
            else if (x0 < z0) {
                i1 = 0;
                j1 = 1;
                k1 = 0;
                i2 = 0;
                j2 = 1;
                k2 = 1;
            } // Y Z X order 
            else {
                i1 = 0;
                j1 = 1;
                k1 = 0;
                i2 = 1;
                j2 = 1;
                k2 = 0;
            } // Y X Z order 
        }
        // A step of (1,0,0) in (i,j,k) means a step of (1-c,-c,-c) in (x,y,z), 
        // a step of (0,1,0) in (i,j,k) means a step of (-c,1-c,-c) in (x,y,z), and 
        // a step of (0,0,1) in (i,j,k) means a step of (-c,-c,1-c) in (x,y,z), where 
        // c = 1/6.
        var x1 = x0 - i1 + G3; // Offsets for second corner in (x,y,z) coords 
        var y1 = y0 - j1 + G3;
        var z1 = z0 - k1 + G3;
        var x2 = x0 - i2 + 2.0 * G3; // Offsets for third corner in (x,y,z) coords 
        var y2 = y0 - j2 + 2.0 * G3;
        var z2 = z0 - k2 + 2.0 * G3;
        var x3 = x0 - 1.0 + 3.0 * G3; // Offsets for last corner in (x,y,z) coords 
        var y3 = y0 - 1.0 + 3.0 * G3;
        var z3 = z0 - 1.0 + 3.0 * G3;
        // Work out the hashed gradient indices of the four simplex corners 
        var ii = i & 255;
        var jj = j & 255;
        var kk = k & 255;
        var gi0 = this.perm[ii + this.perm[jj + this.perm[kk]]] % 12;
        var gi1 = this.perm[ii + i1 + this.perm[jj + j1 + this.perm[kk + k1]]] % 12;
        var gi2 = this.perm[ii + i2 + this.perm[jj + j2 + this.perm[kk + k2]]] % 12;
        var gi3 = this.perm[ii + 1 + this.perm[jj + 1 + this.perm[kk + 1]]] % 12;
        // Calculate the contribution from the four corners 
        var t0 = 0.6 - x0 * x0 - y0 * y0 - z0 * z0;
        if (t0 < 0)
            n0 = 0.0;
        else {
            t0 *= t0;
            n0 = t0 * t0 * this.dot(this.grad3[gi0], x0, y0, z0);
        }
        var t1 = 0.6 - x1 * x1 - y1 * y1 - z1 * z1;
        if (t1 < 0)
            n1 = 0.0;
        else {
            t1 *= t1;
            n1 = t1 * t1 * this.dot(this.grad3[gi1], x1, y1, z1);
        }
        var t2 = 0.6 - x2 * x2 - y2 * y2 - z2 * z2;
        if (t2 < 0)
            n2 = 0.0;
        else {
            t2 *= t2;
            n2 = t2 * t2 * this.dot(this.grad3[gi2], x2, y2, z2);
        }
        var t3 = 0.6 - x3 * x3 - y3 * y3 - z3 * z3;
        if (t3 < 0)
            n3 = 0.0;
        else {
            t3 *= t3;
            n3 = t3 * t3 * this.dot(this.grad3[gi3], x3, y3, z3);
        }
        // Add contributions from each corner to get the final noise value. 
        // The result is scaled to stay just inside [-1,1] 
        return 32.0 * (n0 + n1 + n2 + n3);
    }
    getNoise(worldPos, octaveCount, scale, lacunarity, persistance) {
        var normLat = (worldPos.lat + 85) * scale / (85 * 2);
        var normLong = (worldPos.lng + 180) * scale / (180 * 2);
        var frequency = 1;
        var amplitude = 1;
        var noise = 0;
        for (var i = 0; i < octaveCount; i++) {
            noise += (this.noise(normLat, normLong) + 1) / 2;
            frequency *= lacunarity;
            amplitude *= persistance;
        }
        return noise;
    }
}
exports.Simplex = Simplex;
;

},{}],12:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class Random {
    constructor(seed = undefined) {
        if (typeof (seed) === "string") {
            // String seed
            this._seed = this.hashCode(seed);
        }
        else if (typeof (seed) === "number") {
            // Numeric seed
            this._seed = seed;
        }
        else {
            // Pseudo-random seed
            this._seed = Date.now() + Math.random();
        }
        this.reset();
    }
    // ================================================================================================================
    // PUBLIC INTERFACE -----------------------------------------------------------------------------------------------
    /**
     * Generates a pseudo-random number between a lower (inclusive) and a higher (exclusive) bounds.
     *
     * @param min - The minimum number that can be randomly generated.
     * @param pseudoMax - The maximum number that can be randomly generated (exclusive).
     * @return The generated pseudo-random number.
     */
    next(min = 0, pseudoMax = 1) {
        this.recalculate();
        return this.map(this._value, Random.MIN, Random.MAX, min, pseudoMax);
    }
    /**
     * Generates a pseudo-random integer number in a range (inclusive).
     *
     * @param min - The minimum number that can be randomly generated.
     * @param max - The maximum number that can be randomly generated.
     * @return The generated pseudo-random number.
     */
    nextInt(min = 10, max = 100) {
        this.recalculate();
        return Math.floor(this.map(this._value, Random.MIN, Random.MAX, min, max + 1));
    }
    /**
     * Generates a pseudo-random string sequence of a particular length from a specific character range.
     *
     * Note: keep in mind that creating a random string sequence does not guarantee uniqueness; there is always a
     * 1 in (char_length^string_length) chance of collision. For real unique string ids, always check for
     * pre-existing ids, or employ a robust GUID/UUID generator.
     *
     * @param length - Length of the strting to be generated.
     * @param chars - Characters that are used when creating the random string. Defaults to all alphanumeric chars (A-Z, a-z, 0-9).
     * @return The generated string sequence.
     */
    nextString(length = 16, chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789") {
        let str = "";
        while (str.length < length) {
            str += this.nextChar(chars);
        }
        return str;
    }
    /**
     * Generates a pseudo-random string of 1 character specific character range.
     *
     * @param chars - Characters that are used when creating the random string. Defaults to all alphanumeric chars (A-Z, a-z, 0-9).
     * @return The generated character.
     */
    nextChar(chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789") {
        this.recalculate();
        return chars.substr(this.nextInt(0, chars.length - 1), 1);
    }
    /**
     * Picks a pseudo-random item from an array. The array is left unmodified.
     *
     * Note: keep in mind that while the returned item will be random enough, picking one item from the array at a time
     * does not guarantee nor imply that a sequence of random non-repeating items will be picked. If you want to
     * *pick items in a random order* from an array, instead of *pick one random item from an array*, it's best to
     * apply a *shuffle* transformation to the array instead, then read it linearly.
     *
     * @param array - Array of any type containing one or more candidates for random picking.
     * @return An item from the array.
     */
    nextArrayItem(array) {
        this.recalculate();
        return array[this.nextInt(0, array.length - 1)];
    }
    /**
     * Generates a pseudo-random boolean.
     *
     * @return A value of true or false.
     */
    nextBoolean() {
        this.recalculate();
        return this._value > 0.5;
    }
    /**
     * Skips ahead in the sequence of numbers that are being generated. This is equivalent to
     * calling next() a specified number of times, but faster since it doesn't need to map the
     * new random numbers to a range and return it.
     *
     * @param iterations - The number of items to skip ahead.
     */
    skip(iterations = 1) {
        while (iterations-- > 0) {
            this.recalculate();
        }
    }
    /**
     * Reset the pseudo-random number sequence back to its starting seed. Further calls to next()
     * will then produce the same sequence of numbers it had produced before. This is equivalent to
     * creating a new Random instance with the same seed as another Random instance.
     *
     * Example:
     * let rng = new Random(12345678);
     * console.log(rng.next()); // 0.6177754114889017
     * console.log(rng.next()); // 0.5784605181725837
     * rng.reset();
     * console.log(rng.next()); // 0.6177754114889017 again
     * console.log(rng.next()); // 0.5784605181725837 again
     */
    reset() {
        this._value = this._seed;
    }
    // ================================================================================================================
    // PRIVATE INTERFACE ----------------------------------------------------------------------------------------------
    recalculate() {
        // Xorshift*32
        // Based on George Marsaglia's work: http://www.jstatsoft.org/v08/i14/paper
        this._value ^= this._value << 13;
        this._value ^= this._value >> 17;
        this._value ^= this._value << 5;
    }
    map(val, minFrom, maxFrom, minTo, maxTo) {
        return ((val - minFrom) / (maxFrom - minFrom)) * (maxTo - minTo) + minTo;
    }
    hashCode(str) {
        let hash = 0;
        if (str) {
            let l = str.length;
            for (let i = 0; i < l; i++) {
                hash = ((hash << 5) - hash) + str.charCodeAt(i);
                hash |= 0;
            }
        }
        return hash;
    }
}
Random.MIN = -2147483648; // Int32 min
Random.MAX = 2147483647; // Int32 max
exports.Random = Random;

},{}],13:[function(require,module,exports){
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class Vector2 {
    constructor(x, y) {
        this._x = x;
        this._y = y;
    }
    add(other) {
        return this.delta(other.x, other.y);
    }
    sub(other) {
        return this.add(other.neg());
    }
    mul(factor) {
        return new Vector2(this.x * factor, this.y * factor);
    }
    div(factor) {
        return this.mul(1 / factor);
    }
    avg(other) {
        return this.add(other).div(2);
    }
    floor(step) {
        return new Vector2(Math.floor(this.x / step) * step, Math.floor(this.y / step) * step);
    }
    neg() {
        return new Vector2(this.x, this.y);
    }
    delta(dx, dy) {
        return new Vector2(this.x + dx, this.y + dy);
    }
    get x() {
        return this._x;
    }
    get y() {
        return this._y;
    }
}
exports.Vector2 = Vector2;

},{}]},{},[2]);
