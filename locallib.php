<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_messagestream_ai_activated_in_courses(): array {
    $raw = get_config('mod_messagestream', 'aienabledcourses');

    if (empty($raw)) {
        return [];
    }

    // Spalten, Leerzeichen entfernen und in int-Array umwandeln.
    $ids = array_map('intval', array_filter(array_map('trim', explode(',', $raw))));

    return $ids;
}
