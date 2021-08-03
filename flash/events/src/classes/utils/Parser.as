package utils {
	
	public class Parser {

		public static function parse(str:String):Object {
			
			var cArr:Array = str.split('&');
			var cObj:Object = {};
			var cLen:Number = cArr.length;
			
			for(var cnt = 0; cnt < cLen; cnt++) {

				var cStr:String = new String(cArr[cnt]);
				var cElem:Array = cStr.split('=');
				cObj[trim(cElem[0])] = trim(cElem[1]);
			}
	 
			return cObj;
		}

		 private static function trim(str:String):String {

		 	return str.replace(/(\t|\r|\n|\f| )/g, "");
		 }
	}
}

