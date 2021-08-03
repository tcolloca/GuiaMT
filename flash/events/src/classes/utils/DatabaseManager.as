package utils {
	
	import flash.net.*;
	import flash.events.*;
	import flash.media.*;
	import flash.utils.*;

	public class DatabaseManager {

		public static const ROOT:String = Constants.URLROOT;

		public static function hasCompleted(eventName:String, puzzleName:String, handler:Function, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'hasCompleted';
			urlVars.puzzle = puzzleName;
	
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads || handler == null) {
				return urlReq;
			} else {
				setReturningLoader(urlReq, handler);
				return null;
			}
		}

		public static function complete(eventName:String, puzzleName:String, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'complete';
			urlVars.puzzle = puzzleName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads) {
				return urlReq;
			} else {
				setLoader(setRequest(urlVars));
				return null;
			}
		}

		public static function isAvailable(eventName:String, objectName:String, handler:Function, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'isAvailable';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads || handler == null) {
				return urlReq;
			} else {
				setReturningLoader(urlReq, handler);
				return null;
			}
		}

		public static function hasObject(eventName:String, objectName:String, handler:Function, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'hasObject';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads || handler == null) {
				return urlReq;
			} else {
				setReturningLoader(urlReq, handler);
				return null;
			}
		}

		public static function usedObject(eventName:String, objectName:String, handler:Function, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'usedObject';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads || handler == null) {
				return urlReq;
			} else {
				setReturningLoader(urlReq, handler);
				return null;
			}
		}

		public static function releaseObject(eventName:String, objectName:String, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'releaseObject';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads) {
				return urlReq;
			} else {
				setLoader(setRequest(urlVars));
				return null;
			}
		}

		public static function getObject(eventName:String, objectName:String, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'getObject';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads) {
				return urlReq;
			} else {
				setLoader(setRequest(urlVars));
				return null;
			}
		}

		public static function useObject(eventName:String, objectName:String, loads:Boolean = true):URLRequest {

			var urlVars:URLVariables = new URLVariables();
			urlVars.username = 'tomatereloco'; //TODO: Remove
			urlVars.eventName = eventName;
			urlVars.functionCalled = 'useObject';
			urlVars.object = objectName;
			
			var urlReq:URLRequest = setRequest(urlVars);

			if(!loads) {
				return urlReq;
			} else {
				setLoader(setRequest(urlVars));
				return null;
			}
		}

		private static function setRequest(urlVars:URLVariables):URLRequest {

			var urlReq:URLRequest = new URLRequest(ROOT+"/index.php");
			urlReq.method = URLRequestMethod.POST;
			urlReq.data = urlVars; 
			return urlReq;
		}

		private static function setReturningLoader(urlReq:URLRequest, handler:Function):void {

			var urlLoader:URLLoader = new URLLoader();
			urlLoader.dataFormat = URLLoaderDataFormat.VARIABLES;
			urlLoader.addEventListener(Event.COMPLETE, function(e:Event):void {

															handler(urlLoader);
														}); 
			urlLoader.load(urlReq);
		}

		private static function setLoader(urlReq:URLRequest):void {

			var urlLoader:URLLoader = new URLLoader();
			urlLoader.load(urlReq);
		}
	}

	//TODO: (REMOVE) GETTING DATA: urlLoader.data.mail
}
