export enum TerrainType {
    LAND,
    ROAD,
    WATER,
    TOTAL
}

export enum KingdomType {
	BEACH,
	SNOW,
	TOTAL
}

export enum Decoration {
    NONE,
    SHELL,
    CONCH, 
    PALM_TREE
}

export class TileInfo {
	kingdomType: KingdomType;
	terrainType: TerrainType;
}

