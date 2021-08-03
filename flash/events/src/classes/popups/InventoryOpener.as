package popups {
	
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	
	public class InventoryOpener extends PopUpOpener {

		private var cols:Number;
		private var rows:Number;
		private var spacing:Number;
		private var itemSize:Number;
		private var dragged:DisplayObject;
		private var isOpen:Boolean;

		public function InventoryOpener(popUp:PopUp, condition:Condition = null, cols:int = 9, rows:int = 5, 
				spacing:int = 10, itemSize:int = 60) {
			
			super(popUp, condition);

			this.cols = cols;
			this.rows = rows;
			this.spacing = spacing;
			this.itemSize = itemSize;
		}

		override public function openPopUp(e:MouseEvent):void {

			if(isOpen) {
				closePopUp(e);
				return;
			}

			if(GlobalVars.isBlocked) { //Another puzzle is open
				return;
			}

			isOpen = true;
			GlobalVars.isBlocked = true;
			this.stageRef = stage;
			
			stageRef.addChildAt(popUp, 2);

			addItems(3);	
		}
		
		override public function closePopUp(e:MouseEvent):void {
			
			removeItems();

			stageRef.removeChild(popUp);

			GlobalVars.isBlocked = false;
			isOpen = false;	
		}

		private function addItems(k:Number):void {

			var inv:Inventory = GlobalVars.inventory;

			for(var i:Number = 0; i < inv.size; i++) {

				if(inv.getItem(i)) {

					positionItem(Factory.get(inv.getItem(i)), i, k++);
					Factory.get(inv.getItem(i)).addEventListener(MouseEvent.MOUSE_DOWN, pickUp);
					Factory.get(inv.getItem(i)).addEventListener(MouseEvent.MOUSE_UP, dropIt);
				}
			}
		}

		private function positionItem(item:DisplayObject, i:Number, k:Number):void {

			var startX:Number = 40;
			var startY:Number = 25;
			var col:Number = i % cols;
			var row:int = (int)(i / cols);

			item.x = startX + (itemSize + spacing)*col + itemSize/2;
			item.y = startY + (itemSize + spacing)*row + itemSize/2;

			item.scaleX = item.scaleY *= itemSize/100;

			stageRef.addChildAt(item, k);
		}

		private function removeItems():void {

			var inv:Inventory = GlobalVars.inventory;

			for(var i:Number = 0; i < inv.size; i++) {

				if(inv.getItem(i)) {
			
					var it:DisplayObject = Factory.get(inv.getItem(i));
					
					it.removeEventListener(MouseEvent.MOUSE_DOWN, pickUp);		
					
					if(GlobalVars.dragged == null || (it != GlobalVars.dragged.parent)) {				
						it.scaleX = it.scaleY /= itemSize/100;
						it.removeEventListener(MouseEvent.MOUSE_UP, dropIt);
						stageRef.removeChild(it);
					}	
				}
			}
		}

		private function pickUp(event:MouseEvent):void {
			
			GlobalVars.dragged = event.target as MovieClip;
			
			GlobalVars.dragged.startDrag(false);
			GlobalVars.startX = GlobalVars.dragged.x;
		    GlobalVars.startY = GlobalVars.dragged.y;  
		    bringToFront(GlobalVars.dragged.parent);

			GlobalVars.dragged.parent.scaleX =  GlobalVars.dragged.parent.scaleY /= itemSize/100;

			closePopUp(null);
		}

		private function bringToFront(mcl:DisplayObject):void {
			
			mcl.parent.setChildIndex(mcl,mcl.parent.numChildren - 1);
		}

		private function dropIt(e:MouseEvent):void {

			var dragged:MovieClip = GlobalVars.dragged;

			if(dragged == null) {
				return;
			} 

			dragged.stopDrag();
			dragged.parent.removeEventListener(MouseEvent.MOUSE_UP, dropIt);
			
			stageRef.removeChild(dragged.parent);

			if(dragged.hitTestObject(GlobalVars.itemsMap[dragged.parent][1])) {

				trace(GlobalVars.itemsMap[dragged.parent][2]);
				GlobalVars.itemsMap[dragged.parent][2].addItem(GlobalVars.itemsMap[dragged.parent][0]);
				DatabaseManager.useObject("easter2015", GlobalVars.itemsMap[dragged.parent][0]);
				GlobalVars.inventory.removeItem(GlobalVars.itemsMap[dragged.parent][0]);
			}

		    dragged.x = GlobalVars.startX;
		    dragged.y = GlobalVars.startY;

		   	GlobalVars.dragged = null;
		}
	}
	
}
