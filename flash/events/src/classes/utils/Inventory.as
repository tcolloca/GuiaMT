package utils {

	import items.*;
	import flash.utils.*;
	import flash.display.*;

	public class Inventory {

		private var inv:Array = new Array();
		private var _size:Number = 0;

		public function addItem(name:String):Number {

			for(var i:Number = 0; i < _size; i++) {
				if(!inv[i]) {
					inv[i] = name;
					return i;
				}
			}

			inv[_size] = name;
			return _size++;
		}

		public function removeItem(name:String):Boolean {

			for(var i in inv) {
				
				if(inv[i] == name) {
					inv[i] = null;
					if(i + 1 == _size)
						updateSize();
					return true;
				}
			}

			return false;
		}

		private function updateSize():void {

			var last:Number = 0;

			for(var i in inv) {

				if(inv[i] != null) {
					last = i;
				}
			}

			_size = last +1;
		}

		public function getItem(i:Number):String {

			return inv[i];
		}

		public function hasItem(name:String):Boolean {

			for(var i in inv) {
				if(inv[i] == name) {
					return true;
				}
			}

			return false;
		}

		public function display(parent:DisplayObjectContainer):void {

			for(var i in inv) {
				parent.addChild(Factory.get(inv[i]));
			}
		}

		public function get size():Number {

			return _size;
		}
	}
}
