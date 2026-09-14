<?php

namespace App\Helpers;

class SanitizeHelper
{
    /**
     * Nettoyer une chaîne pour éviter les XSS
     */
    public static function cleanString(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Nettoyer un tableau récursivement
     */
    public static function cleanArray(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $cleaned[$key] = self::cleanString($value);
            } elseif (is_array($value)) {
                $cleaned[$key] = self::cleanArray($value);
            } else {
                $cleaned[$key] = $value;
            }
        }
        return $cleaned;
    }

    /**
     * Échapper les caractères spéciaux LIKE (% _ \)
     */
    public static function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    /**
     * LIKE insensible aux jokers + portable MySQL/SQLite.
     * SQLite n'a pas de caractère d'échappement par défaut,
     * d'où la clause ESCAPE explicite (identique au défaut MySQL).
     */
    public static function whereLike($query, string $column, string $value, string $boolean = 'and')
    {
        return $query->whereRaw(
            "{$column} LIKE ? ESCAPE '\\'",
            ['%'.self::escapeLike($value).'%'],
            $boolean
        );
    }

    public static function orWhereLike($query, string $column, string $value)
    {
        return self::whereLike($query, $column, $value, 'or');
    }

    /**
     * Valider et nettoyer un email
     */
    public static function cleanEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return $email ?: null;
    }

    /**
     * Valider et nettoyer une URL
     */
    public static function cleanUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }
        $url = filter_var(trim($url), FILTER_SANITIZE_URL);
        return $url ?: null;
    }

    /**
     * Supprimer les balises HTML d'une chaîne
     */
    public static function stripTags(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return strip_tags($value);
    }
}
