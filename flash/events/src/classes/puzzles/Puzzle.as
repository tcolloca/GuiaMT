package puzzles {
	
	import flash.errors.*;
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	
	public interface Puzzle {

		function evaluate(e:Event):Boolean;

		function complete( ):void; 
	}
	
}