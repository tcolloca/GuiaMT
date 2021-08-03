package utils {
	
	import flash.display.*;
	import flash.events.*;
	import items.*;
	import popups.*;
	
	public class Container extends PopUp {

		private var popUpOpener:PopUpOpener;

		public function addPopUpOpener(popUpOpener:PopUpOpener):void {

			this.popUpOpener = popUpOpener;
		}

		public function addItem(specialItem:Item, x:Number = 0, y:Number = 0, z:Number = 0):void {

			specialItem.addEventListener(MouseEvent.CLICK, closePopUp);
		}

		private function closePopUp(e:MouseEvent):void {

			popUpOpener.closePopUp(e);
		}
	}
	
}