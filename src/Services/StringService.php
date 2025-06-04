<?php

namespace Itstudioat\Mediamanager\src\Services;

use Illuminate\Support\Str;

class StringService
{
    public function sanitizeFilename($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        // Umlaute und Sonderzeichen in ASCII umwandeln (ä → ae, é → e, etc.)
        $name = Str::ascii($name);

        // Leerzeichen und ähnliche Whitespace-Zeichen durch _ ersetzen
        $name = preg_replace('/\s+/', '_', $name);

        // Nur erlaubte Zeichen: a-z, A-Z, 0-9, _, -, .
        $name = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $name);

        // Mehrfache Unterstriche reduzieren (optional)
        $name = preg_replace('/_+/', '_', $name);

        return $name . ($extension ? '.' . strtolower($extension) : '');
    }
}
