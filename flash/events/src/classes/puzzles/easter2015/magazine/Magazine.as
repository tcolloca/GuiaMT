package puzzles.easter2015.magazine {
	
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

	public class Magazine extends PopUp implements Puzzle, ExternalSWF {

		public static const ROOT:String = Constants.ROOT; 
		public static const TESTING:Boolean = Constants.TESTING;
		private var loader:BulkLoader;

		private var hexakuInstructions:MovieClip;
		private var hexakuCheckMark:CheckMark;
		private var hexaku:MovieClip;

		private var rudokuInstructions:MovieClip;
		private var rudokuCheckMark:CheckMark;
		private var rudoku:MovieClip;

		private var sumakuInstructions:MovieClip;
		private var sumakuCheckMark:CheckMark;
		private var sumaku:MovieClip;

		private var prevPuzzleArrow:MovieClip;
		private var nextPuzzleArrow:MovieClip;

		private var prevPuzzleFun:Function;
		private var nextPuzzleFun:Function;

		private var currentPuzzle:String = null;

		private var hexakuCompleted:Boolean = false;
		private var rudokuCompleted:Boolean = false;
		private var sumakuCompleted:Boolean = false;

		public function Magazine() {

			if(TESTING) {
				addLoader(new BulkLoader("magazine-loader"));
				loader.addEventListener(BulkLoader.COMPLETE, finishedLoading);
				loadMagazine();
				loader.start();
			}
		}

		public function addLoader(loader:BulkLoader):void {

			this.loader = loader;
		}

		public function loadMagazine():void {

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

		public function evaluate(e:Event):Boolean {

			switch((e as MagazinePuzzleEvent).puzzle) {
				case "hexaku":
					hexakuCompleted = true;
					break;
				case "rudoku":
					rudokuCompleted = true;
					break;
				case "sumaku":
					sumakuCompleted = true;
					break;
			}

			if(hexakuCompleted && rudokuCompleted && sumakuCompleted) {
				complete();
				return true;
			}

			return false;
		}

		public function complete( ):void {

			trace("magazine completed!");
			DatabaseManager.complete("easter2015", "magazine");
		}
		
		public function finishedLoading(e:BulkProgressEvent):void {

			initializeBackground();
			initializeContent();	

			hexaku.addEventListener(MagazinePuzzleEvent.COMPLETE, evaluate);	
			rudoku.addEventListener(MagazinePuzzleEvent.COMPLETE, evaluate);
			sumaku.addEventListener(MagazinePuzzleEvent.COMPLETE, evaluate);	
		}
		
		private function initializeBackground():void {
			
			addChildAt(loader.getContent("magazine_background"), 0);
		}
		
		private function initializeContent():void {	
			
			var isCompleted:Boolean = Parser.parse(loader.getContent("hasCompleted_magazine")).ret == "true";

			//trace("magazine is completed?"+isCompleted);
			createHexaku(isCompleted);
			createRudoku(isCompleted);
			createSumaku(isCompleted);
			createLeftArrow();
			createRightArrow();

			loadHexaku(null);
		}

		private function createHexaku(isCompleted:Boolean):void {

			hexakuInstructions = loader.getContent("hexaku_instructions");
			hexakuInstructions.x = 30;
			hexakuInstructions.y = 20;

			hexakuCheckMark = new CheckMark(loader.getContent("hexaku_check_mark"));
			hexakuCheckMark.x = 560;
			hexakuCheckMark.y = 25;

			hexaku = new Hexaku(loader.getContent("hexaku"), hexakuCheckMark, isCompleted);
			hexaku.x = 345;
			hexaku.y = 40;
		}

		private function createRudoku(isCompleted:Boolean):void {

			rudokuInstructions = loader.getContent("rudoku_instructions");
			rudokuInstructions.x = 30;
			rudokuInstructions.y = 20;

			rudokuCheckMark = new CheckMark(loader.getContent("rudoku_check_mark"));
			rudokuCheckMark.x = 560;
			rudokuCheckMark.y = 25;

			rudoku = new Rudoku(loader.getContent("rudoku"), rudokuCheckMark, isCompleted);
			rudoku.x = 330;
			rudoku.y = 40;
		}

		private function createSumaku(isCompleted:Boolean):void {

			sumakuInstructions = loader.getContent("sumaku_instructions");
			sumakuInstructions.x = 30;
			sumakuInstructions.y = 15;

			sumakuCheckMark = new CheckMark(loader.getContent("sumaku_check_mark"));
			sumakuCheckMark.x = 560;
			sumakuCheckMark.y = 25;

			sumaku = new Sumaku(loader.getContent("sumaku"), sumakuCheckMark, isCompleted);
			sumaku.x = 345;
			sumaku.y = 70;
		}

		private function createLeftArrow() {

			prevPuzzleArrow = loader.getContent("magazine_small_left_arrow");
			prevPuzzleArrow.x = 380;
			prevPuzzleArrow.y = 305;
			prevPuzzleArrow.scaleX *= -1;
		}

		private function createRightArrow() {

			nextPuzzleArrow = loader.getContent("magazine_small_right_arrow");
			nextPuzzleArrow.x = 560;
			nextPuzzleArrow.y = 305;
		}

		private function loadHexaku(e:MouseEvent):void {

			unloadPrevPuzzle();
			currentPuzzle = "Hexaku";

			addChild(hexakuInstructions);
			addChild(hexakuCheckMark);
			addChild(hexaku);

			loadRightArrow(loadRudoku);
		}

		private function loadRudoku(e:MouseEvent):void {

			if(e != null)
				e.currentTarget.removeEventListener(e.type, loadRudoku);
			unloadPrevPuzzle();
			currentPuzzle = "Rudoku";

			loadLeftArrow(loadHexaku);
		
			addChild(rudokuInstructions);			
			addChild(rudokuCheckMark);
			addChild(rudoku);

			loadRightArrow(loadSumaku);
		}

		private function loadSumaku(e:MouseEvent):void {

			if(e != null)
				e.currentTarget.removeEventListener(e.type, loadSumaku);
			unloadPrevPuzzle();
			currentPuzzle = "Sumaku";

			loadLeftArrow(loadRudoku);	
			
			addChild(sumakuInstructions);
			addChild(sumakuCheckMark);	
			addChild(sumaku);
		}

		private function loadLeftArrow(puzzleLoader:Function):void {

			if(prevPuzzleFun != null)
				prevPuzzleArrow.removeEventListener(MouseEvent.CLICK, prevPuzzleFun);
			prevPuzzleArrow.addEventListener(MouseEvent.CLICK, puzzleLoader);
			prevPuzzleFun = puzzleLoader;
			addChild(prevPuzzleArrow);
		}

		private function loadRightArrow(puzzleLoader:Function):void {
			
			if(nextPuzzleFun != null)
			nextPuzzleArrow.removeEventListener(MouseEvent.CLICK, nextPuzzleFun);
			nextPuzzleArrow.addEventListener(MouseEvent.CLICK, puzzleLoader);
			nextPuzzleFun = puzzleLoader;
			addChild(nextPuzzleArrow);
		}

		private function unloadPrevPuzzle():void {

			switch(currentPuzzle) {

				case "Hexaku":
					removeChild(hexakuInstructions);
					removeChild(hexakuCheckMark);
					removeChild(hexaku);
					removeChild(nextPuzzleArrow);
					break;
				case "Rudoku":
					removeChild(rudokuInstructions);
					removeChild(rudokuCheckMark);
					removeChild(rudoku);
					removeChild(prevPuzzleArrow);
					removeChild(nextPuzzleArrow);
					break;
				case "Sumaku":
					removeChild(sumakuInstructions);
					removeChild(sumakuCheckMark);
					removeChild(sumaku);
					removeChild(prevPuzzleArrow);
					break;
			}
		}
	}
}
