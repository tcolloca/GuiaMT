package screener {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	
	public class ScreenArrow extends MovieClip {

		private static const SPEED:Number = 7;
		private static const LEFTLIMIT:Number = 361;
		private static const RIGHTLIMIT:Number = -361;
		
		private var roomContent:MovieClip;
		private var dir:Number;

		public function ScreenArrow(roomContent:MovieClip, dirName:String) {
			
			this.roomContent = roomContent;
			
			switch(dirName) {
				case "left":
					dir = -1;
					break;
				default:
					dir = 1;
			}
			
			addEventListener(MouseEvent.MOUSE_DOWN, mouseDown);
		}
		
		function mouseDown(e:Event):void {
			stage.addEventListener(MouseEvent.MOUSE_UP, mouseUp);
			addEventListener(Event.ENTER_FRAME, moveRoom);
		}
		
		function mouseUp(e:Event):void {
			removeEventListener(Event.ENTER_FRAME, moveRoom);
			stage.removeEventListener(MouseEvent.MOUSE_UP, mouseUp);
		}

		private function moveRoom(e:Event):void {
           
			if(!GlobalVars.isBlocked) {
				if(roomContent.x > LEFTLIMIT) {
					roomContent.x -= SPEED;
				}
				else if(roomContent.x < RIGHTLIMIT) {
					roomContent.x += SPEED;
				}
				else {
					roomContent.x -= dir*SPEED;
				}
			}
		}
	}
}

