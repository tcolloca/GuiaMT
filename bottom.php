<div id="bottom">
	<div id="copyright" class="mini-text">
        <p>COPYRIGHT © 2013 MOTION-TWIN - ALL RIGHTS RESERVED.
    Todo contenido que no contiene imágenes de Motion-Twin pertenecen a © GuiaMT 2014. 
    No está permitida la copia del contenido en otras páginas sin previo consenso.</p>
    </div>
	<div id="support-links">
    	<p><a href="/index.php?id=faq">Preguntas Frecuentes</a> |
        <a href="/index.php?id=quienes_somos">Quiénes somos</a> |
         <a href="/index.php?id=contacto">Contacto</a> |        
         <a href="/index.php?id=reporta_un_error">Reporta un error</a> |
         <a href="/index.php?id=privacy_policy">Términos y Condiciones</a></p>
    </div>
</div>

<script>
	
	$(document).ready( function(){			
		var $content = document.getElementById('content').clientHeight;
		var $sidebar = document.getElementById('sidebar').clientHeight;
			
		if( $content > $sidebar ) {
			document.getElementById('sidebar').setAttribute('style', 'height: ' + $content + 'px');
		} else {
			document.getElementById('content').setAttribute('style', 'height: ' + $sidebar + 'px');
		}
	});
	
	$(function() {
    function imageLoaded() {

       counter--;   	
		var $content = document.getElementById('content').offsetHeight;
		var $sidebar = document.getElementById('sidebar').offsetHeight;
			
		if( $content > $sidebar ) {
			document.getElementById('sidebar').setAttribute('style', 'height: ' + $content + 'px');
		}
    }
    var images = $('img');
    var counter = images.length;  // initialize the counter

    images.each(function() {
        if( this.complete ) {
            imageLoaded.call( this );
        } else {
            $(this).one('load', imageLoaded);
        }
    });
});
		
</script>
