package puzzles.easter2015.trash {
	
	import br.com.stimuli.loading.*;
	import flash.display.*;
	import flash.net.*;
	import flash.events.*;
	import flash.media.*;
	import flash.utils.*;
	import flash.system.*;
	import utils.drag.*;
	import popups.*;
	import puzzles.*;
	import utils.*;
	import items.*;

	public class Trash extends Container implements ExternalSWF {

		private static const ROOT:String = Constants.ROOT; 
		private static const TESTING:Boolean = Constants.TESTING;
		public static const ITEMS_AMOUNT:Number = 10;
		private var loader:BulkLoader;
		
		private var container:DragContainer;
		private var popUp:PopUp;
		private var specialItem:Item;
		private var itZ:Number;

		public function Trash() {

			if(TESTING) {
				addLoader(new BulkLoader("trash-loader"));
				loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);
				loadTrash();
				loader.start();
			}
		}

		public function loadTrash():void {

			loader.add(DatabaseManager.isAvailable("easter2015", "clue", null, false), {id:"isAvailable_clue"});
			loader.add(ROOT+"/images/puzzles/easter2015/trash/trash.swf", {id:"trash_background"});

			for(var i:Number = 0; i < ITEMS_AMOUNT/2; i++) {

				loader.add(ROOT+"/images/puzzles/easter2015/trash/trash_paper.swf", {id:"trash_paper"+i});
				loader.add(ROOT+"/images/puzzles/easter2015/trash/trash_old.swf", {id:"trash_old"+i});
				loader.add(ROOT+"/images/puzzles/easter2015/trash/trash_bottle.swf", {id:"trash_bottle"+i});
			}

			loader.add(ROOT+"/images/objects/general/clue.swf", {id:"clue_obj"});
			loader.add(ROOT+"/images/clues/easter2015/test_clue.swf", {id:"clue"});
		}

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}
		
		override public function addItem(specialItem:Item, x:Number = 0, y:Number = 0, z:Number = 0):void {

			this.specialItem = specialItem;
			specialItem.x = x;
			specialItem.y = y;
			this.itZ = z;

			super.addItem(specialItem);
		}

		public function finishedLoading(e:BulkProgressEvent):void {

			Factory.setLoader(loader);
			initializeBackground();
			initializeContent();
		}

		private function initializeBackground():void {
			
			container = DragContainer(Factory.get("trash_background"));
			addChildAt(container, 0);
		}
		
		private function initializeContent():void {	
	
			for(var i:Number = 0; i < ITEMS_AMOUNT; i++) {	

				if(specialItem != null && i == itZ) {
					container.add(specialItem);
				} else {
					var obj:Draggable;
					var objType:Number = Math.random();
					
					if(objType < 0.33) {
						obj = Factory.get("trash_old") as Draggable;
					} else if(objType < 0.67) {
						obj = Factory.get("trash_bottle") as Draggable;
					} else {
						obj = Factory.get("trash_paper") as Draggable;
					}

					
					obj.setContainer(container);
					obj.setLimits(container.width/2 - 25);
					setRandomPosition(container, obj);
					container.add(obj);
				}
			}
		}

		private function setRandomPosition(container:MovieClip, o:MovieClip):void {

			var radius:Number = (container.width - 70)/2;
			var arg:Number = Math.random()*360;
			var maxX:Number = Math.cos(arg)*radius - Math.cos(arg)*o.width/2;
			var maxY:Number = Math.sin(arg)*radius - Math.sin(arg)*o.height/2;

			o.x = Math.random()*maxX;
			o.y = Math.random()*maxY; 
			o.scaleX *= randomSign();
			o.scaleY *= randomSign();
		}

		private function randomSign():Number {

			return Math.random() < 0.5 ? -1 : +1;
		}
	}
}
