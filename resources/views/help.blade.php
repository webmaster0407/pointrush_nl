@extends('layouts.app')

@section('content')
<!--Begin Comm100 Live Chat Code-->
<div id="comm100-button-c705cbec-ab9b-4026-b343-c5dde344c0ad"></div>
<script type="text/javascript">
  var Comm100API=Comm100API||{};(function(t){function e(e){var a=document.createElement("script"),c=document.getElementsByTagName("script")[0];a.type="text/javascript",a.async=!0,a.src=e+t.site_id,c.parentNode.insertBefore(a,c)}t.chat_buttons=t.chat_buttons||[],t.chat_buttons.push({code_plan:"c705cbec-ab9b-4026-b343-c5dde344c0ad",div_id:"comm100-button-c705cbec-ab9b-4026-b343-c5dde344c0ad"}),t.site_id=50100085,t.main_code_plan="c705cbec-ab9b-4026-b343-c5dde344c0ad",e("https://vue.comm100.com/livechat.ashx?siteId="),setTimeout(function(){t.loaded||e("https://standby.comm100vue.com/livechat.ashx?siteId=")},5e3)})(Comm100API||{})
</script>
<!--End Comm100 Live Chat Code-->

<div class="container">
<h1><strong>Inhoud</strong></h1>
<ul>
<li><a href="#_Toc36319679">Een Waypoint of Radius toevoegen</a></li>
<li><a href="#_Toc36192586">Logica</a></li>
<li><a href="#_Toc36192587">Track Detail Pagina</a></li>
<li><a href="#_Toc36192588">Kaart</a></li>
<li><a href="#_Toc36192589">Knoppen</a></li>
<li><a href="#_Toc36192590">De Waypoint tabe</a></li>
<li><a href="#_Toc36192591">Lat / Lon</a></li>
</ul>
<h2><a name="_Toc36319679"></a>Een Waypoint of Radius toevoegen.</h2>
<ol>
<li>Klik op <strong><em>Toevoegen</em></strong> om een waypoint of radius aan te maken in je track<br /><img src="//images/toevoegen_1.png" alt="" width="100%" /></li>
<li>De eerste regel wordt aangemaakt in de waypoint lijst, <em>deze lijst bevat al een tijdelijke locatie <br /> </em><img src="https://pointrush.nl/images/toevoegen_2.png" alt="" width="100%" /></li>
<li>Klik in het vak <strong><em>Titel</em></strong> om je waypoint een naam te geven.<br /> <img src="https://pointrush.nl/images/toevoegen_3.png" alt="" width="100%" /></li>
<li>Klik in het vak <strong><em>Start</em></strong>, de datumprikker verschijnt.<br /> <img src="https://pointrush.nl/images/toevoegen_4.png" alt="" width="100%" /></li>
<li>Selecteer hier een datum en tijd en klik op <strong><em>Opslaan</em></strong><br /> <em>Let op: wanneer de datum en/of tijd niet gewijzigd wordt, zal er ook niets weggeschreven worden, selecteer dus altijd een tijdstop minimaal 1 minuut vanaf heden. Wanneer je de datum of tijd wijzigt zie je ook de tijd verschijnen in de tabel.</em><br /> <img src="https://pointrush.nl/images/toevoegen_5.png" width="100%" /></li>
<li>Voor de eindtijd geldt hetzelfde, klik in het vak <strong><em>Stop</em></strong> om de datumprikker te tonen en te selecteren.<br /> <img src="https://pointrush.nl/images/toevoegen_6.png" alt="" width="100%" /></li>
<li>Klik in het vak <strong><em>Lat</em></strong> om de decimale breedtegraad in te vullen.<br /> <img src="https://pointrush.nl/images/toevoegen_7.png" alt="" width="100%" /></li>
<li>Klik in het vak <strong><em>Lon</em></strong> om de decimale breedtegraad in te vullen.<br /> <img src="https://pointrush.nl/images/toevoegen_8.png" alt="" width="100%" /></li>
<li>Typ een getal om de <strong><em>Radius</em></strong> te bepalen. <br /> <em>Waarde 1 toont een marker i.p.v. een radius op de kaart, deze is al er veel uitgezoomd wordt, beter zichtbaar.</em><br /> <img src="https://pointrush.nl/images/toevoegen_9.png" alt="" width="100%" /></li>
<li>Wanneer het wenselijk is om de <strong><em>Timer</em></strong> te tonen, klik op het rode kruisje &eacute;n selecteer de checkbox.<br /> <img src="https://pointrush.nl/images/toevoegen_10.png" alt="" width="100%" /></li>
<li>Om snel tot een volgend waypoint of radius te komen klik je op het kopieer icoontje en pas je de bewuste waarden aan.<br /> <img src="https://pointrush.nl/images/toevoegen_11.png" alt="" width="100%" /></li>
<li>Klik op Opslaan om je track op te slaan.<br /> <img src="https://pointrush.nl/images/toevoegen_12.png" alt="" width="100%" /></li>
<li>Met de beide knoppen naast de link kan je je kaart bekijken of kopiëren en delen met anderen.<br /> <img src="https://pointrush.nl/images/toevoegen_13.png" alt="" width="100%" /></li>
</ol>
<h2><a name="_Toc36192586"></a>Logica</h2>
<p>Een waypoint of radius wordt op de kaart getoond als:</p>
<p><em>De starttijd &gt; de huidige (server) tijd.<br /> EN<br /> De eindtijd &lt; de huidige (server) tijd.</em></p>
<h2><a name="_Toc36192587"></a>Track Detail Pagina</h2>
<p>Op de Track Detail Pagina zien we een aantal onderdelen, te weten: de kaart, de standaard knoppen en de tabel met waypoints die horen bij deze Track.</p>
<h3><a name="_Toc36192588"></a>Kaart</h3>
<p>De kaart (figuur X) toon alle aanwezige radiussen in deze track, ongeacht of ze volgens de&nbsp;<a href="#_Toc36192586">logica</a> zichtbaar moeten zijn of niet.</p>
<ol>
<li>Wanneer je in de tabel een Waypoint ID aanklikt, kleurt het waypoint geel. Daarmee weet je met welk waypoint je van doen hebt.</li>
<li>Standaard hebben de waypoints een blauwe kleur.</li>
</ol>
<p><img src="#" alt="" /><img src="https://pointrush.nl/images/fig1.png" width="100%" /></p>
<p>Figuur 1</p>
<h3><a name="_Toc36192589"></a>Knoppen</h3>
<p>In figuur X zien we een aantal basis onderdelen benoemd,</p>
<ol>
<li>Voegt een blanco waypoint toe aan je track. Deze krijgt een ID middels autonummering en er wordt als voorbeeld een correcte locatie (lat/lon) meegeven.</li>
<li>Slaat de huidige situatie op.</li>
<li>Roept het helpbestand aan.</li>
<li>Opent de kaartpagina van deze track met de huidige instellingen. Waypoints en radiussen die volgens de opgegeven start en stop tijd nog niet getoond mogen worden, zijn dus ook niet zichtbaar.</li>
<li>Toont de url naar de kaartpagina.</li>
<li>Kopieert de url naar het klembord (daarmee kan je hem delen in whatsapp, messenger o.i.d.)</li>
</ol>
<p><img src="#" alt="" /><img src="https://pointrush.nl/images/fig2.png" width="100%" /></p>
<p>Figuur 2</p>
<h3><a name="_Toc36192590"></a>De Waypoint tabel</h3>
<p>De tabel bevat alle relevante gegevens t.b.v. deze track. Te weten:</p>
<ol>
<li>Waypoint ID, deze ID wordt middels autonummering aangemaakt ter identificatie van het waypoint.</li>
<li>Naam van het waypoint.</li>
<li>Start tijd waarop dit waypoint zichtbaar moet worden op de kaart. Genoteerd in 24-uurs notatie, als DD-MM-JJJJ HH:MM:SS. Op GMT+0100 (Midden-Europese standaardtijd).</li>
</ol>
<p>Wanneer op de datum geklikt wordt, verschijnt het datum selectie venster (zie figuur X). Hier wordt qua tijd enkel gevraagd om uren en minuten, niet om de seconden.</p>
<p><img src="#" alt="" /><img src="https://pointrush.nl/images/fig3.png" width="300" /></p>
<p>Figuur 3</p>
<ol start="4">
<li>Eind tijd waarop het waypoint van de kaart dient te verdwijen, voor deze waarde gelden dezelfde notatie regels als de start tijd.</li>
<li>Lengtegraad, genoteerd in decimale graden (DD). Let bij de invoer van deze waarden op de notatie decimale graden zijn met een <em>punt</em> niet met een</li>
<li>Breedtegraad, notatie gelijk aan de lengtegraad.</li>
<li>De grootte van de radius in meters die getoond wordt op de kaart, wanneer de radius op 1 staat, wordt er een &lsquo;marker&rsquo; getoond i.p.v. een Radius.</li>
<li>Aan/Uit schakelaar voor het tonen van de timer in het betreffende waypoint.<br />
<table style="height: 103px; width: 90%;">
<tbody>
<tr>
<td style="width: 92.5862%;">
<ul>
<li>Standaard staat de timer uit, er wordt dan een rood kruid getoond.</li>
</ul>
</td>
<td style="width: 10%;">&nbsp;<img src="#" alt="" /><img src="https://pointrush.nl/images/rood_kruis.png" width="100%" /></td>
</tr>
<tr>
<td style="width: 92.5862%;">
<ul>
<li>Om in te schakelen klik je op het rode kruis, de &lsquo;checkbox&rsquo; wordt zichtbaar.</li>
</ul>
</td>
<td style="width: 10%;">&nbsp;<img src="#" alt="" /><img src="https://pointrush.nl/images/open_checkbox.png" width="100%" /></td>
</tr>
<tr>
<td style="width: 92.5862%;">
<ul>
<li>Pas wanneer de checkbox ook aangevinkt is, zal de timer weergegeven worden.</li>
</ul>
</td>
<td style="width: 10%;">&nbsp;<img src="#" alt="" /><img src="https://pointrush.nl/images/checked_checkbox.png" width="100%" /></td>
</tr>
<tr>
<td style="width: 92.5862%;">
<ul>
<li>Eenmaal geactiveerd toont het overizicht toont vervolgens een groene vink.</li>
</ul>
</td>
<td style="width: 10%;">&nbsp;<img src="#" alt="" /><img src="https://pointrush.nl/images/groene_vink.png" width="100%" /></td>
</tr>
</tbody>
</table>
</li>
<li>Middels het kopieer icoon kan je de bewuste waypoint kopieren.</li>
<li>Middels het rode kruis kan je de waypoint verwijderen</li>
</ol>
<h2><a name="_Toc36192591"></a>Lat / Lon</h2>
<p>Er word nog gewerkt aan een klikbare kaart, tot die tijd zullen de lengte- (lat) en breedtegraag (lon) nog handmatig ingegeven moeten worden. Je kunt hiervoor tijdelijk de site <a href="https://www.geoplaner.com/">https://www.geoplaner.com/</a> gebruiken.</p>
<p>&nbsp;</p>
<p style="text-align: right;">Versie: 28-03-2020</p>
</div>
@endsection
