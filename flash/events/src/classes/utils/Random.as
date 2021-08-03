package utils {

	public class Random {

		public static function randomIntRange(min:int, max:int):int {

			return (Math.floor(Math.random() * (max - min + 1)) + min);
		}

		public static function randomDoubleRange(min:int, max:int):Number {

			return (Math.random() * (max - min + 1) + min);
		}
	}
}
