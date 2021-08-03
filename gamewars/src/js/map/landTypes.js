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
