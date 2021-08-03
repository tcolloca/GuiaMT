package items {
	
	import flash.display.*;
	import flash.text.*;
	import flash.events.*;
	import utils.*;
	import popups.*;
	
	public class Item extends PopUpOpener {

		private var objName:String;
		private var handler:Function;

		public function Item(objName:String, objMessage:String, handler:Function = null) {

			this.objName = objName;
			this.handler = handler;
			super(setPopUp(objMessage));
		}

		private function setPopUp(objMessage:String):PopUp {

			var objImg:DisplayObject = Factory.get(objName);
			objImg.x = 350 - objImg.width/2;
			objImg.y = 200 - objImg.height/2;

			var textFormat:TextFormat = new TextFormat();
			textFormat.size = 12;
			textFormat.align = TextFormatAlign.CENTER;
			textFormat.font = "Hobo Std";
			textFormat.letterSpacing = 1;

			var textMessage:TextField = new TextField();
			textMessage.text = objMessage;
			textMessage.textColor = 0xFFFFFF;
			textMessage.setTextFormat(textFormat);
			textMessage.width = 350;
			textMessage.height = 100;
			textMessage.x = 350 - textMessage.width/2;
			textMessage.y = objImg.y + objImg.height + 20;
			textMessage.wordWrap = true;

			var popUp:PopUp = new PopUp();
			popUp.addChild(objImg);
			popUp.addChild(textMessage);

			return popUp;
		}

		override public function openPopUp(e:MouseEvent):void {
			
			super.openPopUp(e);
			DatabaseManager.getObject("easter2015", objName);	
			//stage.remove(this);
		}
		
		override public function closePopUp(e:MouseEvent):void {
			
			super.closePopUp(e);
			this.parent.removeChild(this);
		}

		public function get _name():String {

			return objName;
		}
	}
	
}