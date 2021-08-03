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
