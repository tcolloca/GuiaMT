package puzzles.easter2015.fridge {
	
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
	import events.*;

	//TODO: Frutas de snake.

	public class Fridge extends PopUp implements Puzzle, ExternalSWF, ItemUser {

		public static const ROOT:String = Constants.ROOT; 
		public static const TESTING:Boolean = Constants.TESTING;
		private var loader:BulkLoader;
		
		private var fixedHfestItems = Easter2015Constants.fixedHfestItems;
		private var movableHfestItems = Easter2015Constants.movableHfestItems;
		private var externalHfestItems = Easter2015Constants.externalHfestItems.sort(randomize);

		private var currFloor:Number = 1;

		private var fridgeItems:Array = new Array(49);
		private var itemsMap:Dictionary = new Dictionary();

		private var fridgeInventory:Inventory = new Inventory();
		private var currPage:Number = 1;
		private const PAGE_COLS:Number = 2;
		private const PAGE_ROWS:Number = 6;
		private const PAGE_SIZE:Number = PAGE_ROWS*PAGE_COLS;

		private var spaces:Array = new Array(16);
		private var inventory_mc:DisplayObject;
		private var rightArrow:MovieClip;
		private var leftArrow:MovieClip;
		private var rightShadowArrow:MovieClip;
		private var leftShadowArrow:MovieClip;

		private var startX:Number;
		private var startY:Number;
		private var dragged:MovieClip;

		public function Fridge() {

			if(TESTING) {
				addLoader(new BulkLoader("fridge-loader"));
				loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);
				loadFridge();
				loader.start();
			}
		}

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}

		public function addItem(name:String):void {

			fridgeInventory.addItem(name);
			Factory.get(name).addEventListener(MouseEvent.MOUSE_DOWN, pickUp);
			refreshFridgeInventory();
		}

		public function loadFridge():void {

			loader.add(DatabaseManager.hasCompleted("easter2015", "fridge", null, false), {id:"hasCompleted_fridge"});
			loader.add(DatabaseManager.hasCompleted("easter2015", "fridge", null, false), {id:"hasCompleted_fridge"});

			loader.add(ROOT+"/images/puzzles/easter2015/fridge/fridge.swf", {id:"fridge_background"});
			loader.add(ROOT+"/images/puzzles/easter2015/fridge/fridge_inventory.swf", {id:"fridge_inventory"});

			for(var i:Number = 0; i < 2; i++) {

				loader.add(ROOT+"/images/puzzles/easter2015/fridge/frozen_arrow.swf", {id:"frozen_arrow" + i});
				loader.add(ROOT+"/images/puzzles/easter2015/fridge/shadow_frozen_arrow.swf", {id:"shadow_frozen_arrow" + i});
			}

			loader.add(ROOT+"/images/puzzles/easter2015/fridge/frozen_one.swf", {id:"frozen_one"});
			loader.add(ROOT+"/images/puzzles/easter2015/fridge/frozen_two.swf", {id:"frozen_two"});
			loader.add(ROOT+"/images/puzzles/easter2015/fridge/frozen_three.swf", {id:"frozen_three"});
			
			loadFridgeItems();
		}

		public function loadFridgeItems():void {

			for(var i in fixedHfestItems) {
				
				loader.add(ROOT+"/images/objects/hammerfest/"+fixedHfestItems[i][1]+".swf", {id:fixedHfestItems[i][1]});
			}
			
			for(var j in movableHfestItems) {
				
				loader.add(ROOT+"/images/objects/hammerfest/"+movableHfestItems[j][1]+".swf", {id:movableHfestItems[j][1]});
			}

			for(var h in externalHfestItems) {
				
				loader.add(ROOT+"/images/objects/hammerfest/"+externalHfestItems[h][1]+".swf", {id:externalHfestItems[h][1]});
			}

			for(var k in externalHfestItems) {
				
				loader.add(DatabaseManager.usedObject("easter2015", externalHfestItems[k][1], null, false), {id:"used_" + externalHfestItems[k][1]});
			}
			//TODO: Load snake items
		}

		public function evaluate(e:Event):Boolean {

			var item:DisplayObject;

			for(var i in fixedHfestItems) {
				
					item = fridgeItems[fixedHfestItems[i][0]];
					if(item == null || itemsMap[item] != fixedHfestItems[i][1]) {
						return false;
					}
				}
			
				for(var j in movableHfestItems) {
					
					item = fridgeItems[movableHfestItems[j][0]];
					if(item == null || itemsMap[item] != movableHfestItems[j][1]) {
						return false;
					}
				}

				for(var k in externalHfestItems) {

					item = fridgeItems[externalHfestItems[k][0]];
					if(item == null || itemsMap[item] != externalHfestItems[k][1]) {
						return false;
					}
				}

				complete( );
				return true;
		}

		public function complete( ):void {

			trace("fridge completed!");//TODO: Clue
			for(var j in movableHfestItems) {
					
					Factory.get(movableHfestItems[j][1]).removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);

			}

			for(var k in externalHfestItems) {
					
					Factory.get(externalHfestItems[k][1]).removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);
			}

			DatabaseManager.complete("easter2015", "fridge");
		}
		
		public function finishedLoading(e:BulkProgressEvent):void {

			Factory.setLoader(loader);
			initializeBackground();
			initializeContent();	
			initializeArrows();
		}
		
		private function initializeBackground():void {
			
			var background = Factory.get("fridge_background") as MovieClip;
			addChildAt(background, 0);
			addChildAt(inventory_mc = Factory.get("fridge_inventory"), 1);

			for(var i:Number = 0; i < 16; i++) {
				spaces[i] = background.getChildByName("space" + (i + 1));
			}

			var one:MovieClip = Factory.get("frozen_one") as MovieClip;
			one.x = 576;
			one.y = 396;
			one.addEventListener(MouseEvent.MOUSE_DOWN, gotoFirstFloor);
			addChild(one);

			var two:MovieClip = Factory.get("frozen_two") as MovieClip;
			two.x = 600;
			two.y = 395;
			two.addEventListener(MouseEvent.MOUSE_DOWN, gotoSecondFloor);
			addChild(two);

			var three:MovieClip = Factory.get("frozen_three") as MovieClip;
			three.x = 627;
			three.y = 396;
			three.addEventListener(MouseEvent.MOUSE_DOWN, gotoThirdFloor);
			addChild(three);
		}
		
		private function initializeContent():void {	
			
			var isCompleted:Boolean = Parser.parse(loader.getContent("hasCompleted_fridge")).ret == "true";

			trace("fridge is completed?"+isCompleted);
			
			if(isCompleted) {
				placeItemsCorrectly();
			} else {
				
				placeItemsRandomly();

				for(var i in fixedHfestItems) {
				
					itemsMap[Factory.get(fixedHfestItems[i][1])] = fixedHfestItems[i][1];
				}
			
				for(var j in movableHfestItems) {
					
					itemsMap[Factory.get(movableHfestItems[j][1])] = movableHfestItems[j][1];
					Factory.get(movableHfestItems[j][1]).addEventListener(MouseEvent.MOUSE_DOWN, pickUp);

				}

				for(var k in externalHfestItems) {

					itemsMap[Factory.get(externalHfestItems[k][1])] = externalHfestItems[k][1];

					var usedObj:Boolean = Parser.parse(loader.getContent("used_" + externalHfestItems[k][1])).ret == "true";
					
					if(usedObj) {
						fridgeInventory.addItem(externalHfestItems[k][1]);
						Factory.get(externalHfestItems[k][1]).addEventListener(MouseEvent.MOUSE_DOWN, pickUp);
					}
				}

				displayFridgeInventory();

				this.addEventListener(MouseEvent.MOUSE_UP, dropIt);
			}
		}

		private function placeItemsCorrectly():void {

			var floor:Number;
			var item:DisplayObject;
			
			placeFixedItems();

			for(var j in movableHfestItems) {
				
				item = Factory.get(movableHfestItems[j][1]);
				floor = setPosition(item, movableHfestItems[j][0]);
				fridgeItems[movableHfestItems[j][0]] = item;
				
				if(floor == currFloor) {
					addChild(item);
				}
			}

			for(var k in externalHfestItems) {

				item = Factory.get(externalHfestItems[k][1]);
				floor = setPosition(item, externalHfestItems[k][0]);
				fridgeItems[externalHfestItems[k][0]] = item;
				
				if(floor == currFloor) {
					addChild(item);
				}
			}
		}

		private function placeItemsRandomly():void {

			var floor:Number;
			var item:DisplayObject;
			var randomArr:Array = new Array(movableHfestItems.size);
			
			placeFixedItems();

			for(var i in movableHfestItems) {
			
				randomArr[i] = movableHfestItems[i][0];
			}

			for(var k:Number = 0; k < 10; k++) {

				randomArr.sort(randomize);
			}

			for(var j in movableHfestItems) {
				
				item = Factory.get(movableHfestItems[j][1]);
				floor = setPosition(item, randomArr[j]);
				fridgeItems[randomArr[j]] = item;
				
				if(floor == currFloor) {
					addChild(item);
				}
			}
		}

		private function randomize (a:*, b:*):int {
		   
		    return (Math.random() > .5) ? 1:-1;
		}

		private function placeFixedItems():void {

			var floor:Number;
			var item:DisplayObject;

			for(var i in fixedHfestItems) {
				
				item = Factory.get(fixedHfestItems[i][1]);
				floor = setPosition(item, fixedHfestItems[i][0]);
				fridgeItems[fixedHfestItems[i][0]] = item;
				
				if(floor == 1) {
					addChild(item);
				}
			}
		}

		private function displayFridgeInventory():void {

			for(var i:Number = (currPage - 1)*PAGE_SIZE; i < fridgeInventory.size && i < currPage*PAGE_SIZE; i++) {

				var item:DisplayObject = Factory.get(fridgeInventory.getItem(i));

				if(item != null)
					positionInventoryItem(item, i);
			}
		}

		private function refreshFridgeInventory():void {

			for(var i:Number = 0; i < fridgeInventory.size; i++) {

				var item:DisplayObject = Factory.get(fridgeInventory.getItem(i));
				
				if(item != null && contains(item)) {
					item.scaleX = item.scaleY /= 0.45;
					removeChild(item);
				}
			}

			displayFridgeInventory();
		}

		private function positionInventoryItem(item:DisplayObject, i:Number):void {
				
			var pageId:Number = i % PAGE_SIZE;
			var col:Number = pageId % PAGE_COLS;
			var row:int = (int)(pageId / PAGE_COLS);
			var startX:Number = 587;
			var startY:Number = 102;

			item.x = startX + 45*col;
			item.y = startY + 45*row;

			item.scaleX = item.scaleY *= 0.45;

			addChild(item);
		}

		private function initializeArrows():void {

			rightArrow = Factory.get("frozen_arrow") as MovieClip;
			leftArrow = Factory.get("frozen_arrow") as MovieClip;
			leftArrow.scaleX *= -1;
			rightShadowArrow = Factory.get("shadow_frozen_arrow") as MovieClip;
			leftShadowArrow = Factory.get("shadow_frozen_arrow") as MovieClip;
			leftShadowArrow.scaleX *= -1;

			rightArrow.x = rightShadowArrow.x = 653;
			leftArrow.x = leftShadowArrow.x = 570; 
			rightArrow.y = rightShadowArrow.y = leftArrow.y = leftShadowArrow.y = 400;

			leftArrow.addEventListener(MouseEvent.MOUSE_DOWN, prevPage);
			rightArrow.addEventListener(MouseEvent.MOUSE_DOWN, nextPage);

			displayArrows();
		}

		private function refreshArrows():void {

			if(contains(rightArrow)) {
				removeChild(rightArrow);
			} else {
				removeChild(rightShadowArrow);
			}

			if(contains(leftArrow)) {
				removeChild(leftArrow);
			} else {
				removeChild(leftShadowArrow);
			}

			displayArrows();
		}

		private function displayArrows():void {

			if(currPage > 1) {
				addChild(leftArrow);
			} else {
				addChild(leftShadowArrow);
			}

			if(currPage < getLastPage()) {
				addChild(rightArrow);
			} else {
				addChild(rightShadowArrow);
			}
		}

		private function getLastPage():Number {
			
			return (int)((fridgeInventory.size - 1)/ PAGE_SIZE) + 1;
		}

		private function prevPage(e:MouseEvent):void {

			currPage--;
			refreshArrows();
			refreshFridgeInventory();
		}

		private function nextPage(e:MouseEvent):void {

			currPage++;
			refreshArrows();
			refreshFridgeInventory();
		}

		private function gotoFirstFloor(e:MouseEvent):void {

			removeOldItems();
			currFloor = 1;
			addNewItems();
		}

		private function gotoSecondFloor(e:MouseEvent):void {

			removeOldItems();
			currFloor = 2;
			addNewItems();
		}

		private function gotoThirdFloor(e:MouseEvent):void {

			removeOldItems();
			currFloor = 3;
			addNewItems();
		}

		private function removeOldItems():void {

			for(var i:Number = (3 - currFloor)*16 + 1; i <= (3 - currFloor + 1)*16; i++) {

				if(fridgeItems[i] != null) {
					removeChild(fridgeItems[i]);
				}
			}
		}

		private function addNewItems():void {

			for(var i:Number = (3 - currFloor)*16 + 1; i <= (3 - currFloor + 1)*16; i++) {

				if(fridgeItems[i] != null) {
					addChild(fridgeItems[i]);
				}
			}
		}

		private function setPosition(item:DisplayObject, i:int):Number {

			var floor:int = 3 - (int)((i - 1)/16);
			var baseX:Number = 167;
			var spacing:Number = 70;
			var colMod:Number = (int)(((i - 1) % 16) / 4);

			baseX -= colMod*7;
			spacing += colMod*5;

			item.x = ((i-1) % 4)*spacing + baseX;
			item.scaleX = item.scaleY *= (0.5 + colMod*0.1); 

			switch(colMod) {
				case 0:
					item.y = 122 - item.height/2;
					break;
				case 1:
					item.y = 176 - item.height/2;
					break;
				case 2:
					item.y = 245 - item.height/2;
					break;
				case 3:
					item.y = 323 - item.height/2;
					break;
			}

			return floor;
		}

		private function getScale(item:DisplayObject):Number {

			if(fridgeItems.indexOf(item) >= 0) {
				var colMod:Number = (int)(((fridgeItems.indexOf(item) - 1) % 16) / 4);

				return colMod*0.1 + 0.5;
			} else {
				return 0.45;
			}
		}

		private function pickUp(event:MouseEvent):void {
			
			dragged = event.target as MovieClip;
			
			dragged.startDrag(false);
			startX = dragged.x;
		    startY = dragged.y;  
		    bringToFront(dragged.parent);

		    if(fridgeInventory.hasItem(itemsMap[dragged.parent])) {
		    	fridgeInventory.removeItem(itemsMap[dragged.parent]);
		    }

			dragged.parent.scaleX = dragged.parent.scaleY /= getScale(dragged.parent as DisplayObject);
		}

		private function dropIt(event:MouseEvent):void {

			if(dragged == null) {
				return;
			} 

			dragged.stopDrag();

			if(dragged.hitTestObject(inventory_mc)) {

				fridgeItems[fridgeItems.indexOf(dragged.parent)] = null;
				removeChild(dragged.parent); 
				fridgeInventory.addItem(itemsMap[dragged.parent]);
				refreshArrows();
				refreshFridgeInventory();
			
			} else {
				
				for(var i in spaces) {
					
					var spaceLinearPos:Number = i + 16*(3 - currFloor) + 1;

					if(dragged.hitTestObject(spaces[i]) && !fridgeItems[spaceLinearPos]) {
						
						fridgeItems[fridgeItems.indexOf(dragged.parent)] = null;
						fridgeItems[spaceLinearPos] = dragged.parent;
						setPosition(dragged.parent as DisplayObject, spaceLinearPos);
						refreshArrows();
						
						dragged.x = startX;
		    			dragged.y = startY;

		    			dragged = null;

		    			evaluate(null);

		    			return;
					}
				}

				dragged.parent.scaleX = dragged.parent.scaleY *= getScale(dragged.parent as DisplayObject);
		    }

		    dragged.x = startX;
		    dragged.y = startY;

		    dragged = null;
		}
		
		private function bringToFront(mcl:DisplayObject):void {
			
			mcl.parent.setChildIndex(mcl,mcl.parent.numChildren - 1);
		}

	}
}
