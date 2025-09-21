<?php

return [
    'availability' => [
        'title' => 'Beschikbare Tijdslots',
        'no_slots' => 'Geen beschikbare tijdslots voor de geselecteerde datum.',
        'service_not_found' => 'Dienst niet gevonden.',
        'invalid_date' => 'Ongeldige datum opgegeven.',
        'date_past' => 'Kan beschikbaarheid niet controleren voor datums in het verleden.',
    ],
    
    'booking' => [
        'created' => 'Reservering succesvol bevestigd',
        'cancelled' => 'Reservering succesvol geannuleerd',
        'not_found' => 'Reservering niet gevonden',
        'already_cancelled' => 'Reservering is al geannuleerd',
        'cannot_cancel' => 'Kan deze reservering niet annuleren',
        'conflict' => 'Tijdslot is niet meer beschikbaar',
        'invalid_service' => 'Ongeldige dienst geselecteerd',
        'invalid_time' => 'Ongeldig tijdslot geselecteerd',
        'party_size_required' => 'Aantal personen is vereist voor deze dienst',
        'customer_required' => 'Klantinformatie is vereist',
    ],
    
    'errors' => [
        'unauthorized' => 'Ongeautoriseerde toegang',
        'forbidden' => 'Toegang verboden',
        'not_found' => 'Bron niet gevonden',
        'validation_failed' => 'Validatie mislukt',
        'server_error' => 'Interne serverfout',
        'rate_limit' => 'Te veel verzoeken. Probeer het later opnieuw.',
    ],
    
    'messages' => [
        'booking_confirmed' => 'Uw reservering is bevestigd',
        'booking_cancelled' => 'Uw reservering is geannuleerd',
        'booking_reminder' => 'Herinnering: U heeft morgen een reservering',
        'booking_no_show' => 'U heeft uw reservering gemist',
    ],
];
