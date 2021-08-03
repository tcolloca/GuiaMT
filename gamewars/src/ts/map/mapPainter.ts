import { SuperChunk } from "./chunks";
import { Random } from "../utils/random";
import { TerrainType, Decoration, KingdomType, TileInfo } from "./landTypes";
import { TileSprite, DecorationSprite, Rectangle } from "./sprites";
import { Kingdom } from "./kingdoms";
import { WorldPosition } from "./worldPosition";
import { Simplex, PerlinNoiseProps } from "../utils/perlin";
import { Vector2 } from "../utils/vector";

export class MapPainter {

	private random: Random;
	private tileWidth: number = 16;
	private tileHeight: number = 16;

	constructor(superChunk: SuperChunk) {
		this.random = new Random(superChunk.getId());
	}

	public drawSuperChunk(game: Phaser.Game, superChunk: SuperChunk): PaintedMap {
        var tileWidth: number = this.tileWidth;
        var tileHeight: number = this.tileHeight;
        
        var bitmap: Phaser.BitmapData = new Phaser.BitmapData(game, "chunk-bitmap", 
          	superChunk.getWidth(), superChunk.getHeight());

        console.log("Got chunk");
        var terrainMap: TerrainType[][] = superChunk.buildTerrainMap(tileWidth, tileHeight);
        console.log("Build terrain");
        var kingdomMap: KingdomType[][] = superChunk.buildKingdomMap(tileWidth, tileHeight, 1, 15000, 1, 1);
        console.log("Build kingdom");

        for (var dy: number = 0; dy < terrainMap.length - 1; dy++) {
            for (var dx: number = 0; dx < terrainMap[0].length - 1; dx++) {
            	var neighs: TileInfo[] = [];
            	for (var k: number = 0; k < 4; k++) {
            		neighs[k] = {
            			kingdomType: kingdomMap[dy + (k <= 1 ? 0 : 1)][dx + (k % 3 == 0 ? 0 : 1)],
            			terrainType: terrainMap[dy + (k <= 1 ? 0 : 1)][dx + (k % 3 == 0 ? 0 : 1)] 
            		};
            	}
            	var tileBitmap: Phaser.BitmapData = this.getTileBitmap(game, neighs);
            	bitmap.draw(tileBitmap, dx * tileWidth, dy * tileHeight);
            }
        }
        console.log("Build bitmap");

        var minigames: Minigame[] = [];

        var decorationsMap: Decoration[][] = [];

        var decorationOffset = 3;
        for (var dy: number = 0; dy < terrainMap.length - decorationOffset; dy++) {
            decorationsMap.push([]);
            for (var dx: number = 0; dx < terrainMap[0].length - decorationOffset; dx++) {
                decorationsMap[dy].push(Decoration.NONE);
                if (dy > decorationOffset && dx > decorationOffset) {
                    var decorationSprites: DecorationSprite[] = Kingdom.of({kingdomType: kingdomMap[dy][dx]}).sprites.decorations;

                    for (var decorationSprite of decorationSprites) {
                        var worldPos: WorldPosition = superChunk.getWorldPosition(dx * tileWidth, dy * tileHeight);
                        var exists: boolean = this.existsDecoration(decorationSprite, worldPos);
                        // console.log(decorationSprite);
                        // console.log(exists);
                        if (!exists) {
                            continue;
                        }
                        var fits: boolean = this.fitsDecoration(decorationSprite, dx, dy, decorationsMap);
                        // console.log(fits);
                        if (!fits) {
                            continue;
                        }
                        decorationsMap[dy][dx] = Decoration.CONCH;
                        var visible: boolean = this.isVisibleDecoration(decorationSprite, dx, dy, terrainMap);
                        if (visible) {
                            // console.log(dx * tileWidth - decorationSprite.props.anchorX, 
                            //     dy * tileHeight - decorationSprite.props.anchorY - 8);
                            var posX: number = dx * tileWidth - decorationSprite.props.anchorX - 8;
                            var posY: number = dy * tileHeight - decorationSprite.props.anchorY - 8;
                            var decoBitmap: Phaser.BitmapData = decorationSprite.toBitmap(game);
                            bitmap.draw(decoBitmap, posX, posY);
                            this.fillRange(decorationSprite, dx, dy, decorationsMap);


                            if (decorationSprite.props.minigame) {
                                var rand: Random = new Random(decorationSprite.name + worldPos.toString());
                                var minigameNames: string[] = Kingdom.of({kingdomType: kingdomMap[dy][dx]}).sprites.minigames;
                                var val: number = rand.nextInt(0, minigameNames.length - 1);
                                var name: string = minigameNames[val];

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

    private fitsDecoration(decorationSprite: DecorationSprite, x: number, y: number, decorationsMap: Decoration[][]): boolean {
        if (decorationSprite.props.range == null) {
            return true;
        }
        var range: Rectangle = decorationSprite.props.range;
        return this.checkNeighs(decorationsMap, x + range.start.x, y + range.start.y, 
            range.end.x + x, range.end.y + y, Decoration.NONE);
    }

    private isVisibleDecoration(decorationSprite: DecorationSprite, x: number, y: number, terrainMap: TerrainType[][]): boolean {
        if (terrainMap[y][x] !== TerrainType.LAND) {
            return false;
        }
        if (decorationSprite.props.range == null) {
            return true;
        }
        var range: Rectangle = decorationSprite.props.range;
        return this.checkNeighs(terrainMap, x + range.start.x, y + range.start.y, 
            range.end.x + x, range.end.y + y, TerrainType.LAND);
    }

    private existsDecoration(decorationSprite: DecorationSprite, worldPosition: WorldPosition): boolean {
        var randValue: number;
        var rand: Random = new Random(decorationSprite.name + worldPosition.toString());
        if (decorationSprite.props.perlinNoiseProps != null) {
            var noiseGen: Simplex = new Simplex(rand);
            var noiseProps: PerlinNoiseProps = decorationSprite.props.perlinNoiseProps;
            randValue = noiseGen.getNoise(worldPosition, noiseProps.octaveCount, noiseProps.scale, 
                noiseProps.lacunarity, noiseProps.persistance);
        } else {
            randValue = rand.next();
        }

        var key: number;
        for (var rangeMax in decorationSprite.props.odds) {
            if (randValue < Number(rangeMax)) {
                key = Number(rangeMax);
            }
        }
        var odds: number = decorationSprite.props.odds[rangeMax];
        var newRand: number = rand.next();
        return newRand < odds; 
    }

    private fillRange(decorationSprite: DecorationSprite, x: number, y: number, map: Decoration[][]): void {
        if (decorationSprite.props.range == null) {
            return;
        }
        var range: Rectangle = decorationSprite.props.range;
        for (var dx: number = range.start.x + x; dx <= range.end.x + x; dx++) {
            for (var dy: number = range.start.y + y; dy <= range.end.y + y; dy++) {
                if (dx < 0 || dy < 0 || dx >= map.length[0] || dy >= map.length) {
                    continue;
                }
                map[dy][dx] = Decoration.CONCH;
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

    private checkNeighs<T>(map: T[][], startX: number, startY: number, 
        endX:number, endY: number, value: T): boolean {
        if (startX < 0 || startY < 0 || startX > endX 
            || startY > endY || endX >= map.length[0] || endY >= map.length) {
            return false;
        }
        for (var dx: number = startX; dx <= endX; dx++) {
            for (var dy: number = startY; dy <= endY; dy++) {
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


	private getTileBitmap(game: Phaser.Game, neighs: TileInfo[]): Phaser.BitmapData {
		var encoded: number = 0;
		for (var i: number = 0; i < 4; i++) {
			encoded += neighs[i].terrainType == TerrainType.LAND ? 1 << i : 0;
		}
		if (encoded == 0) {				
			return this.getBitmap(game, neighs, encoded, [0], [0, 0, 0, 0], null);
		} else if (encoded == 15) {			
			return this.getBitmap(game, neighs, encoded, [15], [1, 1, 1, 1], null);
		} else if ((encoded & (encoded - 1)) == 0) {	
			return this.getBitmap(game, neighs, encoded, [1, 2, 4, 8], [1, 0, 0, 0], "corners");
		} else if (encoded % 3 == 0) { 			
			return this.getBitmap(game, neighs, encoded, [9, 3, 6, 12], [1, 0, 0, 1], "sides");
		} else if (encoded % 5 == 0) { 				
			return this.getBitmap(game, neighs, encoded, [5, 10], [1, 0, 1, 0], "oppositeCorners");
		} else {										
			return this.getBitmap(game, neighs, encoded, [11, 7, 14, 13], [1, 1, 0, 1], "lShapes");
		}
	}

	private getBitmap(game: Phaser.Game, neighs: TileInfo[], encoded: number, 
			encodings: number[], lands: number[], type: string): Phaser.BitmapData {
		var rotation: number = 0; 	 

		for (var i: number = 0; i < encodings.length; i++) {
			if (encodings[i] == encoded) {
				rotation = i;
				break;
			}
		}
		var rotated: TileInfo[] = this.rotateArray(neighs, - rotation);

		var transitionType: TransitionType = TransitionType.DEFINED;

		// console.log(rotated);
		for (var i: number = 0; i < lands.length && transitionType == TransitionType.DEFINED; i++) {
			for (var j: number = 0; j < lands.length && transitionType == TransitionType.DEFINED; j++) {
				if (lands[i] == lands[j] && rotated[i].kingdomType != rotated[j].kingdomType) {
					transitionType = TransitionType.TRANSPARENCY;
				}
			}
		}
		return this.getBitmapFromRotated(game, rotated, rotation, lands, type, transitionType);
	}

	private getBitmapFromRotated(game: Phaser.Game, rotated: TileInfo[], 
		rotation: number, lands: number[], type:string, transitionType: TransitionType): Phaser.BitmapData {
		var bitmap: Phaser.BitmapData;
		var land: TileInfo = rotated[0];
		var other: TileInfo;
		for (var i: number = 0; i < lands.length; i++) {
			if (lands[i] === 0) {
				other = rotated[i];
				break;
			}
		}

		// If transition is defined, return the corresponding tile.
		if (transitionType === TransitionType.DEFINED) {
			if (other == null) { // All is land
				return Kingdom.of(land).sprites.terrain.get(land.terrainType).toBitmap(game);
			} else if (land.terrainType === other.terrainType) { // All is other
				return Kingdom.of(other).sprites.terrain.get(other.terrainType).toBitmap(game);
			}
			// Get side that transitions to the next type.
			var tiles: TileSprite[] = Kingdom.of(land).sprites[type].get(other);
			if (tiles != null && tiles[rotation] != null) {
				return tiles[rotation].toBitmap(game);
			}
		}
		// console.log(transitionType);
		// console.log(land, other);
		// Get the corresponding subblocks for the land and other terrains.
		var backgroundSubBlocks: Phaser.BitmapData[] = [];
		var landsSubBlocks: Phaser.BitmapData[] = [];

		if (type != null) {
			var transparentType: string = "transparent" + type.charAt(0).toUpperCase() + type.slice(1);
		}
		for (var i: number = 0; i < 4; i++) {
			var refTile: TileInfo = rotated[i];
			if (lands[i] === 1) {
				// Find first tile that isn't land.
				for (var j: number = 0; j < lands.length; j++) {
					if (lands[j] === 0) {
						refTile = rotated[j];
						break;
					}
				}
				if (type != null) {
					landsSubBlocks[i] = Kingdom.of(rotated[i]).sprites[transparentType][rotation]
						.subBlock(game, (i + rotation) % 4);
				} else { // All blocks are land.
					landsSubBlocks[i] = Kingdom.of(rotated[i]).sprites.terrain.get(rotated[i].terrainType)
						.subBlock(game, (i + rotation) % 4);
				}
			}
			backgroundSubBlocks[i] = Kingdom.of(refTile).sprites.terrain.get(refTile.terrainType)
				.subBlock(game, (i + rotation) % 4);
		}

		
		// Place the subchunks in a bitmap in the right position.
		var bitMap: Phaser.BitmapData = new Phaser.BitmapData(game, "tile", 16, 16);
		for (var i: number = 0; i < 4; i++) {
			var rotI: number = (i + rotation + 4) % 4;
			var x: number = rotI % 3 == 0 ? 0 : 8; 
			var y: number = rotI <= 1 ? 0 : 8;
			bitMap.draw(backgroundSubBlocks[i], x, y);
			if (lands[i] === 1) {
				bitMap.draw(landsSubBlocks[i], x, y);
			}
		}
		return bitMap;
	}

	private rotateArray<T>(arr: T[], shift: number): T[] {
		var newArr: T[] = [];
		for (var i = 0; i < 4; i++) {
			newArr[i] = arr[(i - shift + 4) % 4];
		}
		return newArr;
	} 
}

export class PaintedMap {
    private _bitmap: Phaser.BitmapData;
    private _minigames: Minigame[];

    constructor(bitmap: Phaser.BitmapData, minigames: Minigame[]) {
        this._bitmap = bitmap;
        this._minigames = minigames;
    }

    public toBackgroundSprites(game: Phaser.Game, positionX: number, positionY: number): BackgroundSprite[] {
        var backgroundSprites: BackgroundSprite[] = [];
        backgroundSprites.push({sprite: game.add.sprite(positionX + 0, positionY + 0, this.bitmap), dx: 0, dy: 0});

        // backgroundSprites[0].sprite.inputEnabled = true;
        // backgroundSprites[0].sprite.events.onInputDown.add(this.listener, this);

        this.minigames.forEach((minigame: Minigame) => {
            var sprite: Phaser.Sprite = game.add.sprite(positionX + minigame.dx, positionY + minigame.dy);
            sprite.width = minigame.width;
            sprite.height = minigame.height;
            sprite.inputEnabled = true;
            sprite.events.onInputDown.add(() => {
                window.location.href = "./games/" + minigame.name;
            });
            backgroundSprites.push({sprite: sprite, dx: minigame.dx, dy: minigame.dy});
        });

        return backgroundSprites;
    }

    get bitmap(): Phaser.BitmapData {
        return this._bitmap;
    }

    get minigames(): Minigame[] {
        return this._minigames;
    }
}

export interface Minigame {
    name: string,
    dx: number,
    dy: number,
    width: number,
    height: number
}

export interface BackgroundSprite {
    sprite: Phaser.Sprite;
    dx: number;
    dy: number;
}

export enum TransitionType {
    DEFINED,
    TRANSPARENCY
}