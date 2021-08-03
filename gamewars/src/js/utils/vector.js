"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class Vector2 {
    constructor(x, y) {
        this._x = x;
        this._y = y;
    }
    add(other) {
        return this.delta(other.x, other.y);
    }
    sub(other) {
        return this.add(other.neg());
    }
    mul(factor) {
        return new Vector2(this.x * factor, this.y * factor);
    }
    div(factor) {
        return this.mul(1 / factor);
    }
    avg(other) {
        return this.add(other).div(2);
    }
    floor(step) {
        return new Vector2(Math.floor(this.x / step) * step, Math.floor(this.y / step) * step);
    }
    neg() {
        return new Vector2(this.x, this.y);
    }
    delta(dx, dy) {
        return new Vector2(this.x + dx, this.y + dy);
    }
    get x() {
        return this._x;
    }
    get y() {
        return this._y;
    }
}
exports.Vector2 = Vector2;
