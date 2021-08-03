//// <reference path="../utils/jimp.d.ts" />

import { GoogleMapsApi } from "./googleMapsApi";
import { Vector2 } from "../utils/vector";
import { MapTile } from "./mapTile";
import { WorldPosition } from "./worldPosition";
import { Chunk, SuperChunk } from "./chunks";
import { Decoration } from "./landTypes";
import { MapPainter, PaintedMap, Minigame, BackgroundSprite } from "./mapPainter";
import { createWorker, ITypedWorker } from "../utils/typed-workers";


export class GameMap {

    private gameHeight: number = Math.min(window.innerHeight, 400);
    private gameWidth: number = window.innerWidth / window.innerHeight * this.gameHeight;

    private game: Phaser.Game = new Phaser.Game(this.gameWidth, this.gameHeight, Phaser.AUTO, '', 
        { preload: this.preload.bind(this), create: this.create.bind(this), update: this.update.bind(this) });
  
    private superChunkMemory: number = 3;
    private midMemory: number = Math.floor(this.superChunkMemory / 2);
    private superChunkSize: number = 3;
    // private maxDist: number = window.innerWidth / 2 + 50;
    private maxDist: number = this.gameWidth / 2 + 50;
    private speed: number = 0.000025;
    private zoom: number = 18;
    private tileWidth: number = 16;
    private tileHeight: number = 16;
    private extraOffset: number = GoogleMapsApi.EXTRA_OFFSET;

    // private lat: number = 50;
    // private lng: number = 30;
    private lat: number = -34.491126;
    private lng: number = -58.493072;
   

    private mapsApi: GoogleMapsApi;
    private cursors: Phaser.CursorKeys;
    private player: Phaser.Sprite;

    private background: BackgroundSprite[][][];
    private superChunks: SuperChunk[][];

    private prevLat: number = null;
    private prevLng: number = null;
    private firstTime: boolean = true;
    private firstSuperChunk: boolean = true;

    constructor() {
        this.background = [];
        this.superChunks = [];
        for (var i: number = 0; i < this.superChunkMemory; i++) {
            this.background[i] = [];
            this.superChunks[i] = [];
            for (var j: number = 0; j < this.superChunkMemory; j++) {
                this.background[i][j] = null;
                this.superChunks[i][j] = null;
            }
        }
    }

    private updateCoords(position: Position) {
        this.lat = position.coords.latitude;
        this.lng = position.coords.longitude;

        if (this.firstSuperChunk) {
            this.showPosition(position);
            this.firstSuperChunk = false;
        }
    }

