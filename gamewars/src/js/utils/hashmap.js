"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class HashMap {
    constructor() {
        this.map = new Map();
    }
    get(key) {
        return this.map.get(this.hash(key));
    }
    set(key, value) {
        this.map.set(this.hash(key), value);
    }
    hash(key) {
        return JSON.stringify(key);
    }
}
exports.HashMap = HashMap;
