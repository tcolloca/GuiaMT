package puzzles.easter2015.shake {

	public class FruitPack {

		private var _quantity:int;
		private var _fruit:Fruit;

		public function FruitPack(_fruit:Fruit, _quantity:int) {

			this._quantity = _quantity;
			this._fruit = _fruit;
		}

		public function get quantity():int {

			return _quantity;
		}

		public function get fruit():Fruit {

			return _fruit;
		}
	}
}