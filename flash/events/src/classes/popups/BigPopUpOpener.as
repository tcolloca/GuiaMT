package popups {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	
	public class BigPopUpOpener extends PopUpOpener {

		public function BigPopUpOpener(popUp:PopUp, condition:Condition = null, escX:int = 665, escY:int = 10) {
			
			super(popUp, condition);

			esc.x = escX;
			esc.y = escY;
		}

		override public function openPopUp(e:MouseEvent):void {

    		if(condition == null || condition.evaluate()) {
				GlobalVars.isBlocked = true;
				this.stageRef = stage;

				stageRef.addChildAt(popUp, 3);	
				stageRef.addChildAt(esc, 4);	
			}	
		}
		
		override public function closePopUp(e:MouseEvent):void {
			
			stageRef.removeChild(esc);
			stageRef.removeChild(popUp);	

			GlobalVars.isBlocked = false;	
		}
	}
}