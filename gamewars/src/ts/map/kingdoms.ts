import { HashMap } from "../utils/hashmap";
import { TileSprite, DecorationSprite } from "./sprites";
import { TileInfo, KingdomType, TerrainType } from "./landTypes";
import { Vector2 } from "../utils/vector";


export interface KingdomSprites {

	minigames: string[];

	terrain: HashMap<TerrainType, TileSprite>;
	
	corners: HashMap<TileInfo, TileSprite[]>;
	transparentCorners: TileSprite[];
	
	sides: HashMap<TileInfo, TileSprite[]>;
	transparentSides: TileSprite[];

	oppositeCorners: HashMap<TileInfo, TileSprite[]>;
	transparentOppositeCorners: TileSprite[];

	lShapes: HashMap<TileInfo, TileSprite[]>;
	transparentLShapes: TileSprite[];

	decorations: DecorationSprite[];
}

export class Kingdom {

	private static kingdomMap: HashMap<KingdomType, Kingdom>;

	private _sprites: KingdomSprites;

	static initialize() {
		this.kingdomMap = new HashMap<KingdomType, Kingdom>();
		this.kingdomMap.set(KingdomType.SNOW, SnowKingdom.instance);
		this.kingdomMap.set(KingdomType.BEACH, BeachKingdom.instance);
	}

	static of(info: {kingdomType: KingdomType}): Kingdom {
		return this.kingdomMap.get(info.kingdomType);
	}

	constructor(sprites: KingdomSprites) {
		this._sprites = sprites;
	}

	get sprites(): KingdomSprites {
		return this._sprites;
	}
}

export class SnowKingdom extends Kingdom {

	public static _instance: SnowKingdom;

	static get instance(): SnowKingdom {
		if (this._instance == null) {
			this._instance = new SnowKingdom();
		}
		return this._instance;
	}

	private constructor() {
		var terrainMap: HashMap<TerrainType, TileSprite> = new HashMap<TerrainType, TileSprite>();
		terrainMap.set(TerrainType.LAND, new TileSprite("snowy_land"));
		terrainMap.set(TerrainType.ROAD, new TileSprite("snowy_path5"));
		terrainMap.set(TerrainType.WATER, new TileSprite("snowy_path5"));

		var corners: TileSprite[] = ["snowy_path5", "snowy_path5", "snowy_path5", "snowy_path5"].map(
			name => new TileSprite(name));

		var sides: TileSprite[] = ["snowy_path4", "snowy_path2", "snowy_path6", "snowy_path8"].map(
			name => new TileSprite(name));

		var oppositeCorners: TileSprite[] = ["snowy_path5", "snowy_path5"].map(
			name => new TileSprite(name));

		var lShapes: TileSprite[] = ["snowy_path1", "snowy_path3", "snowy_path9", "snowy_path7"].map(
			name => new TileSprite(name));

		var cornersMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		cornersMap.set({kingdomType: KingdomType.SNOW, terrainType: TerrainType.ROAD}, corners);

		var sidesMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		sidesMap.set({kingdomType: KingdomType.SNOW, terrainType: TerrainType.ROAD}, sides);

		var oppositeCornersMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		oppositeCornersMap.set({kingdomType: KingdomType.SNOW, terrainType: TerrainType.ROAD}, oppositeCorners);

		var lShapesMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		lShapesMap.set({kingdomType: KingdomType.SNOW, terrainType: TerrainType.ROAD}, lShapes);

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
				new DecorationSprite("pipe", {
					minigame: true,
					anchorX: 24 - 12 + 16, 
					anchorY: 38 - 28 + 16,
					hFlipable: true,
					range:  {
						start: new Vector2(-2, -3),
						end: new Vector2(-1, 0)
					},
					odds: {
						1: 0.005 * 3,
					}
				}),
				new DecorationSprite("snowy_tree", {
					anchorX: 40 - 18 + 16, 
					anchorY: 44 - 28 + 16,
					hFlipable: true,
					range:  {
						start: new Vector2(-2, -2),
						end: new Vector2(0, 0)
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
				new DecorationSprite("small_cut_tree", {
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
				new DecorationSprite("snowy_small_plant", {
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
				new DecorationSprite("snowy_plant", {
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

// TODO: Update sprites.

export class BeachKingdom extends Kingdom {

	public static _instance: BeachKingdom;

	static get instance(): BeachKingdom {
		if (this._instance == null) {
			this._instance = new BeachKingdom();
		}
		return this._instance;
	}

	private constructor() {
		var terrainMap: HashMap<TerrainType, TileSprite> = new HashMap<TerrainType, TileSprite>();
		terrainMap.set(TerrainType.LAND, new TileSprite("beach_land"));
		terrainMap.set(TerrainType.ROAD, new TileSprite("beach_pond5"));
		terrainMap.set(TerrainType.WATER, new TileSprite("beach_pond5"));

		var corners: TileSprite[] = ["beach_pond10", "beach_pond11", "beach_pond13", "beach_pond12"].map(
			name => new TileSprite(name));

		var sides: TileSprite[] = ["beach_pond4", "beach_pond2", "beach_pond6", "beach_pond8"].map(
			name => new TileSprite(name));

		var oppositeCorners: TileSprite[] = ["beach_pond14", "beach_pond14"].map(
			name => new TileSprite(name));

		var lShapes: TileSprite[] = ["beach_pond1", "beach_pond3", "beach_pond9", "beach_pond7"].map(
			name => new TileSprite(name));

		var cornersMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		cornersMap.set({kingdomType: KingdomType.BEACH, terrainType: TerrainType.ROAD}, corners);

		var sidesMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		sidesMap.set({kingdomType: KingdomType.BEACH, terrainType: TerrainType.ROAD}, sides);

		var oppositeCornersMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		oppositeCornersMap.set({kingdomType: KingdomType.BEACH, terrainType: TerrainType.ROAD}, oppositeCorners);

		var lShapesMap: HashMap<TileInfo, TileSprite[]> = new HashMap<TileInfo, TileSprite[]>();
		lShapesMap.set({kingdomType: KingdomType.BEACH, terrainType: TerrainType.ROAD}, lShapes);

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
				new DecorationSprite("pipe", {
					minigame: true,
					anchorX: 24 - 12 + 16, 
					anchorY: 38 - 28 + 16,
					hFlipable: true,
					range:  {
						start: new Vector2(-2, -3),
						end: new Vector2(-1, 0)
					},
					odds: {
						1: 0.02 * 3,
					}
				}),
				new DecorationSprite("palm_tree", {
					anchorX: 36 - 18 + 16, 
					anchorY: 44 - 28 + 16,
					hFlipable: true,
					range:  {
						start: new Vector2(-2, 0),
						end: new Vector2(-1, 0)
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
				new DecorationSprite("conch", {
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
				new DecorationSprite("shell", {
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

Kingdom.initialize();