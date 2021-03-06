@extends('layouts.app')

@section('title', __('basic.home'))
@section('url', route('home'))
@section('caption', __('basic.content'))

@section('content')
    <div class="container">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 15%;">&nbsp;</td>
                    <td>
                        <center><iframe src="https://pointrush.nl/track/1"
                                style="height:400px;width:95%;border:none;"></iframe></center>
                        <div align="left"><sup><i>Bovenstaande kaart toont een realtime track, klik <a
                                        href="https://pointrush.nl/track/1	" target="_blank" rel="noopener">hier</A> voor de
                                    full screen versie.</i></sup></div>
                        <p>In deze digitale tijd zijn we steeds op zoek naar nieuwe uitdagende route technieken, deze tool
                            kan je op meerdere manieren inzetten als online route of spel techniek Alle varianten gaan er
                            echter wel vanuit dat je zelf bepaald hoe je op de bewuste locaties komt, de tool geeft alleen
                            aan waar je moet zijn.</p>
                        <h3>Waypoints of Radiussen</h3>
                        <p>Middels een <a href="https://pointrush.nl/login	" target="_blank" rel="noopener">backend</a>
                            functie kan je -<em>op basis van een lengte- en breedtegraad</em>- aangeven waar de deelnemers
                            naar toe moeten. Je geeft van elk punt aan wanneer deze op de kaart beschikbaar komt en wanner
                            deze ook weer zal verdwijnen. Op die manier hoeft de eind- of tussen locatie niet meteen bekend
                            te zijn bij je deelnemers.</p>
                        <p>Door de grootte van de radius te wijzigen kan je diverse spel elementen toepassen, optioneel is
                            de timer per radius aan te zetten, zodoende zien de deelnemers hoe lang de radius nog
                            beschikbaar is.</p>
                        <p>Wanneer deelnemers hun locatiegegevens beschikbaar stellen aan de browser in hun apparaat, dan
                            zien ze hun huidige locatie terug op de kaart. Wanneer de huidige locatie binnen
                            &eacute;&eacute;n van de actieve radiussen valt, kleurt de bewuste radius groen en kan deze ???geclaimd??? worden. Door opvolgende waypoints in the stellen, kan een route worden gecre??erd.</p>
                        <h3>Kaarten</h3>
                        <p>Voor deze tool maken we gebruik van zgn. OpenStreetMap kaarten, dit zijn rechten-vrije kaarten
                            zonder de &lsquo;Google Maps faciliteiten&rsquo;, hiermee dwingen we de gebruikers wel zelf te
                            navigeren en kaart te lezen en dus niet klakkeloos achter een pijltje aan lopen.</p>
                        <h3>Mogelijke toepassingen</h3>
                        <p>Hieronder staan een aantal varianten toegelicht, heb je zelf nog een andere spelvariant? We horen
                            het graag.</p>
                        <h4 style="padding-left: 30px;">&lsquo;Volg de waypoints&rsquo;</h4>
                        <p style="padding-left: 30px;">Waypoints komen na elkaar zichtbaar op de kaart, hiermee leidt je de
                            deelnemers naar een locatie zonder de eind locatie of route vroegtijdig bekend te maken. Je kunt
                            hiermee teams of koppels bijvoorbeeld een omtrekkende route laten maken.</p>
                        <h4 style="padding-left: 30px;">&lsquo;Kom in de cirkel&rsquo;</h4>
                        <p style="padding-left: 30px;">Er staat 1 radius op de kaart en de timer loopt, welke deelnemers
                            zijn op tijd &lsquo;binnen&rsquo;? (Eenvoudig te bewijzen door een screenshot met groene cirkel
                            te sturen naar de organisator)</p>
                        <h4 style="padding-left: 30px;">&lsquo;Hou hem groen&rsquo;</h4>
                        <p style="padding-left: 30px;">We starten met een grote radius, de deelnemers krijgen de opdracht om
                            elke &lsquo;x&rsquo; minuten een screenshot van een groene radius te sturen. Het center punt
                            veranderd per keer en de radius wordt per keer kleiner. Wie blijft er het langst in de groene
                            cirkel?</p>
                        <h4 style="padding-left: 30px;">&lsquo;Vrije zones&rsquo;</h4>
                        <p style="padding-left: 30px;">In een willekeurig &lsquo;kat en muis&rsquo; of
                            &lsquo;hunter/seeker&rsquo; spel wil je wel eens vrije zones inbouwen. Stel je radius in en toon
                            ze op de kaart. Iedereen weet -ongeacht paden en wegen- waar de vrije zones zijn. Makkelijk te
                            controleren door alle betrokken spelers.</p>
                        <h3>Gebruik van de tool</h3>
                        <p>Het staat iedereen vrij deze online tool te gebruiken, <a href="https://pointrush.nl/register"
                                target="_blank" rel="noopener">registreer</a> je account en creeer je eigen track. We zullen
                            je niet spammen of lastigvallen, maar we willen graag onze gebruikers kunnen verwittigen waar
                            nodig.</p>
                        <p>Omdat deze tool nog constant in ontwikkeling is, houden we het recht om de database geheel te
                            wissen, wil je de tool dus gebruiken tijdens een activiteit? Geef even een seintje en je zit
                            altijd goed!</p>
                        <p>Heb je de tool gebruikt voor een activiteit, dan horen we dat natuurlijk graag, foto&rsquo;s
                            en/of ervaringen mogen naar: <a href="mailto:busger@graafottogroep.nl" target="_blank"
                                rel="noopener">busger@graafottogroep.nl</a>.</p>
                        <p>Veel speelplezier!</p>
                        <p>Arjan Busger op Vollenbroek<br /> Scouting Graaf Otto Groep, Lochem</p>
                    </td>
                    <td style="width: 15%;">&nbsp;</td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
