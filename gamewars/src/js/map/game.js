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
