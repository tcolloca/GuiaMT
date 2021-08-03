package rooms {

	import br.com.stimuli.loading.*;
	import flash.display.*;
	import flash.net.*;
	import flash.events.*;
	import flash.media.*;
	import flash.utils.*;
	import flash.system.*;
	import utils.*;
	import items.*;
	import utils.drag.*;
	import screener.*;
	import popups.*;
	import puzzles.easter2015.*;
	import puzzles.easter2015.magazine.*;
	import puzzles.easter2015.trash.*;

	public class MainRoom extends MovieClip implements ExternalSWF {

		private static const ROOT:String = Constants.ROOT;
		private static const GRID:Boolean = Constants.GRID;
		private static const POPUPS:Number = 10;
		private static const ITEMS_AMOUNT:Number = Trash.ITEMS_AMOUNT;
		private var loader:BulkLoader;
		private var loadingBar:LoadingBar;

		private var roomContent:MovieClip = new MovieClip();

		public function MainRoom() {

			loadingBar = new LoadingBar();
			loadingBar.x = (stage.stageWidth - loadingBar.width)/2;
			loadingBar.y = (stage.stageHeight - loadingBar.height)/2;
			stage.addChild(loadingBar);
			addLoader(new BulkLoader("main-loader"));

			loader.addEventListener(BulkLoader.PROGRESS, whileLoading);
			loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);

			loadRoom();
			loadInventory();
			loadMagazine();
			loadMap();
			loadTrash();
			loadFridge();
			loadShake();

			if(GRID) {
				loader.add(ROOT+"/images/utils/grid.swf", {id:"_grid"});
			}

			loader.start();
		}

		/*DatabaseManager.usedObject("easter2015", "magazine_piece_1", handler, true);
		private function handler(urlLoader:URLLoader) {

			trace(urlLoader.data.ret);
		}*/

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}

		private function loadRoom():void {

			loader.add(ROOT+"/images/rooms/main_room.swf", {id:"room_background"});

			loader.add(ROOT+"/images/screener/screener_background.swf", {id:"screener_background"});
			loader.add(ROOT+"/images/screener/inventory.swf", {id:"inventory"});
			loader.add(ROOT+"/images/screener/screen_arrow.swf", {id:"screen_left_arrow"});
			loader.add(ROOT+"/images/screener/screen_arrow.swf", {id:"screen_right_arrow"});

			loader.add(ROOT+"/images/utils/inventory.swf", {id:"inventory_popup"});

			for(var i:Number = 0; i < POPUPS; i++) {

				loader.add(ROOT+"/images/utils/blackscreen.swf", {id:"blackscreen" + i});
				loader.add(ROOT+"/images/utils/esc.swf", {id:"esc" + i});
			}

			loader.add(ROOT+"/images/objects/easter2015/main_room/magazine.swf", {id:"magazine_obj"});
			loader.add(ROOT+"/images/objects/easter2015/main_room/map.swf", {id:"map_obj"});
			loader.add(ROOT+"/images/objects/easter2015/main_room/trash.swf", {id:"trash_obj"});
			loader.add(ROOT+"/images/objects/easter2015/main_room/fridge.swf", {id:"fridge_obj"});
			loader.add(ROOT+"/images/objects/snake/mixer.swf", {id:"shake_obj"});

			loader.add(ROOT+"/src/flas/easter2015/puzzles/magazine.swf", {id:"magazine_puzzle"});
			loader.add(ROOT+"/src/flas/easter2015/puzzles/map.swf", {id:"map_puzzle"});
			loader.add(ROOT+"/src/flas/easter2015/puzzles/trash.swf", {id:"trash_popup"});
			loader.add(ROOT+"/src/flas/easter2015/puzzles/fridge.swf", {id:"fridge_puzzle"});
			loader.add(ROOT+"/src/flas/easter2015/puzzles/shake.swf", {id:"shake_puzzle"});
		}

		private function loadInventory():void {

			var invItems:Array = Easter2015Constants.inventoryItems;

			for(var i in invItems) {

				for(var j in invItems[i][1]) {
					loader.add(DatabaseManager.hasObject("easter2015", invItems[i][1][j], null, false), {id:"has_" + invItems[i][1][j]});
					loader.add(ROOT+"/images/objects/"+invItems[i][0]+"/"+invItems[i][1][j]+".swf", {id:invItems[i][1][j]});
				}
			}
		}

		private function loadMagazine():void {

			loader.add(DatabaseManager.hasCompleted("easter2015", "magazine", null, false), {id:"hasCompleted_magazine"});

			loader.add(ROOT+"/images/puzzles/easter2015/magazine/magazine.swf", {id:"magazine_background"});

			loader.add(ROOT+"/images/puzzles/easter2015/magazine/hexaku_instructions.swf", {id:"hexaku_instructions"});
			loader.add(ROOT+"/images/puzzles/easter2015/magazine/hexaku.swf", {id:"hexaku"});
			loader.add(ROOT+"/images/utils/check_circle_mark.swf", {id:"hexaku_check_mark"});

			loader.add(ROOT+"/images/puzzles/easter2015/magazine/rudoku_instructions.swf", {id:"rudoku_instructions"});
			loader.add(ROOT+"/images/puzzles/easter2015/magazine/rudoku.swf", {id:"rudoku"});
			loader.add(ROOT+"/images/utils/check_circle_mark.swf", {id:"rudoku_check_mark"});

			loader.add(ROOT+"/images/puzzles/easter2015/magazine/sumaku_instructions.swf", {id:"sumaku_instructions"});
			loader.add(ROOT+"/images/puzzles/easter2015/magazine/sumaku.swf", {id:"sumaku"});
			loader.add(ROOT+"/images/utils/check_circle_mark.swf", {id:"sumaku_check_mark"});

			loader.add(ROOT+"/images/utils/small_arrow.swf", {id:"magazine_small_left_arrow"});
			loader.add(ROOT+"/images/utils/small_arrow.swf", {id:"magazine_small_right_arrow"});
		}

		private function loadMap():void {

			loader.add(DatabaseManager.hasCompleted("easter2015", "map", null, false), {id:"hasCompleted"});

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

		private function loadTrash():void {

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

		private function loadFridge():void {

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

		private function loadFridgeItems():void {

			var fixedHfestItems:Array = Easter2015Constants.fixedHfestItems;
			var movableHfestItems:Array = Easter2015Constants.movableHfestItems;
			var externalHfestItems:Array = Easter2015Constants.externalHfestItems;

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
		}

		public function loadShake():void {

			loader.add(DatabaseManager.hasCompleted("easter2015", "shake", null, false), {id:"hasCompleted_shake"});

			loader.add(ROOT+"/images/puzzles/easter2015/shake/shake.swf", {id:"shake_background"});
			loader.add(ROOT+"/images/objects/snake/mixer.swf", {id:"mixer"});

			loader.add(ROOT+"/images/utils/reset_button.swf", {id:"reset_button"});
			loader.add(ROOT+"/images/utils/blue_button.swf", {id:"bag_button"});
			loader.add(ROOT+"/images/utils/green_button.swf", {id:"colors_button"});
			loader.add(ROOT+"/images/utils/orange_button.swf", {id:"diofantic_button"});

			loader.add(ROOT+"/images/puzzles/easter2015/shake/smoothie1.swf", {id:"smoothie1"});
			loader.add(ROOT+"/images/puzzles/easter2015/shake/smoothie2.swf", {id:"smoothie2"});
			loader.add(ROOT+"/images/puzzles/easter2015/shake/smoothie3.swf", {id:"smoothie3"});

			loader.add(ROOT+"/images/objects/snake/frumilla_jam.swf", {id:"frumilla_jam"});
			loader.add(ROOT+"/images/objects/snake/libernucla_jam.swf", {id:"libernucla_jam"});
			loader.add(ROOT+"/images/objects/snake/higo_jam.swf", {id:"higo_jam"});
			loader.add(DatabaseManager.usedObject("easter2015", "higo_jam", null, false), {id:"used_" + "higo_jam"});

			loadShakeFruits();
		}

		private function loadShakeFruits():void {

			var fruitName:String;
			var fixedSnakeFruitPacks:Array = Easter2015Constants.fixedSnakeFruitPacks;
			var externalSnakeFruits:Array = Easter2015Constants.externalSnakeFruits;

			for(var i in fixedSnakeFruitPacks) {

				if(fixedSnakeFruitPacks[i].quantity > 1) {
					for(var k:int = 0; k < fixedSnakeFruitPacks[i].quantity; k++) {

						fruitName = fixedSnakeFruitPacks[i].fruit.name;
						loader.add(ROOT+"/images/objects/snake/"+fruitName+".swf", {id:(fruitName + k)});
					}
				} else {
					fruitName = fixedSnakeFruitPacks[i].fruit.name;
					loader.add(ROOT+"/images/objects/snake/"+fruitName+".swf", {id:fruitName});
				}
			}

			for(var j in externalSnakeFruits) {

				loader.add(ROOT+"/images/objects/snake/"+externalSnakeFruits[j].name+".swf", {id:externalSnakeFruits[j].name});

				loader.add(DatabaseManager.usedObject("easter2015", externalSnakeFruits[j].name, null, false),
					{id:"used_" + externalSnakeFruits[j].name});
			}
		}

		private function whileLoading(e:BulkProgressEvent):void {

			var pcent:Number = int(e._ratioLoaded * 100);
			loadingBar.setPercentage(pcent);
		}

		public function finishedLoading(e:BulkProgressEvent):void {

			stage.removeChild(loadingBar);

			Factory.setLoader(loader);

			initializeBackground();
			initializeItems();
			initializeInventory();
			initializeObjects();
			stage.addChild(roomContent);

			initializeScreener();

			if(GRID) {
				initializeGrid();
			}
		}

		private function initializeBackground():void {

			roomContent.addChildAt(loader.getContent("room_background"), 0);
			//roomContent.addEventListener(MouseEvent.MOUSE_UP, dropIt);
		}

		private function initializeItems():void {

			var invItems:Array = Easter2015Constants.inventoryItems;

			for(var i in invItems) {

				for(var j in invItems[i][1]) {

					var item:DisplayObject = Factory.get(invItems[i][1][j]);
					var targetObject:DisplayObject;
					var targetPuzzle:ItemUser;

					switch(invItems[i][0]) {
						case "hammerfest":
							targetObject = Factory.get("fridge_obj");
							targetPuzzle = Factory.get("fridge_puzzle") as ItemUser;
							break;
						case "magazine":
							targetObject = Factory.get("magazine_obj");
							targetPuzzle = Factory.get("magazine_puzzle") as ItemUser;
							break;
						case "map":
							targetObject = Factory.get("map_obj");
							targetPuzzle = Factory.get("map_puzzle") as ItemUser;
							break;
						case "snake":
							targetObject = Factory.get("shake_obj");
							targetPuzzle = Factory.get("shake_puzzle") as ItemUser;
							break;
					}

					GlobalVars.itemsMap[item] = new Array(invItems[i][1][j], targetObject, targetPuzzle);
				}
			}
		}

		private function initializeInventory():void {

			var invItems:Array = Easter2015Constants.inventoryItems;

			for(var i in invItems) {

				for(var j in invItems[i][1]) {

					var hasObj:Boolean = Parser.parse(loader.getContent("has_" + invItems[i][1][j])).ret == "true";

					if(hasObj) {
						GlobalVars.inventory.addItem(invItems[i][1][j]);
					}
				}
			}
		}

		private function initializeObjects():void {

			initializeMagazine();
			initializeMap();
			initializeTrash();
			initializeFridge();
			initializeShake();
		}

		private function initializeMagazine():void {

			var magazinePopUp:PopUp = new PopUp();
			magazinePopUp.x = 35;
			magazinePopUp.y = 15;

			var magazineContent:MovieClip = loader.getContent("magazine_puzzle");
			(magazineContent as ExternalSWF).addLoader(loader);
			(magazineContent as ExternalSWF).finishedLoading(null);

			magazinePopUp.addChild(magazineContent);

			var magazine:PopUpOpener = new PopUpOpener(magazinePopUp);
			magazine.addChild(loader.getContent("magazine_obj"));
			magazine.x = 600;
			magazine.y = 210;
			roomContent.addChild(magazine);
		}

		private function initializeMap():void {

			var mapPopUp:PopUp = new PopUp();
			mapPopUp.x = 110;
			mapPopUp.y = 20;

			var mapContent:MovieClip = loader.getContent("map_puzzle");
			(mapContent as ExternalSWF).addLoader(loader);
			(mapContent as ExternalSWF).finishedLoading(null);
			mapContent.width *= 0.80;
			mapContent.height *= 0.80;
			mapPopUp.addChild(mapContent);


			var map:PopUpOpener = new PopUpOpener(mapPopUp);
			map.addChild(loader.getContent("map_obj"));
			map.x = -40;
			map.y = 287;
			roomContent.addChild(map);
		}

		private function initializeTrash():void {

			var trashPopUp:PopUp = new PopUp();
			trashPopUp.x = 350;
			trashPopUp.y = 225;

			var trash:PopUpOpener = new PopUpOpener(trashPopUp);
			trash.addChild(loader.getContent("trash_obj"));
			trash.x = 450;
			trash.y = 215;

			var item:Item = new Item("clue", "¡Felicitaciones! Has conseguido la primera pista.");
			item.addChild(Factory.get("clue_obj"));

			var trashContent:MovieClip = loader.getContent("trash_popup");
			trashContent as DragContainer; //magic mysterious line
			(trashContent as ExternalSWF).addLoader(loader);
			if(isAvailable("clue")) {
				(trashContent as Container).addPopUpOpener(trash);
				(trashContent as Container).addItem(item);
			}
			(trashContent as ExternalSWF).finishedLoading(null);
			trashContent.width *= 0.80;
			trashContent.height *= 0.80;
			trashPopUp.addChild(trashContent);

			roomContent.addChild(trash);
		}

		private function initializeFridge():void {

			var fridgePopUp:PopUp = new PopUp();

			var fridgeContent:MovieClip = loader.getContent("fridge_puzzle");
			(fridgeContent as ExternalSWF).addLoader(loader);
			(fridgeContent as ExternalSWF).finishedLoading(null);
			fridgePopUp.addChild(fridgeContent);


			var fridge:BigPopUpOpener = new BigPopUpOpener(fridgePopUp);
			fridge.addChild(loader.getContent("fridge_obj"));
			roomContent.addChild(fridge);
		}

		private function initializeShake():void {

			var shakePopUp:PopUp = new PopUp();

			var shakeContent:MovieClip = loader.getContent("shake_puzzle");
			(shakeContent as ExternalSWF).addLoader(loader);
			(shakeContent as ExternalSWF).finishedLoading(null);
			shakePopUp.addChild(shakeContent);


			var shake:BigPopUpOpener = new BigPopUpOpener(shakePopUp);
			shake.x = 720;
			shake.y = 190;
			shake.scaleX /= 4;
			shake.scaleY /= 4;
			shake.addChild(loader.getContent("shake_obj"));
			roomContent.addChild(shake);
		}

		private function isAvailable(objName:String):Boolean {

			return Parser.parse(loader.getContent("isAvailable_" + objName)).ret == "true";
		}

		private function initializeScreener():void {

			var inventoryPopUp:PopUp = new PopUp();

			var inventoryContent:MovieClip = loader.getContent("inventory_popup");
			inventoryPopUp.addChild(inventoryContent);

			var inventory:InventoryOpener = new InventoryOpener(inventoryPopUp);
			inventory.addChild(loader.getContent("inventory"));

			var leftArrow:ScreenArrow = new ScreenArrow(roomContent, "left");
			leftArrow.addChild(loader.getContent("screen_left_arrow"));

			var rightArrow:ScreenArrow = new ScreenArrow(roomContent, "right");
			var arrowContent:MovieClip = loader.getContent("screen_right_arrow");
			arrowContent.scaleX *= -1;
			rightArrow.addChild(arrowContent);

			var screenerObj:Screener = new Screener();
			screenerObj.addChild(loader.getContent("screener_background"));

			inventory.x = 23;
			inventory.y = 400;
			screenerObj.addChild(inventory);

			leftArrow.x = 575;
			leftArrow.y = 400;
			screenerObj.addChild(leftArrow);

			rightArrow.x = 680;
			rightArrow.y = 400;
			screenerObj.addChild(rightArrow);

			stage.addChild(screenerObj);
		}

		private function initializeGrid() {

			stage.addChild(loader.getContent("_grid"));
		}
	}
}
