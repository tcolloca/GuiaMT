package utils.drag {
	
	import flash.display.*;
	import flash.events.*;
	import popups.*;

	public class DragContainer extends PopUp {

		private var dragged:utils.drag.Draggable;

		public function DragContainer():void {

			addEventListener(MouseEvent.MOUSE_MOVE, onMove);
		}
		
		public function startObjDrag(dragged:utils.drag.Draggable):void {

			this.dragged = dragged;
		}

		public function stopObjDrag():void {
			
			dragged = null;
		}

		public function somethingIsDragged():Boolean {
			
			return dragged != null;
		}

		public function onMove(e:MouseEvent):void {

			if(dragged != null && !dragged.canMove(dragged.x, dragged.y)) {

				dragged.stopObjDrag(null);
			}
		}

		public function add(obj:DisplayObject):void {

			addChild(obj);
		}
	}
}