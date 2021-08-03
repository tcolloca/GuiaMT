package puzzles.easter2015.shake {
	
	import br.com.stimuli.loading.*;
	import flash.display.*;
	import flash.net.*;
	import flash.events.*;
	import flash.media.*;
	import flash.utils.*;
	import flash.system.*;
	import flash.text.*;
	import utils.*;
	import popups.*;
	import puzzles.*;

	public class Shake extends PopUp implements Puzzle, ExternalSWF, ItemUser {

		private static const ROOT:String = Constants.ROOT; 
		private static const TESTING:Boolean = Constants.TESTING;
		private static const GRID:Boolean = Constants.GRID;
		private var loader:BulkLoader;
		
		private static const scale:Number = 1.25;
		private static const minX:Number = 200;
		private static const maxX:Number = 650;
		private static const minY:Number = 300;
		private static const maxY:Number = 420;

		private var currentPuzzle:String = null;
		private var currentSmoothie:int = 1;

		private var bagCompleted:Boolean = false;
		private var diofanticCompleted:Boolean = false;
		private var colorsCompleted:Boolean = false;
		private var allCompleted:Boolean = false;

		private var fixedSnakeFruitPacks:Array = Easter2015Constants.fixedSnakeFruitPacks;
		private var externalSnakeFruits:Array = Easter2015Constants.externalSnakeFruits;
		private var diofanticSnakeFruits:Array = Easter2015Constants.diofanticSnakeFruits;

		private var bagFruitNames:Array = Easter2015Constants.bagFruitNames;
		private var bagPositions:Array = new Array(
		new Array(200, 320), //tarudon
		new Array(280, 300), new Array(265, 350), //piwi_rosa
		new Array(350, 315), new Array(400, 340), new Array(335, 380), //albusides
		new Array(200, 400), new Array(240, 380), new Array(285, 410), new Array(455, 355), //musillo
		new Array(400, 400), new Array(460, 300), new Array(505, 350), new Array(515, 405), //efemerinas
		new Array(550, 315), new Array(563, 358), new Array(580, 396), new Array(620, 310), 
		new Array(615, 354), new Array(635, 415));
		private var t1:TextField = new TextField();
		private var t2:TextField = new TextField();
		private var t3:TextField = new TextField();
		private var t4:TextField = new TextField();
		private var t5:TextField = new TextField();
		private var t6:TextField = new TextField();
		private var t7:TextField = new TextField();

		private var colorsFruitNames:Array = Easter2015Constants.colorsFruitNames;
		private var colorsPositions:Array = new Array(
		new Array(210, 360), new Array(275, 360), new Array(340, 360), new Array(410, 360),
		new Array(490, 360), new Array(570, 360), new Array(650, 360));
		private var smoothies:Array = new Array();
		private var smoothiesCompleted:Array = new Array(false, false, false);

		private var diofanticFruitNames:Array = Easter2015Constants.diofanticFruitNames;

		private var mixer:DisplayObject;
		private var mixerPositions:Dictionary = new Dictionary();
		private var mixerFruits:Array = new Array();
		private var shaked:Boolean = false;

		private var bagFruits:Array = new Array();
		private var colorsFruits:Array = new Array();
		private var diofanticFruits:Array = new Array();

		private var properties:Dictionary = new Dictionary();

		private var startX:Number;
		private var startY:Number;
		private var dragged:MovieClip;

		private var zIndex:int = 3;

		public function Shake() {

			if(TESTING) {
				addLoader(new BulkLoader("shake-loader"));
				loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);
				loadShake();
				loader.start();
			}

			if(GRID) {
				loader.add(ROOT+"/images/utils/grid.swf", {id:"_grid"});
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

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}
		
		public function finishedLoading(e:BulkProgressEvent):void {

			Factory.setLoader(loader);
			initializeBackground();
			initializeContent();
			initializeButtons();

			if(GRID) {
				initializeGrid();
			}
		}

		public function addItem(name:String):void {

			if(name == "higo_jam") {
				addFruit("diofantic", name, diofanticSnakeFruits[1], 1);
				resetPuzzle(null);
				return;
			}

			var k:int = 0;
			while(externalSnakeFruits[k].name != name &&
			 k < externalSnakeFruits.length) k++;

			var h:int = 0;
			while(colorsFruitNames[h] != name &&
			 h < colorsFruitNames.length) h++;

			addFruit("colors", name, externalSnakeFruits[k], h);
			resetPuzzle(null);
		}

		private function initializeBackground():void {
			
			addChildAt(loader.getContent("shake_background"), 0);
		}
		
		private function initializeContent():void {	
			
			var isCompleted:Boolean = Parser.parse(loader.getContent("hasCompleted_shake")).ret == "true";

			if(isCompleted) {
				allCompleted = bagCompleted = colorsCompleted = diofanticCompleted = true;
				smoothiesCompleted = new Array(true, true, true);
			}

			initializeMixer();
			initializeFruits();
			initializeMixerPositions();
			initializeText();
			initializeSmoothies();
			loadBagPuzzle();

			this.addEventListener(MouseEvent.MOUSE_UP, dropIt);
		}

		private function initializeButtons():void {

			var resetButton:DisplayObject = Factory.get("reset_button");
			resetButton.x = 50;
			resetButton.y = 15;

			resetButton.addEventListener(MouseEvent.MOUSE_DOWN, resetPuzzle);
			addChild(resetButton);

			var bagButton:DisplayObject = Factory.get("bag_button");
			bagButton.x = 310;
			bagButton.y = 15;

			var bagFunction:Function = changeToPuzzle("bag");
			bagButton.addEventListener(MouseEvent.MOUSE_DOWN, bagFunction);
			addChild(bagButton);

			var colorsButton:DisplayObject = Factory.get("colors_button");
			colorsButton.x = 430;
			colorsButton.y = 15;

			var colorsFunction:Function = changeToPuzzle("colors");
			colorsButton.addEventListener(MouseEvent.MOUSE_DOWN, colorsFunction);
			addChild(colorsButton);

			var diofanticButton:DisplayObject = Factory.get("diofantic_button");
			diofanticButton.x = 550;
			diofanticButton.y = 15;

			var diofanticFunction:Function = changeToPuzzle("diofantic");
			diofanticButton.addEventListener(MouseEvent.MOUSE_DOWN, diofanticFunction);
			addChild(diofanticButton);
		}

		private function initializeMixer():void {

			var mixer:DisplayObject = Factory.get("mixer");
			mixer.x = 120;
			mixer.y = 250;
			mixer.scaleX *= scale;
			mixer.scaleY *= scale;

			this.mixer = mixer;
			addChild(mixer);
		}

		private function initializeFruits():void {

			var index:int = 0;
			var k:int;
			var pack:FruitPack;

			for(var i in bagFruitNames) {
				
				k = 0;
				while(fixedSnakeFruitPacks[k].fruit.name != bagFruitNames[i] &&
				 k < fixedSnakeFruitPacks.length) k++;

				pack = fixedSnakeFruitPacks[k];

				if(pack.quantity > 1) {
					for(var h:int = 0; h <pack.quantity; h++) {

						addFruit("bag", bagFruitNames[i] + h, pack.fruit, index++);
					}
				} else {
						addFruit("bag", bagFruitNames[i], pack.fruit, index++);		
				}
			}

			for(var j in colorsFruitNames) {

				var external:Boolean = false;
				for(var r in externalSnakeFruits) {

					if(colorsFruitNames[j] == externalSnakeFruits[r].name) {
						var used:Boolean = Parser.parse(loader.getContent("used_"+externalSnakeFruits[r].name)).ret == "true";
						if(used) {
							addFruit("colors", colorsFruitNames[j], externalSnakeFruits[r], j);
						}
						external = true;
					}
				}
				
				if(!external) {
					k = 0;
					while( k < fixedSnakeFruitPacks.length &&
					fixedSnakeFruitPacks[k].fruit.name != colorsFruitNames[j]) k++;

					pack = fixedSnakeFruitPacks[k];
					addFruit("colors", colorsFruitNames[j], pack.fruit, j);
				}	
			}

			for(var s in diofanticFruitNames) {

				if(diofanticFruitNames[s] == "higo_jam") {
					used = Parser.parse(loader.getContent("used_"+"higo_jam")).ret == "true";
					if(used) {
						addFruit("diofantic", diofanticFruitNames[s], diofanticSnakeFruits[s], s);
					}
				} else {
					addFruit("diofantic", diofanticFruitNames[s], diofanticSnakeFruits[s], s);
				}
			}
		}

		private function initializeSmoothies():void {
	
			for(var i:int = 1; i <= 3; i++) {
				
				addSmoothie(Factory.get("smoothie" + i), i);
			}
		}

		private function addSmoothie(smoothie:DisplayObject, i:int):void {

			smoothie.x = 370 + i*70;
			smoothie.y = 80;
			smoothie.scaleX *= 0.5;
			smoothie.scaleY *= 0.5;
			smoothies.push(smoothie);
			var smoothieFunction:Function = loadSmoothie(i);
			smoothie.addEventListener(MouseEvent.CLICK, smoothieFunction);			
		}

		private function initializeMixerPositions():void {

			mixerPositions["tarudon"] = new Array(62, 144, 79, 140);
			mixerPositions["piwi_rosa"] = new Array(60, 150, 82, 147);
			mixerPositions["albusides"] = new Array(60, 141, 81, 138);
			mixerPositions["musillo"] = new Array(57, 150, 82, 146);
			mixerPositions["efemerina"] = new Array(59, 155, 82, 153);
			mixerPositions["limon"] = new Array(60, 150, 80, 147);
			mixerPositions["naranja"] = new Array(56, 150, 84, 148);
			mixerPositions["biliosa"] = new Array(62, 147, 82, 145);
			mixerPositions["chankasar"] = new Array(61, 151, 78, 149);
			mixerPositions["bolanio"] = new Array(68, 143, 78, 140);
			mixerPositions["marveluna"] = new Array(69, 135, 69, 133);
			mixerPositions["nuez_de_ziduna"] = new Array(61, 145, 76, 144);
		}

		private function initializeText() {

			t1.width = 100;
			t1.height = 100;
			t1.x = 330;
			t1.y = 130;

			t2.width = 100;
			t2.height = 100;
			t2.x = 400;
			t2.y = 130;

			t3.width = 100;
			t3.height = 100;
			t3.x = 450;
			t3.y = 130;

			t4.width = 200;
			t4.height = 100;
			t4.x = 330;
			t4.y = 160;

			t5.width = 100;
			t5.height = 100;
			t5.x = 230;
			t5.y = 130;

			t6.width = 200;
			t6.height = 100;
			t6.x = 260;
			t6.y = 130;

			t7.width = 200;
			t7.height = 100;
			t7.x = 340;
			t7.y = 130;

			addChild(t1);
			addChild(t2);
			addChild(t3);
			addChild(t4);
			addChild(t5);
			addChild(t6);
			addChild(t7);
		}

		private function changeToPuzzle(nextPuzzle:String):Function {

			return function(e:MouseEvent):void {
			   
			    unloadPuzzle();

				switch(nextPuzzle) {
				case "bag":
					loadBagPuzzle();
					break;
				case "colors":
					loadColorsPuzzle();
					break;
				case "diofantic":
					loadDiofanticPuzzle();
					break;
				}
			};
		}

		private function loadBagPuzzle():void {

			currentPuzzle = "bag";

			var fruit:DisplayObject;

			if(!bagCompleted) {

				for(var i in bagFruits) {

					fruit = bagFruits[i];
					fruit.addEventListener(MouseEvent.MOUSE_DOWN, pickUp);
					fruit.x = getXPosition(fruit, i);
					fruit.y = getYPosition(fruit, i);
					addChild(fruit);
				}

				mixer["mixer"]["on_button"].addEventListener(MouseEvent.MOUSE_DOWN, bagShakeIt);
			} else if(!shaked) {
				var e:int = 0;
				var m:int = 0;
				for(var j in bagFruits) {

					var name:String = properties[bagFruits[j]].name;
					if(name == "tarudon" ||
					(name == "musillo" && m++ < 2) ||
					(name == "efemerina" && e++ < 9)) {
						fruit = bagFruits[j];
						fruit.x = getXPosition(fruit, j);
						fruit.y = getYPosition(fruit, j);
						addChild(fruit);
					}
				}
			}
		}

		private function loadColorsPuzzle():void {

			currentPuzzle = "colors";

			if(!isSmoothieCompleted(currentSmoothie)) 
				smoothies[currentSmoothie - 1]["smoothie"].gotoAndStop(2);
			else {
				for(var b in smoothiesCompleted) {
					if(!smoothiesCompleted[b]) {
						currentSmoothie = b + 1;
						smoothies[currentSmoothie - 1]["smoothie"].gotoAndStop(2);
						break;
					} else {
						smoothies[b]["smoothie"].gotoAndStop(3);
					}
				}
			}

			var fruit:DisplayObject;

			for(var i in colorsFruits) {

				fruit = colorsFruits[i];
				fruit.addEventListener(MouseEvent.MOUSE_DOWN, pickUp);
				fruit.x = getXPosition(fruit, i);
				fruit.y = getYPosition(fruit, i);
				addChild(fruit);
			}

			mixer["mixer"]["on_button"].addEventListener(MouseEvent.MOUSE_DOWN, colorsShakeIt);

			for(var s in smoothies) {

				addChild(smoothies[s]);
			}
		}

		private function loadSmoothie(smoothie:int):Function {
	
			return function(e:MouseEvent):void {
			   
			   if(!isSmoothieCompleted(smoothie)) {
			   		resetPuzzle(null);
					unloadSmoothie(currentSmoothie);
				    currentSmoothie = smoothie;
					smoothies[smoothie - 1]["smoothie"].gotoAndStop(2);
			   } 
			};
		}

		private function unloadSmoothie(smoothie:int):void {

			if(!isSmoothieCompleted(smoothie)) {
				smoothies[smoothie - 1]["smoothie"].gotoAndStop(1);
			}
		}

		private function completeSmoothie(smoothie:int):void {

			smoothies[smoothie - 1]["smoothie"].gotoAndStop(3);
			smoothiesCompleted[smoothie-1] = true;
		}

		private function isSmoothieCompleted(smoothie:int):Boolean {

			return smoothiesCompleted[smoothie-1];
		}

		private function loadDiofanticPuzzle():void {

			currentPuzzle = "diofantic";
			if(contains(mixer)) {
				removeChild(mixer);
			}

			var fruit:DisplayObject;

			for(var i in diofanticFruits) {

				fruit = diofanticFruits[i];
				fruit.x = getXPosition(fruit, i);
				fruit.y = getYPosition(fruit, i);
				addChild(fruit);
			}
		}

		private function bagShakeIt(e:Event):void {

			if(mixerFruits.length == 0) {
				return;
			}

			shaked = true;

			(mixer as MovieClip)["mixer"]["on_button"].removeEventListener(MouseEvent.MOUSE_DOWN, bagShakeIt);

			for(var j in bagFruits) {

				bagFruits[j].removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);
			}

			var yellow:Boolean = false;
			var red:Boolean = false;
			var violet:Boolean = false;
			for(var i in mixerFruits) {
				var fruit:DisplayObject = mixerFruits[i];
				var props:Fruit = properties[fruit];
				
				if(props.desc.indexOf("amarillo") >= 0) {
					yellow = true;
				} else if(props.desc.indexOf("roja") >= 0) {
					red = true;
				} else {
					violet = true;
				}
				(mixer as MovieClip)["mixer"].removeChild(fruit);
				fruit.scaleX *= scale;
				fruit.scaleY *= scale;
			}

			var frame:int = 1; //none
			if(yellow && !red && !violet) {	
				frame = 2; //yellow
			} else if(yellow && red && !violet) {
				frame = 3; //orange
			} else if(!yellow && red && !violet) {
				frame = 4; //red
			} else if(yellow && red && violet) {
				frame = 5; //brown
			} else if(!yellow && !red && violet) {
				frame = 6; //violet
			} else if(yellow && !red && violet) {
				frame = 7; //violet with yellow
			} else if(!yellow && red && violet) {
				frame = 8; //violet with red
			}

			(mixer as MovieClip)["mixer"]["smoothie"].gotoAndStop(frame);

			evaluate(e);
		}

		private function colorsShakeIt(e:Event):void {

			if(mixerFruits.length == 0) {
				return;
			}

			shaked = true;

			(mixer as MovieClip)["mixer"]["on_button"].removeEventListener(MouseEvent.MOUSE_DOWN, colorsShakeIt);

			for(var j in colorsFruits) {

				colorsFruits[j].removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);
			}

			evaluate(e);
		}

		private function resetPuzzle(e:Event):void {
		
			switch(currentPuzzle) {
			case "bag":
				if(!bagCompleted) {
					unloadPuzzle();
				}
				loadBagPuzzle();
				break;
			case "colors":
				unloadPuzzle();
				
				loadColorsPuzzle();
				break;
			case "diofantic":
				if(!colorsCompleted) {
					unloadPuzzle();
				}
				loadDiofanticPuzzle();
				break;
			} 
		}

		private function unloadPuzzle():void {

			switch(currentPuzzle) {
			case "bag":
				if(!shaked) {
					for(var i in bagFruits) {
						
						if(mixerFruits.indexOf(bagFruits[i]) >= 0) {
							(mixer as MovieClip)["mixer"].removeChild(bagFruits[i]);
							bagFruits[i].scaleX *= scale;
							bagFruits[i].scaleY *= scale;
						} else {
							if(contains(bagFruits[i])) { //may not contain if finished
								removeChild(bagFruits[i]);
							}		
						}
					}
				} else {
					for(var j in bagFruits) {
						
						if(mixerFruits.indexOf(bagFruits[j]) < 0) {
							removeChild(bagFruits[j]);
						}
					}
					resetMixer();
				}

				t1.text = t2.text = t3.text = t4.text = "";
				zIndex = 3;
				mixerFruits = new Array();
				(mixer as MovieClip)["mixer"]["on_button"].removeEventListener(MouseEvent.MOUSE_DOWN, bagShakeIt);
			break;
			case "colors":
				if(!shaked) {
					for(var k in colorsFruits) {
						
						if(mixerFruits.indexOf(colorsFruits[k]) >= 0) {
							(mixer as MovieClip)["mixer"].removeChild(colorsFruits[k]);
							colorsFruits[k].scaleX *= scale;
							colorsFruits[k].scaleY *= scale;
						} else {
							if(contains(colorsFruits[k])) { //may not contain if finished
								removeChild(colorsFruits[k]);
							}		
						}
					}
				} else {
					for(var h in colorsFruits) {
						
						if(mixerFruits.indexOf(colorsFruits[h]) < 0) {
							removeChild(colorsFruits[h]);
						}
					}
					resetMixer();
				}

				for(var s in smoothies) {

					removeChild(smoothies[s]);
				}

				zIndex = 3;
				mixerFruits = new Array();
				(mixer as MovieClip)["mixer"]["on_button"].removeEventListener(MouseEvent.MOUSE_DOWN, colorsShakeIt);
			break;
			case "diofantic":
				addChild(mixer);
				for(var d in diofanticFruits) {
					removeChild(diofanticFruits[d]);
				}
				t5.text = t6.text = t7.text = "";
			}
		}

		private function resetMixer():void {
			(mixer as MovieClip)["mixer"]["smoothie"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["background"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["yellow_seeds"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["orange_seeds"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["pink_jelly"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["marveluna_stuff"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["fog"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["cursed_stars"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["stars"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["red_stars"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["black_chips"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["red_chips"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["rainbow_drops"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["green_swirls"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["green_worms"].gotoAndStop(1);
			(mixer as MovieClip)["mixer"]["color_smoothies"]["custard"].gotoAndStop(1);
			shaked = false;
		}

		public function evaluate(e:Event):Boolean {

			var fruit:DisplayObject;
			var props:Fruit;

			if(currentPuzzle == "bag") {

				var cals:int = 0;
				var vits:int = 0;
				for(var i in mixerFruits) {
					fruit = mixerFruits[i];
					props = properties[fruit];
					cals += props.cals;
					vits += props.vits;
				}
				setText(cals, vits);
				if(vits < 285 || cals > 120) {
					return false;
				}
				bagCompleted = true;
			} else if(currentPuzzle == "colors") {

				var yellow:Boolean = false;
				var orange:Boolean = false;
				var red:Boolean = false;
				var lightBlue:Boolean = false;
				var violet:Boolean = false;
				var blue:Boolean = false;
				var green:Boolean = false;
				var citric:Boolean = false;
				var alien1:Boolean = false;
				var alien2:Boolean = false;
				var alien3:Boolean = false;
				var leaf:Boolean = false;
				var nut:Boolean = false;

				for(var j in mixerFruits) {
					fruit = mixerFruits[j];
					props = properties[fruit];
					
					if(props.desc.indexOf("amarillo") >= 0) {
						yellow = true;
					}
					if(props.desc.indexOf("roja") >= 0) {
						red = true;
					}
					if(props.desc.indexOf("naranja") >= 0) {
						orange = true;
					}
					if(props.desc.indexOf("azul") >= 0) {
						blue = true;
					}
					if(props.desc.indexOf("verde") >= 0) {
						green = true;
					}
					if(props.desc.indexOf("citrico") >= 0) {
						citric = true;
					}
					if(props.desc.indexOf("hoja") >= 0) {
						leaf = true;
					}
					if(props.desc.indexOf("nuez") >= 0) {
						nut = true;
					}  
					if(props.name == "biliosa") {
						alien1 = true;
					} 
					if(props.name == "chankasar") {
						alien2 = true;
						lightBlue = true;
					} 
					if(props.name == "bolanio") {
						violet = true;
					}
					if(props.name == "marveluna") {
						alien3 = true;
					} 

					(mixer as MovieClip)["mixer"].removeChild(fruit);
					fruit.scaleX *= scale;
					fruit.scaleY *= scale;
				}

				var background:int = 1;

				if(mixerFruits.length == 7) {
					background = 14; //super brown
				} else if(5 <= mixerFruits.length) {
					background = 13; //medium brown
				}  else if(3 <= mixerFruits.length) {
					background = 12; //brown
				} else {
					var justOne:Boolean = (mixerFruits.length == 1);

					if(yellow && justOne) {
						background = 2; //yellow
					}else if(yellow && orange || yellow && red || orange && justOne) {
						background = 3; //orange
					} else if(yellow && lightBlue || yellow && green || orange && lightBlue) {
						background = 9; //lime
					} else if(yellow && violet || orange && violet) {
						background = 8; //pink
					} else if(yellow && blue || red && lightBlue || green && justOne) {
						background = 7; //green
					} else if(orange && red || red && justOne) {
						background = 4; //red
					} else if(orange && blue || red && violet || red && blue || violet && green ||
						lightBlue && violet || blue && violet || violet && justOne) {
						background = 5; //violet
					} else if(orange && green || red && green) {
						background = 12; //brown
					} else if(lightBlue && justOne) {
						background = 11; //lightBlue
					} else if(lightBlue && blue || blue && justOne) {
						background = 6; //blue
					}  else if(lightBlue && green || blue && green) {
						background = 10; //water-green
					}
				}

				var deface:int = 0;
				if(alien1 && alien2 && alien3 &&  7 > mixerFruits.length) {
					deface += 7;
				} else if(3 <= mixerFruits.length &&  7 > mixerFruits.length) {
					if(alien1 && alien2) {
						deface += 1;
					} else if(alien1 && alien3) {
						deface += 3;
					} else if(alien2 && alien3) {
						deface += 5;
					}					
				}		
				if(deface > 0) {
					background = ((background - 2 + deface) % 10) + 2;
				}

				(mixer as MovieClip)["mixer"]["color_smoothies"]["background"].gotoAndStop(background);

				if(yellow) {
					if(nut) {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["black_chips"].gotoAndStop(2);
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["yellow_seeds"].gotoAndStop(2);
					}		
				}
				if(orange) {
					if(nut) {
						if(red) {
							(mixer as MovieClip)["mixer"]["color_smoothies"]["red_chips"].gotoAndStop(2);
						} else {
							(mixer as MovieClip)["mixer"]["color_smoothies"]["black_chips"].gotoAndStop(2);
						}
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["orange_seeds"].gotoAndStop(2);
					}
				}
				if(red || lightBlue) {
					if(citric) {
						if(!leaf) {
							(mixer as MovieClip)["mixer"]["color_smoothies"]["fog"].gotoAndStop(2);
						}
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["pink_jelly"].gotoAndStop(2);	
					}					
				}
				if(blue) {
					(mixer as MovieClip)["mixer"]["color_smoothies"]["marveluna_stuff"].gotoAndStop(2);
				}
				if(red && lightBlue && yellow && !orange) {
					if(nut) {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["cursed_stars"].gotoAndStop(2);
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["stars"].gotoAndStop(2);
					}
				}
				if(red && lightBlue && orange) {
					if(nut) {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["cursed_stars"].gotoAndStop(2);
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["red_stars"].gotoAndStop(2);
					}
				}
				if(blue && green) {
					if(citric) {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["marveluna_stuff"].gotoAndStop(1);
						if(violet) {
							(mixer as MovieClip)["mixer"]["color_smoothies"]["custard"].gotoAndStop(2);
						}
						(mixer as MovieClip)["mixer"]["color_smoothies"]["green_swirls"].gotoAndStop(2);
					} else {
						(mixer as MovieClip)["mixer"]["color_smoothies"]["green_worms"].gotoAndStop(2);
					}
				}
				if(violet && blue && green && orange && red && yellow) {
					(mixer as MovieClip)["mixer"]["color_smoothies"]["red_chips"].gotoAndStop(1);
					(mixer as MovieClip)["mixer"]["color_smoothies"]["black_chips"].gotoAndStop(1);
					(mixer as MovieClip)["mixer"]["color_smoothies"]["green_swirls"].gotoAndStop(1);
					(mixer as MovieClip)["mixer"]["color_smoothies"]["custard"].gotoAndStop(1);
					(mixer as MovieClip)["mixer"]["color_smoothies"]["rainbow_drops"].gotoAndStop(2);
				}

				if(currentSmoothie == 1) {
					if(violet && blue && green && orange && red && yellow && !lightBlue) {
						completeSmoothie(1);
					}
				} else if(currentSmoothie == 2) {
					if(yellow && !orange && red && lightBlue && violet && !blue && !green) {
						completeSmoothie(2);
					}
				} else {
					if(!yellow && orange && red && !lightBlue && !violet && blue && green) {
						completeSmoothie(3);
					}
				}

				if(isSmoothieCompleted(1) && isSmoothieCompleted(2) && isSmoothieCompleted(3)) {
					colorsCompleted = true;
				}
			} else {

				var totalPoints:int = 0;
				var totalAmount:int = 0;

				for(var d in diofanticFruits) {
					
					props = properties[diofanticFruits[d]];
					var amount:int = parseInt(diofanticFruits[d][props.name.replace(/_jam/, "")+"_amount"].text, 10);
					totalPoints += amount * props.points;
					totalAmount += amount;
				}
				printPoints(totalPoints);
				
				if(totalPoints == 30000 && totalAmount <= 30) {
					for(var t in diofanticFruits) {
					
						props = properties[diofanticFruits[t]];
						diofanticFruits[t][props.name.replace(/_jam/, "")+"_amount"].type = TextFieldType.DYNAMIC;
					}
					diofanticCompleted = true;
				}
			}

			if(bagCompleted && colorsCompleted && diofanticCompleted) {
				complete();	
				return true;
			}
			return false;
		}

		public function complete():void {
			if(!allCompleted) {
				trace("Shake is completed!");
				allCompleted = true;
				DatabaseManager.complete("easter2015", "shake");
			}
		}

		private function addFruit(puzzle:String, name:String, property:Fruit, index:int):void {

			var fruit:DisplayObject = Factory.get(name);
			fruit.scaleX *= scale;
			fruit.scaleY *= scale;		

			switch(puzzle)
			{
			case "bag":
				if((index+1) % 3 == 0) {
					fruit.scaleX *= -1;
				}
				bagFruits.push(fruit);	
				break;
			case "colors":
				colorsFruits.push(fruit);
				break;
			case "diofantic":
				diofanticFruits.push(fruit);
				fruit[name.replace(/_jam/, "")+"_amount"].addEventListener(KeyboardEvent.KEY_UP, evaluate);
				break;
			}

			properties[fruit] = property;
		}

		private function getXPosition(fruit:DisplayObject, index:int):int {

			switch(currentPuzzle) {
			case "bag":
				return bagPositions[index][0];
			case "colors":
				return colorsPositions[index][0];
			case "diofantic":
				return 150 + 150*index;
			}
			return 0;
		}

		private function getYPosition(fruit:DisplayObject, index:int):int {

			switch(currentPuzzle) {
			case "bag":
				return bagPositions[index][1];
			case "colors":
				return colorsPositions[index][1];
			case "diofantic":
				return 240;
			}
			return 0;
		}

		private function setText(cals:int, vits:int):void {

			var color:int;
			if(cals <= 120) {
				color = 0x4FA912;
			} else {
				color = 0xFD4040;
			}
			var textFormat:TextFormat = new TextFormat();
			textFormat.size = 20;
			textFormat.align = TextFormatAlign.CENTER;
			textFormat.font = "Hobo Std";
			textFormat.letterSpacing = 1;

			t1.text = "Calorías: ";
			t2.text = "" + cals;
			t3.text = "/120";
			t4.text = "Vitaminas: " + vits;

			t1.setTextFormat(textFormat);
			t2.setTextFormat(textFormat);
			t3.setTextFormat(textFormat);
			t4.setTextFormat(textFormat);

			t1.textColor = t3.textColor = t4.textColor = 0x000000;
			t2.textColor = color;
		}

		private function printPoints(points:int):void {

			var color:int;
			if(points < 20000 || points > 40000) {
				color = 0xFD4040; // red	
			} else if(points < 25000 || points > 35000) {
				color = 0xDE7D12; //orange
			} else if(points < 29000 || points > 31000) {
				color = 0xDFC611; //yellow
			} else if(points < 30000 || points > 30000) {
				color = 0xB1C910; //light-green
			} else {
				color = 0x4FA912; //green
			}	
			var textFormat:TextFormat = new TextFormat();
			textFormat.size = 20;
			textFormat.align = TextFormatAlign.CENTER;
			textFormat.font = "Hobo Std";
			textFormat.letterSpacing = 1;

			t5.text = "Puntos: ";
			t6.text = "" + points;
			t7.text = "/30000";

			t5.setTextFormat(textFormat);
			t6.setTextFormat(textFormat);
			t7.setTextFormat(textFormat);

			t5.textColor = t7.textColor = 0x000000;
			t6.textColor = color;
		}

		private function pickUp(event:MouseEvent):void {
			
			dragged = event.target as MovieClip;
			
			dragged.startDrag(false);
			startX = dragged.x;
		    startY = dragged.y;  
		    bringToFront(dragged.parent);
		}

		private function dropIt(event:MouseEvent):void {

			if(dragged == null) {
				return;
			} 

			dragged.stopDrag();

			if(dragged.hitTestObject(mixer)) {
				var fruit:DisplayObject = dragged.parent;
				removeChild(fruit);

				fruit.scaleX /= scale;
				fruit.scaleY /= scale;
				
				var position:Array = getMixerPosition(mixerPositions[properties[fruit].name]);
				fruit.x = position[0];
				fruit.y = position[1];
				fruit.removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);

				mixerFruits.push(fruit);
				(mixer as MovieClip)["mixer"].addChildAt(fruit, zIndex++);
			}

		    dragged.x = startX;
		    dragged.y = startY;
		    dragged = null;
		}

		private function getMixerPosition(range:Array):Array {

			var x:Number = Random.randomDoubleRange(range[0], range[2]);
			var y:Number = Random.randomDoubleRange(range[1], range[3]);
			return new Array(x, y);
		}

		private function bringToFront(mcl:DisplayObject):void {
			
			mcl.parent.setChildIndex(mcl,mcl.parent.numChildren - 1);
		}

		private function initializeGrid() {

			stage.addChild(loader.getContent("_grid"));
		}
	}
}
