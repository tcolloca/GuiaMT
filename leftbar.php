<div id="sidebar"> 
			<script>
              (function() {
                var cx = '017539557686675255550:8v0wq9gftpm';
                var gcse = document.createElement('script');
                gcse.type = 'text/javascript';
                gcse.async = true;
                gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                    '//www.google.com/cse/cse.js?cx=' + cx;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(gcse, s);
              })();
            </script>
            <gcse:search></gcse:search>
			<ul>
            	<li class="side-category">Secciones</li>
<?php	
	$barIds = array('tu_cuenta', 'personal', 'proximamente', 'sumate', 'eventos_anteriores');
	$barNames = array('Tu cuenta', 'Personal', 'Proximamente', 'Sumate', 'Eventos');
	
	for($i=0;$i<=count($barIds)-1;$i++)
		echo "<li><div class='button'><a href='/index.php?id=".$barIds[$i]."'>".$barNames[$i]."</a></div></li>";				
?>
				<li><div class='button'><a href='/distinciones'>Distinciones</a></div></li>
                <li><div class='button'><a href='http://client00.chat.mibbit.com/?channel=%23GuiaMT&server=irc.mibbit.net'>Chat</a></div></li>
			</ul>
            <pre>	</pre>
            <ul>
				<li><div class="side-category">Páginas amigas</div></li>
				<li><img src='https://sites.google.com/site/zombiearchivos/_/rsrc/1334119399772/home/libro.gif' title="Dinoarchivos" alt="Dinoarchivos" /> <a href="https://sites.google.com/site/dinoarchivos/" style="text-decoration: none;"><b class="mini-text">Dinoarchivos</b></a>
                </li>
                <li><img src='https://sites.google.com/site/dinoarchivos/_/rsrc/1334270670066/home/zombie.png' title="Zombiearchivos" alt="Zombiearchivos" /> <a href="https://sites.google.com/site/zombiearchivos/home" style="text-decoration: none;"><b class="mini-text">Zombiearchivos</b></a>
                </li> 
                <li><img src='https://sites.google.com/site/quarantinefiles/_/rsrc/1410788266890/home/favicon.ico.png' title="Zerofiles" alt="Zerofiles" /> <a href="https://sites.google.com/site/quarantinefiles/" style="text-decoration: none;"><b class="mini-text">ZeroFiles</b></a>
                </li> 
                   
			</ul>
            <pre>	</pre>
            <ul>
            	<li class="side-category">Contador</li>
                <li>
                	<p><img style="border: 0px solid ; display: inline;" alt="counter" 
	src="http://contador-de-visitas.com/hit.php?id=1846633&amp;counter=2"></p>
					<p class="mini-text"><b>Desde el 5 de <br />Octubre del 2013</b></p>
				</li>
            </ul>
</div>