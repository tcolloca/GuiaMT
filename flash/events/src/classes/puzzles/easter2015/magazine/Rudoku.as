package puzzles.easter2015.magazine {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	import puzzles.*;
	import events.*;

	public class Rudoku extends MovieClip implements Puzzle {

		private var puzzleContent:MovieClip;
		private var checkMark:CheckMark;

		public function Rudoku(puzzleContent:MovieClip, checkMark:CheckMark, isCompleted:Boolean) {

			this.checkMark = checkMark;
			addEventListener(KeyboardEvent.KEY_UP, evaluate);

			this.puzzleContent = puzzleContent;

			addChild(puzzleContent);

			if(isCompleted) {
				complete();
			}
		}

		public function evaluate(e:Event):Boolean {
			
			var isCompleted:Boolean = true;
			var solutions:Array = new Array( 2, 5, 4, 1, 6, 1, 2, 6, 5, 6, 1, 2, 3, 4,
										6, 3, 5, 4, 2, 3, 4, 6, 1, 4, 5, 1, 2, 3 );
			
			for(var i:Number = 0; i < 1 /*TODO: 28*/; i++) {
				
				if(puzzleContent["b" + (i + 1)].txt.text != solutions[i]) {
					trace("Value is: " + puzzleContent["b" + (i + 1)].txt.text + ", should be: " + solutions[i]);
					isCompleted = false;
				}
			}

			if(isCompleted == true) {			
				complete();	
			}

			return isCompleted;
		}

		public function complete( ):void {

			checkMark.check();
			removeEventListener(KeyboardEvent.KEY_UP, evaluate);
			dispatchEvent(new MagazinePuzzleEvent(MagazinePuzzleEvent.COMPLETE, "rudoku"));
		}
	}
}