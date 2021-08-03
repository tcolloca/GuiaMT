package puzzles.easter2015.magazine {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	import puzzles.*;
	import events.*;

	public class Hexaku extends MovieClip implements Puzzle {

		private var puzzleContent:MovieClip;
		private var checkMark:CheckMark;

		public function Hexaku(puzzleContent:MovieClip, checkMark:CheckMark, isCompleted:Boolean) {

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
			var solutions:Array = new Array( 2, 1, 7, 4, 7, 3, 5, 3, 5, 2, 2, 7, 
										1, 4, 6, 4, 6, 3, 6, 3, 5, 2, 5, 7, 4 );
		
			for( var i:Number = 0; i < 1/*TODO: 25*/; i++ ) {
				
				if(puzzleContent["c" + (i + 1)].text != solutions[i]) {
					//trace("Value is: " + puzzleContent["c" + (i + 1)].text + ", should be: " + solutions[i]);
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
			dispatchEvent(new MagazinePuzzleEvent(MagazinePuzzleEvent.COMPLETE, "hexaku"));
		}
	}
}