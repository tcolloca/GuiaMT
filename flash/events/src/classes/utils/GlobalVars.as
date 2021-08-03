package utils {

	import flash.display.*;
	import flash.utils.*;

	public class GlobalVars {

		public static var inventory:Inventory = new Inventory();
		public static var isBlocked:Boolean = false; 

		public static var dragged:MovieClip;
		public static var startX:Number;
		public static var startY:Number;

		public static var itemsMap:Dictionary = new Dictionary();
	}
	
}
