export class Vector2 {
    private _x: number;
    private _y: number;

    constructor(x: number, y: number) {
        this._x = x;
        this._y = y;
    }

    public add(other: Vector2): Vector2 {
        return this.delta(other.x, other.y);
    }

    public sub(other: Vector2): Vector2 {
        return this.add(other.neg());
    }

    public mul(factor: number): Vector2 {
        return new Vector2(this.x * factor, this.y * factor);
    }

    public div(factor: number): Vector2 {
        return this.mul(1 / factor);
    }

    public avg(other: Vector2): Vector2 {
        return this.add(other).div(2);
    }

    public floor(step: number): Vector2 {
        return new Vector2(Math.floor(this.x / step) * step, 
            Math.floor(this.y / step) * step);
    }

    public neg(): Vector2 {
        return new Vector2(this.x, this.y);
    }

    public delta(dx: number, dy: number): Vector2 {
        return new Vector2(this.x + dx, this.y + dy);
    }

    get x(): number {
        return this._x;
    }

    get y(): number {
        return this._y;
    }
}