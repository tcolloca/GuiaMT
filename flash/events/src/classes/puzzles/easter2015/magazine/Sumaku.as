package puzzles.easter2015.magazine {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	import puzzles.*;
	import events.*;

	public class Sumaku extends MovieClip implements Puzzle {

		private var puzzleContent:MovieClip;
		private var checkMark:CheckMark;

		public function Sumaku(puzzleContent:MovieClip, checkMark:CheckMark, isCompleted:Boolean) {

			this.checkMark = checkMark;
			addEventListener(KeyboardEvent.KEY_UP, evaluate);

			this.puzzleContent = puzzleContent;

			addChild(puzzleContent);

			if(isCompleted) {
				complete( );
			}
		}

		public function evaluate(e:Event):Boolean {
			
			var isCompleted:Boolean = true;
			var solutions:Array = new Array( 1, 2, 8, 6, 5, 9, 7, 8, 6, 3,
				1, 3, 4, 2, 1, 3, 5, 2, 1 );

			for(var i:Number = 0; i < 1 /*TODO: 19*/; i++) {
				
				if(puzzleContent["s" + (i + 1)].text != solutions[i]){
					//trace("Value is: " + puzzleContent["s" + (i + 1)].text + ", should be: " + solutions[i]);
					isCompleted = false;
				}
			}

			if(isCompleted == true) {	
				complete( );	
			}

			return isCompleted;
		}

		public function complete( ):void {

			checkMark.check();
			removeEventListener(KeyboardEvent.KEY_UP, evaluate);
			dispatchEvent(new MagazinePuzzleEvent(MagazinePuzzleEvent.COMPLETE, "sumaku"));
		}
	}
}