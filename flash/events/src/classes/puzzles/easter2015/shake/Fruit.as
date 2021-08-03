package puzzles.easter2015.shake {

	public class Fruit {

		private var _name:String;
		private var _points:int;
		private var _cals:int;
		private var _vits:int;
		private var _time:int;
		private var _desc:Array;

		public function Fruit(_name:String, _points:int, _vits:int, _cals:int, _time:int, _desc:Array) {

			this._name = _name;
			this._points = _points;
			this._cals = _cals;
			this._vits = _vits;
			this._time = _time;
			this._desc = _desc;
		}

		public function get name():String {

			return _name;
		}

		public function get points():int {

			return _points;
		}

		public function get vits():int {

			return _vits;
		}

		public function get cals():int {

			return _cals;
		}

		public function get time():int {

			return _time;
		}

		public function get desc():Array {

			return _desc;
		}
	}
}