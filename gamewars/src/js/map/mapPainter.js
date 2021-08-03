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