    private showPosition(position: Position) {
        // this.lat = position.coords.latitude;
        // this.lng = position.coords.longitude;

        this.getSuperChunk().then((superChunk: SuperChunk) => {
            var map: PaintedMap = new MapPainter(superChunk).drawSuperChunk(this.game, superChunk);
            this.background[this.midMemory][this.midMemory] = map.toBackgroundSprites(this.game, 0, 0);
            this.superChunks[this.midMemory][this.midMemory] = superChunk;

            this.player = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY, 'player');
            this.player.animations.add('up', [4, 3, 2, 1].map(n => 'up_' + n + '.png'), 10, true);
            this.player.animations.add('down', [4, 3, 2, 1].map(n => 'down_' + n + '.png'), 10, true);
            this.player.animations.add('left', [4, 3, 2, 1].map(n => 'left_' + n + '.png'), 10, true);
            this.player.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
        });
    }

    public preload(): void {
        this.game.scale.scaleMode = Phaser.ScaleManager.EXACT_FIT;
        this.game.scale.refresh();
        // this.game.scale.setupScale(window.innerWidth, window.innerHeight);
        // this.game.scale.refresh();
        this.game.load.atlasJSONHash('map-tiles', 'assets/map-tiles.png', 'assets/map-tiles.json');
        this.game.load.atlasJSONHash('player', 'assets/player.png', 'assets/player.json');
        this.mapsApi = new GoogleMapsApi();
        this.cursors = this.game.input.keyboard.createCursorKeys();
    }

    public create(): void {
        var game: Phaser.Game = this.game;
        // navigator.geolocation.getCurrentPosition(this.updateCoords.bind(this));
        this.showPosition(null);
        // Jimp.read("./assets/path.png");
    }

    public update(): void {
        var speed: number = this.speed;
        var cursors: Phaser.CursorKeys = this.cursors;
        var lat: number = this.lat;
        var lng: number = this.lng;

        if (this.player == null) {
            return;
        } else if (this.firstTime) {
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

            var superChunkIndex: Vector2 = this.findContainingSuperChunk(lat, lng);

            this.shiftSuperChunks(superChunkIndex);

            this.updateBackground();
    	}
    }

    private playAnimation(lat: number, lng: number): void {
        if (lat < this.prevLat) {
            this.player.animations.play('down');
        } else if (lat > this.prevLat) {
            this.player.animations.play('up');
        } else if (lng < this.prevLng) {
            this.player.animations.play('left');
        } else if (lng > this.prevLng) {
            this.player.animations.play('right');
        }
    }

    private updateBackground(): void {
        var superChunk: SuperChunk = this.superChunks[this.midMemory][this.midMemory];
        var offset: Vector2 = superChunk.getOffset(this.lat, this.lng);

        for (var dx: number = 0; dx < this.superChunkMemory; dx++) {
            for (var dy: number = 0; dy < this.superChunkMemory; dy++) {
                var chunkSprites: BackgroundSprite[] = this.background[dy][dx];
                if (chunkSprites != null) {
                    chunkSprites.forEach((chunkSprite: BackgroundSprite) => {
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

    private getSuperChunk(deltaX: number = 0, deltaY: number = 0): Promise<SuperChunk> {
        var chunkPromises: Promise<Chunk>[][] = [];
        console.log(this.lat, this.lng);
        var centerMapTile: MapTile = new WorldPosition(this.lat, this.lng).toMapTile(this.zoom)
            .floor(this.superChunkSize)
            .delta(deltaX * this.superChunkSize, deltaY * this.superChunkSize);
        for (var dy: number = 0; dy < this.superChunkSize; dy++) {
            chunkPromises.push([]);
            for (var dx: number = 0; dx < this.superChunkSize; dx++) {
                var chunkMapTile: MapTile = centerMapTile.delta(dx, dy);
                chunkPromises[dy].push(this.mapsApi.getChunk(chunkMapTile));
            }
        }

        return Promise.all(chunkPromises.map(row => Promise.all(row)))
            .then((chunks: Chunk[][]) => new SuperChunk(chunks));
    }

    private findContainingSuperChunk(lat: number, lng: number): Vector2 {
        for (var dx: number = 0; dx < this.superChunks[0].length; dx++) {
            for (var dy: number = 0; dy < this.superChunks.length; dy++) {
                if (this.superChunks[dy][dx] != null) {
                    if (this.superChunks[dy][dx].containsWorldPosition(lat, lng)) {
                        return new Vector2(dx, dy);
                    }
                }
            }
        }
    }

    private shiftSuperChunks(superChunkIndex: Vector2): void {
        if (superChunkIndex.x < this.midMemory) {
            this.shiftLeft();
        } else if (superChunkIndex.x > this.midMemory) {
            this.shiftRight();
        }
        if (superChunkIndex.y < this.midMemory) {
            this.shiftUp();
        } else if (superChunkIndex.y > this.midMemory) {
            this.shiftDown();
        }
    }

    private shiftLeft(): void {
        for (var dy: number = 0; dy < this.superChunkMemory; dy++) {
            for (var dx: number = this.superChunkMemory - 1; dx > 0; dx--) {
                this.background[dy][dx] = this.background[dy][dx - 1];
                this.superChunks[dy][dx] = this.superChunks[dy][dx - 1];
            }
            this.background[dy][0] = null;
            this.superChunks[dy][0] = null;
        }
    }

    private shiftRight(): void {
        for (var dy: number = 0; dy < this.superChunkMemory; dy++) {
            for (var dx: number = 0; dx < this.superChunkMemory - 1; dx++) {
                this.background[dy][dx] = this.background[dy][dx + 1];
                this.superChunks[dy][dx] = this.superChunks[dy][dx + 1];
            }
            this.background[dy][this.superChunkMemory - 1] = null;
            this.superChunks[dy][this.superChunkMemory - 1] = null;
        }
    }

    private shiftUp(): void {
        for (var dx: number = 0; dx < this.superChunkMemory; dx++) {
            for (var dy: number = this.superChunkMemory - 1; dy > 0; dy--) {
                this.background[dy][dx] = this.background[dy - 1][dx];
                this.superChunks[dy][dx] = this.superChunks[dy - 1][dx];
            }
            this.background[0][dx] = null;
            this.superChunks[0][dx] = null;
        }
    }

    private shiftDown(): void {
        for (var dx: number = 0; dx < this.superChunkMemory; dx++) {
            for (var dy: number = 0; dy < this.superChunkMemory - 1; dy++) {
                this.background[dy][dx] = this.background[dy + 1][dx];
                this.superChunks[dy][dx] = this.superChunks[dy + 1][dx];
            }
            this.background[this.superChunkMemory - 1][dx] = null;
            this.superChunks[this.superChunkMemory - 1][dx] = null;
        }
    }

    private loadNeighSuperChunks(offset: Vector2): void {
        var superChunk: SuperChunk = this.superChunks[this.midMemory][this.midMemory];
        
        var deltaX: number = 0;
        var deltaY: number = 0;
        if (offset.x < this.maxDist) {
            deltaX = -1;
        } else if (offset.x > superChunk.getWidth() - this.maxDist) {
            deltaX = 1;
        } 

        if (offset.y < this.maxDist) {
            deltaY = -1;
        } else if (offset.y > superChunk.getHeight() - this.maxDist) {
            deltaY = 1;
        }
        
        if (deltaX === -1 && this.background[this.midMemory][this.midMemory - 1] === null) {
            this.loadChunk(offset, new Vector2(deltaX, 0));
            if (deltaY != 0) {
                this.loadChunk(offset, new Vector2(deltaX, deltaY));
            }
        } else if (deltaX === 1 && this.background[this.midMemory][this.midMemory + 1] === null) {
            this.loadChunk(offset, new Vector2(deltaX, 0));
            if (deltaY != 0) {
                this.loadChunk(offset, new Vector2(deltaX, deltaY));
            }
        }

        if (deltaY === -1 && this.background[this.midMemory - 1][this.midMemory] === null) {
            this.loadChunk(offset, new Vector2(0, deltaY));
            if (deltaX != 0) {
                this.loadChunk(offset, new Vector2(deltaX, deltaY));
            }
        } else if (deltaY === 1 && this.background[this.midMemory + 1][this.midMemory] === null) {
           this.loadChunk(offset, new Vector2(0, deltaY));
            if (deltaX != 0) {
                this.loadChunk(offset, new Vector2(deltaX, deltaY));
            }
        }
    }

    private loadChunk(offset: Vector2, delta: Vector2): void {
        console.log("Load...");

        var data: SuperChunkLoaderData = {
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
        }

        this.background[this.midMemory + delta.y][this.midMemory + delta.x] = undefined;
        
        // var worker: ITypedWorker<SuperChunkLoaderData, void> = createWorker((data: SuperChunkLoaderData) => {
            data.getSuperChunk.bind(data)(delta.x, delta.y).then((superChunk: SuperChunk) => {
                var map: PaintedMap = new MapPainter(superChunk).drawSuperChunk(data.game, superChunk);
                var bitmap: Phaser.BitmapData = map.bitmap;
                
                var positionX: number = (bitmap.width - data.extraOffset) * delta.x 
                    - offset.x + data.game.world.width / 2;
                var positionY: number = (bitmap.height - data.extraOffset) * delta.y 
                    - offset.y + data.game.world.height / 2;

                var backgroundSprites: BackgroundSprite[] = map.toBackgroundSprites(data.game, positionX, positionY);

                data.background[data.midMemory + delta.y][data.midMemory + delta.x] = backgroundSprites;
                data.superChunks[data.midMemory + delta.y][data.midMemory + delta.x] = superChunk;
            });
        // };

        // worker.postMessage(data);
    }
}

export interface SuperChunkLoaderData {
    delta: Vector2;
    lat: number;
    lng: number;
    zoom: number;
    superChunkSize: number;
    mapsApi: GoogleMapsApi;
    game: Phaser.Game;
    midMemory: number;
    extraOffset: number;
    background: BackgroundSprite[][][];
    superChunks: SuperChunk[][];
    getSuperChunk: (x: number, y: number) => Promise<SuperChunk>;
}

new GameMap();
