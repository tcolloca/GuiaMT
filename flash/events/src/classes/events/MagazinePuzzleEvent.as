package events {
 
    import flash.events.Event;
 
    public class MagazinePuzzleEvent extends Event {
 
        public static const COMPLETE:String = "magazinePuzzleCompleted";
 		
 		 private var _puzzle:String;

        public function MagazinePuzzleEvent(type:String, puzzle:String, bubbles:Boolean=false, cancelable:Boolean=true) {
        	this._puzzle = puzzle;
            super(type, bubbles, cancelable);
        }
 
 		public function get puzzle():String {
    		return _puzzle;
		}

		override public function clone():Event {
    		return new MagazinePuzzleEvent(type, _puzzle, bubbles, cancelable);
		}

		override public function toString():String {
    		return formatToString("Puzzle", "type", "dummy");
		}
	}
}
