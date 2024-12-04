<?php

namespace App\Services;

class TokenValidator
{
    public static function validate(string $token): bool
    {
        // En este array sólo meteremos los caracteres de apertura que vengan en el token: "{", "[" y "("
        $caracteresApertura = [];

        // Nos creamos un array asociativo con el caracter de apertura correspondiente a cada caracter de cierre
        $equivalencias = [
            '}' => '{',
            ']' => '[',
            ')' => '('
        ];

        $caracteresToken = str_split($token);

        // Recorremos cada caracter del token
        foreach ($caracteresToken as $caracterToken) {
            if (in_array($caracterToken, $equivalencias)) {
                // Si es alguno de los caracteres de apertura, lo metemos en el array $caracteresAValidar
                $caracteresApertura[] = $caracterToken;
            } elseif (isset($equivalencias[$caracterToken])) {
                // Si es alguno de los caracteres de cierre, sacamos el último elemento del array de caracteres a validar
                // y comprobamos si es igual al equivalente del caracter del token.
                // Si es igual, el caracter de apertura desaparecerá del array $caracteresApertura y continuaremos con el bucle
                // (se ha abierto y se ha cerrado bien). Si no lo es, no pasará la validación
                if (array_pop($caracteresApertura) !== $equivalencias[$caracterToken]) {
                    return false;
                }
            }
        }

        // Por último validamos si ha quedado algún caracter de apertura sin su correspondiente cierre
        return empty($caracteresApertura);
    }
}
