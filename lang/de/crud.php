<?php

return [
    'common' => [
        'actions' => 'Aktionen',
        'create' => 'Erstellen',
        'edit' => 'Bearbeiten',
        'update' => 'Update',
        'new' => 'Neu',
        'cancel' => 'Abbrechen',
        'attach' => 'anhängen',
        'detach' => 'Trennen Sie',
        'save' => 'Speichern Sie',
        'delete' => 'Löschen',
        'delete_selected' => 'Ausgewählte löschen',
        'search' => 'Suche...',
        'back' => 'Zurück zum Index',
        'are_you_sure' => 'Sind Sie sicher?',
        'no_items_found' => 'Keine Artikel gefunden',
        'created' => 'Erfolgreich erstellt',
        'saved' => 'Erfolgreich gespeichert',
        'removed' => 'Erfolgreich entfernt',
        'Shop'=>'Shop',
    ],

    'categories' => [
        'name' => 'Kategorien',
        'index_title' => 'Kategorien Liste',
        'new_title' => 'Neue Kategorie',
        'create_title' => 'Kategorie erstellen',
        'edit_title' => 'Kategorie bearbeiten',
        'show_title' => 'Kategorie anzeigen',
        'inputs' => [
            'logo' => 'Logo',
            'name' => 'Name',
        ],
    ],

    'products' => [
        'name' => 'Produkte',
        'index_title' => 'Liste der Produkte',
        'new_title' => 'Neues Produkt',
        'create_title' => 'Produkt erstellen',
        'edit_title' => 'Produkt bearbeiten',
        'show_title' => 'Produkt anzeigen',
        'total_product'=>'Gesamtprodukte',
        'average_price'=>'durchschnittlicher Preis',
        'inputs' => [
            'picture' => 'Bild',
            'name' => 'Name',
            'description' => 'Beschreibung',
            'price' => 'Preis',
            'weight' => 'Gewicht',
            'stock' => 'Lager',
        ],
    ],

    'reviews' => [
        'name' => 'Bewertungen',
        'index_title' => 'Liste der Bewertungen',
        'new_title' => 'Neue Rezension',
        'create_title' => 'Rezension erstellen',
        'edit_title' => 'Rezension bearbeiten',
        'show_title' => 'Rezension anzeigen',
        'inputs' => [
            'review' => 'Rezension',
            'product_id' => 'Produkt',
        ],
    ],

    'product_reviews' => [
        'name' => 'Produktbewertungen',
        'index_title' => 'Liste der Bewertungen',
        'new_title' => 'Neue Rezension',
        'create_title' => 'Rezension erstellen',
        'edit_title' => 'Rezension bearbeiten',
        'show_title' => 'Rezension anzeigen',
        'inputs' => [
            'review' => 'Rezension',
        ],
    ],

    'customers' => [
        'name' => 'Kunden',
        'index_title' => 'Kundenliste',
        'new_title' => 'Neue Kunden',
        'create_title' => 'Kunden anlegen',
        'edit_title' => 'Kunden bearbeiten',
        'show_title' => 'Kunden anzeigen',
        'inputs' => [
            'name' => 'Name',
            'email' => 'E-Mail',
            'password' => 'Passwort',
            'phone_number' => 'Telefon Nummer',
        ],
    ],

    'order_products' => [
        'name' => 'Produkte bestellen',
        'index_title' => ' Liste',
        'new_title' => 'Produkt "New Order',
        'create_title' => 'Bestellung_Produkt erstellen',
        'edit_title' => 'Bestellung_Produkt bearbeiten',
        'show_title' => 'Bestellung_Produkt anzeigen',
        'inputs' => [
            'product_id' => 'Produkt',
            'quantity' => 'Menge',
        ],
    ],

    'customer_orders' => [
        'name' => 'Kundenbestellungen',
        'index_title' => 'Liste der Aufträge',
        'new_title' => 'Neue Ordnung',
        'create_title' => 'Bestellung erstellen',
        'edit_title' => 'Bestellung bearbeiten',
        'show_title' => 'Bestellung anzeigen',
        'inputs' => [
            'stauts' => 'Stauts',
            'number' => 'Nummer',
        ],
    ],

    'product_categories' => [
        'name' => 'Produkt-Kategorien',
        'index_title' => ' Liste',
        'new_title' => 'Neue Produktkategorie',
        'create_title' => 'kategorie_produkt erstellen',
        'edit_title' => 'kategorie_produkt bearbeiten',
        'show_title' => 'kategorie_produkt anzeigen',
        'inputs' => [
            'categorie_id' => 'Kategorie',
        ],
    ],

    'categorie_products' => [
        'name' => 'Kategorie Produkte',
        'index_title' => ' Liste',
        'new_title' => 'Neue Produktkategorie',
        'create_title' => 'kategorie_produkt erstellen',
        'edit_title' => 'kategorie_produkt bearbeiten',
        'show_title' => 'kategorie_produkt anzeigen',
        'inputs' => [
            'product_id' => 'Produkt',
        ],
    ],

    'orders' => [
        'name' => 'Bestellungen',
        'index_title' => 'Liste der Aufträge',
        'new_title' => 'Neue Ordnung',
        'create_title' => 'Bestellung erstellen',
        'edit_title' => 'Bestellung bearbeiten',
        'show_title' => 'Bestellung anzeigen',
        'Order Date' =>'Bestelldatum',
        'open_orders'=>'Offene Bestellungen',
        'average_price'=>'Durchschnittspreis',
        'inputs' => [
            'number' => 'Nummer',
            'total_price' => 'Gesamtpreis',
            'stauts' => 'Status',
            'payment_method'=>'Zahlungsmethode',
            'payment_status'=>'Zahlungsstand',
            'pick_up_date'=>'Abholdatum',
        ],
        'options'=> [
            'processing' => 'Verarbeitung',
            'packed' => 'Verpackt',
            'picked' => 'Gepflückt',
            'cancelled' => 'Abgesagt',
            'paid' => 'Bezahlt',
            'not paid' => 'Nicht bezahlt',
            'pay on site'=>'Bezahlung vor Ort', 
        ]
    ],

    'customer_reviews' => [
        'name' => 'Kundenrezensionen',
        'index_title' => 'Liste der Bewertungen',
        'new_title' => 'Neue Rezension',
        'create_title' => 'Rezension erstellen',
        'edit_title' => 'Rezension bearbeiten',
        'show_title' => 'Rezension anzeigen',
        'inputs' => [
            'review' => 'Rezension',
            'product_id' => 'Produkt',
        ],
    ],

    'users' => [
        'name' => 'Benutzer',
        'index_title' => 'Benutzerliste',
        'new_title' => 'Neuer Benutzer',
        'create_title' => 'Benutzer erstellen',
        'edit_title' => 'Benutzer bearbeiten',
        'show_title' => 'Benutzer anzeigen',
        'inputs' => [
            'name' => 'Name',
            'email' => 'E-Mail',
            'password' => 'Passwort',
            'phone_number' => 'Telefon Nummer',
        ],
    ],

    'roles' => [
        'name' => 'Rollen',
        'index_title' => 'Liste der Rollen',
        'create_title' => 'Rolle erstellen',
        'edit_title' => 'Rolle bearbeiten',
        'show_title' => 'Rolle anzeigen',
        'inputs' => [
            'name' => 'Name',
        ],
    ],

    'permissions' => [
        'name' => 'Berechtigungen',
        'index_title' => 'Liste der Berechtigungen',
        'create_title' => 'Erlaubnis erstellen',
        'edit_title' => 'Berechtigung bearbeiten',
        'show_title' => 'Erlaubnis anzeigen',
        'inputs' => [
            'name' => 'Name',
        ],
    ],
    'filters' => [
        'created_from' => 'erstellt von',
        'created_until' => 'erstellt bis',
    ],
    'Associations'=>'Verbände',
];
