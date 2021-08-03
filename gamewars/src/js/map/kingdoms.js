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
