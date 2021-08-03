package puzzles.easter2015.map {
	
	import br.com.stimuli.loading.*;
	import flash.display.*;
	import flash.net.*;
	import flash.events.*;
	import flash.media.*;
	import flash.utils.*;
	import flash.system.*;
	import utils.*;
	import popups.*;
	import puzzles.*;

	public class Map extends PopUp implements Puzzle, ExternalSWF {

		public static const ROOT:String = Constants.ROOT; 
		public static const TESTING:Boolean = Constants.TESTING;
		private var loader:BulkLoader;

		private var amount:Number = 0;
		private var fields:Vector.<MovieClip> = new Vector.<MovieClip>();
		private var swords:Vector.<MovieClip> = new Vector.<MovieClip>();

		public function Map() {

			if(TESTING) {
				addLoader(new BulkLoader("map-loader"));
				loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);
				loadMap();
				loader.start();
			}
		}

		public function loadMap():void {

			loader.add(DatabaseManager.hasCompleted("easter2015", "map", null, false), {id:"hasCompleted_map"});

			loader.add(ROOT+"/images/puzzles/easter2015/map/map.swf", {id:"map_background"});

			for(var i:Number = 0; i < 64; i++) {

				loader.add(ROOT+"/images/puzzles/easter2015/map/field.swf", {id:"field"+i});
			}

			for(i = 0; i < 6; i++) {

				loader.add(ROOT+"/images/puzzles/easter2015/map/lake.swf", {id:"lake"+i});
				loader.add(ROOT+"/images/puzzles/easter2015/map/sword.swf", {id:"sword"+i});
			}

			loader.add(ROOT+"/images/puzzles/easter2015/map/blue_castle.swf", {id:"blue_castle"});
			loader.add(ROOT+"/images/puzzles/easter2015/map/red_castle.swf", {id:"red_castle"});
			loader.add(ROOT+"/images/puzzles/easter2015/map/attack_btn.swf", {id:"attack_btn"});
		}

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}
		
		public function finishedLoading(e:BulkProgressEvent):void {

			Factory.setLoader(loader);
			initializeBackground();
			initializeContent();
		}

		private function initializeBackground():void {
			
			addChildAt(loader.getContent("map_background"), 0);
		}
		
		private function initializeContent():void {	
			
			for(var i:Number = 0; i < 8; i++) {

				for(var j:Number = 0; j < 8; j++) {

					var square:DisplayObject;
					var index:Number = getIndex(i, j);

					if(index == 22 || index == 37 || index == 55) {
						square = Factory.get("lake");
					} else if(index == 30) {
						square = Factory.get("blue_castle");
						square.addEventListener(MouseEvent.CLICK, putMark);
					} else if(index == 57) {
						square = Factory.get("red_castle");
					} else {
						square = Factory.get("field");
						square.addEventListener(MouseEvent.CLICK, putMark);
					}	

					square.width *= 0.9;
					square.height *= 0.9;
					square.y = 35 +i*(square.height-2);
					square.x = 80 + j*(square.width-2);
					fields[index - 1] = square as MovieClip;
					addChild(square);
				}
			}

			for(i = 0; i < 6; i++) {

				var sword:DisplayObject = Factory.get("sword");
				sword.width *= 0.9;
				sword.height *= 0.9;
				sword.y = 50 + i*(sword.height + 5);
				sword.x = 487;
				swords[i] = sword as MovieClip;
				addChild(sword);
			}

			var attackButton:DisplayObject = Factory.get("attack_btn");
			attackButton.x = 470;
			attackButton.y = 360;
			attackButton.addEventListener(MouseEvent.CLICK, evaluate);
			addChild(attackButton);
		}

		private function getIndex(i:Number, j:Number):Number {
			return i*8 + j + 1;
		} 


		public function evaluate(e:Event):Boolean {

			var isOk:Boolean = true;
			
			for(var i:Number = 1; i <= 64 && isOk; i++) {
				
				if(i == 26 || i == 30 || i == 33 || i == 34 || i == 42 || i == 62) {
					if(fields[i - 1].currentFrame == 1) {
						isOk = false;
					}
				} else {
					if(fields[i - 1].currentFrame == 2) {
						isOk = false;
					}
				}
			}

			trace(isOk);

			if(isOk) {
				complete( );
				return true;
			}

			return false;
		}

		public function complete( ):void {

			trace("map completed!");
			DatabaseManager.complete("easter2015", "map");
		}

		private function putMark(e:MouseEvent):void {

			if(e.currentTarget.currentFrame == 1 && amount < 6) {
				useSword();
				e.currentTarget.play();
			} else if(e.currentTarget.currentFrame != 1) {
				releaseSword();
				e.currentTarget.gotoAndStop(1);
			}
		}

		private function useSword():void {

			swords[amount++].play();
		}

		private function releaseSword():void {

			swords[--amount].gotoAndStop(1);
		}
	}
}
