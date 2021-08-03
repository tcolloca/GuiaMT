export class HashMap<K, V> {

	private map: Map<string, V>;

	constructor() {
		this.map = new Map<string, V>();
	}
	
	public get(key: K): V {
		return this.map.get(this.hash(key));
	}

	public set(key: K, value: V): void {
		this.map.set(this.hash(key), value);
	}

	public hash(key: K): string {
		return JSON.stringify(key);
	}
}