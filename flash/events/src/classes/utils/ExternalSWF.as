package utils {
	
	import flash.errors.*;
	import flash.display.*;
	import flash.events.*;
	import utils.*;
	import br.com.stimuli.loading.*;
	
	public interface ExternalSWF {

		function addLoader(loader:BulkLoader):void;

		function finishedLoading(e:BulkProgressEvent):void
	}
}