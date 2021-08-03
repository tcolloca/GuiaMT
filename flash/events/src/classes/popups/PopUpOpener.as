package popups {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	
	public class PopUpOpener extends MovieClip {

		protected var blackscreen:DisplayObject;
		protected var esc:DisplayObject;
		protected var popUp:PopUp;
		protected var condition:Condition;
		protected var stageRef:Stage;

		public function PopUpOpener(popUp:PopUp, condition:Condition = null) {
			
			this.popUp = popUp;
			this.blackscreen = Factory.get("blackscreen");
			this.esc = Factory.get("esc");
			esc.x = 655;
			esc.y = 20;

			addEventListener(MouseEvent.CLICK, openPopUp);
			esc.addEventListener(MouseEvent.CLICK, closePopUp);
		}

		public function openPopUp(e:MouseEvent):void {
			
			if(condition == null || condition.evaluate()) {
				GlobalVars.isBlocked = true;
				this.stageRef = stage;

				stageRef.addChildAt(blackscreen, 2);
				stageRef.addChildAt(popUp, 3);	
				stageRef.addChildAt(esc, 4);	
			}	
		}
		
		public function closePopUp(e:MouseEvent):void {
			
			stageRef.removeChild(esc);
			stageRef.removeChild(popUp);
			stageRef.removeChild(blackscreen);	

			GlobalVars.isBlocked = false;	
		}
	}
	
}
