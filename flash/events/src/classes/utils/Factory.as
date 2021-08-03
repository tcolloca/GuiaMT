package utils {
	
	import br.com.stimuli.loading.*;
	import flash.display.*;
	import utils.*;

	public class Factory {

		private static var map:Array = new Array();
		private static var loader:BulkLoader;
		
		public static function setLoader(_loader:BulkLoader) {

			loader = _loader;
		}

		public static function get(objName:String):DisplayObject {

			if(map[objName] == null) {
				map[objName] = 0;
			}

			if(loader.getContent(objName + map[objName]) == null && map[objName] == 0) {
				return loader.getContent(objName);
			}

			if(loader.getContent(objName + map[objName]) == null) {
				return loader.getContent(objName + 1);
			}
			return loader.getContent(objName + map[objName]++);
		}
	}
}
