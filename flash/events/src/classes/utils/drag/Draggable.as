package utils.drag {
	
	import flash.display.*;
	import flash.events.*;

	public class Draggable extends MovieClip {

		private var container:DragContainer;
		private var up:Number;
		private var right:Number;
		private var down:Number;
		private var left:Number;

	
		public function Draggable() {

			addEventListener(MouseEvent.MOUSE_DOWN, startObjDrag);
			addEventListener(MouseEvent.MOUSE_UP, stopObjDrag);
		}

		public function setContainer(container:DragContainer):void {

			this.container = container;
		}

		public function setLimits(up:Number, right:Number = -1, down:Number = -1, left:Number = -1) {
		
			this.up = up;
			this.right = right;
			this.down = down;
			this.left = left;
		}

		private function startObjDrag(e:MouseEvent):void {
			
			if(container != null && !container.somethingIsDragged()) {
				container.startObjDrag(this);
				startDrag();
			}
		}
	
		public function stopObjDrag(e:MouseEvent):void {
			
			container.stopObjDrag();
		
			stopDrag();
		}

		public function canMove(x:Number, y:Number):Boolean {


			if(right != -1 && down != -1 && left != -1) {			
				if(y > down || y < up) {
				
					return false;
				}

				if(x < left || x > right) {
					
					return false;
				}

				return true;
			} else {
				var radius:Number = up;
				var xPos:Number = Math.abs(x) + this.width/2;
				var yPos:Number = Math.abs(y) + this.height/2;

				if(xPos*xPos + yPos*yPos > radius*radius) {

					return false;
				}
				return true;
			}
			
			return false;
		}
	}
}