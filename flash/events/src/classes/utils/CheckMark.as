package utils {
	
	import flash.display.*;
	
	public class CheckMark extends MovieClip {

		private var markContent:MovieClip;
		
		public function CheckMark(markContent:MovieClip) {

			this.markContent = markContent;
			addChild(markContent);
		}

		public function check():void {

			markContent.play();
		}
	}
	
}