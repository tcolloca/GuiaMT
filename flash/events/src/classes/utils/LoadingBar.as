package utils {
	
	import flash.display.*;
	
	public class LoadingBar extends MovieClip {

		public function LoadingBar() {
			
			setPercentage(0);
		}

		public function setPercentage(percentage:Number):void {
			
			this.gotoAndStop(percentage);
			this.loadingText.text = percentage + "%";
		}
	}
}