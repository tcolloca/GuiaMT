import { Random } from "../utils/random";
import { Vector2 } from "../utils/vector";
import { PerlinNoiseProps } from "../utils/perlin";

export class TileSprite {

    private name: string;
    private sprite: Phaser.Sprite;

    constructor(name: string) {
        this.name = name;
    }

    public toBitmap(game: Phaser.Game): Phaser.BitmapData {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap: Phaser.BitmapData = new Phaser.BitmapData(game, this.name, 16, 16);
           bitmap.draw(this.sprite);
           return bitmap;
    }

    public subBlock(game: Phaser.Game, index: number): Phaser.BitmapData {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap: Phaser.BitmapData = new Phaser.BitmapData(game, this.name, 8, 8);
        var x: number = index % 3 == 0 ? 0 : 8; 
        var y: number = index <= 1 ? 0 : 8;
           bitmap.copyRect(this.sprite, new Phaser.Rectangle(x, y, 8, 8), 0, 0);
           return bitmap;
    }
}

export interface DecorationProperties {
	
    minigame?: boolean;
	anchorX?: number;
	anchorY?: number;
	range?: Rectangle;
	hFlipable?: boolean;
	rotations?: number[];
	perlinNoiseProps?: PerlinNoiseProps;
	odds: {[rangeMax: number]: number};
}

export class DecorationSprite {
    
    private _name: string;
    private _props: DecorationProperties;
    private sprite: Phaser.Sprite;

    constructor(name: string, props: DecorationProperties) {
        this._name = name;
        this._props = props;
        if (this._props.anchorX == null) {
        	this._props.anchorX = 0;
        }
        if (this._props.anchorY == null) {
        	this._props.anchorY = 0;
        }
    }

    public toBitmap(game: Phaser.Game): Phaser.BitmapData {
        if (this.sprite == null) {
            this.sprite = game.make.sprite(0, 0, 'map-tiles', this.name + '.png');
        }
        var bitmap: Phaser.BitmapData = new Phaser.BitmapData(game, this.name, this.sprite.width, this.sprite.height);
        var flip: number = 1;
        // var flip: number = this.props.hFlipable && Math.random() < 0.5 ? -1 : 1;
        var rotate: number = 0;
        if (this.props.rotations != null) {
        	rotate = this.props.rotations[new Random().nextInt(0, this.props.rotations.length - 1)];
        }
        return bitmap.copy(this.sprite, 0, 0, null, null, null, null, null, null, rotate, null, null, flip);
    }

    get name(): string {
    	return this._name;
    }

    get props(): DecorationProperties {
    	return this._props;
    }
}

export interface Rectangle {

	start: Vector2;
	end: Vector2;
}