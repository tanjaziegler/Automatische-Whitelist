# Automatische-Whitelist

***

Dies ist die README zum Plugin "Automatische Whitelist".
Bitte lese dir die Hinweise aufmerksam durch, bevor du das Plugin installierst.

***

Hinweise:

Das Plugin ist auf diversen Mybb 1.8-Versionen getestet.
Es werden keine weiteren Plugins oder Erweiterungen benötigt.

Das Plugin ermöglicht es, Usern mit einem einfachen Klick auszuwählen, ob sie den Account behalten oder löschen lassen möchten. 
Das Gadget ist vorrangig für den RPG-Gebrauch erstellt worden.
Neben dem Status ("Bleibt" und "Geht") wird auch der letzte Beitrag in einer speziellen Kategorie (hier: Inplay und Inplayarchiv) ausgegeben.

Für Moderatoren und Administratoren:
Die Liste schaltet sich NICHT automatisch ab, dies muss händisch vorgenommen werden.
Ebenso die Aktualisierung der Eingaben mittels des Buttons unterhalb der Liste.

Die Übersicht kann über /whitelist.php aufgerufen werden.

Die Variable für die Meldung im Welcomeblock lautet {$Whitelistinfo}.

Es werden folgende Templates erstellt:
- whitelist
- whitelist_bit
- whitelist_bit_abwesend
- whitelist_bit_aktualisieren
- whitelist_delete
- whitelist_delete_bit

Ladet beide Dateien in den entsprechenden Ordner und installiert das Plugin im Admin-Controlpanel.
