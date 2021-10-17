<?php

namespace Francerz\MX_CURP;

use DateTimeImmutable;

class CURP
{
    public const PATTERN_REGEXP = '/^[A-Z]{4}[0-9]{6}(H|M)[A-Z]{5}.[0-9]$/';

    private const IGNORAR_REGEXP = '/\\b(DAS|DA|DEL|DER|DE|DIE|DI|DD|LAS|LA|LOS|EL|LES|LE|MAC|MC|VAN|VON|Y)\\b/';
    private const CHARS = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";

    private const INCONVENIENTES = [
        'BACA', 'BAKA', 'BUEI', 'BUEY', 'CACA', 'CACO', 'CAGA', 'CAGO',
        'CAKA', 'CAKO', 'COGE', 'COGI', 'COJA', 'COJE', 'COJI', 'COJO',
        'COLA', 'CULO', 'FALO', 'FETO', 'GETA', 'GUEI', 'GUEY', 'JETA',
        'JOTO', 'KACA', 'KACO', 'KAGA', 'KAGO', 'KAKA', 'KAGO', 'KAKA',
        'KAKO', 'KOGE', 'KOGI', 'KOJA', 'KOJE', 'KOJI', 'KOJO', 'KOLA',
        'KULO', 'LILO', 'LOCA', 'LOCO', 'LOKA', 'LOKO', 'MAME', 'MAMO',
        'MEAR', 'MEAS', 'MEON', 'MIAR', 'MION', 'MOCO', 'MOKO', 'MULA',
        'MULO', 'NACA', 'NACO', 'PEDA', 'PEDO', 'PENE', 'PIPI', 'PITO',
        'POPO', 'PUTA', 'PUTO', 'QULO', 'RATA', 'ROBA', 'ROBE', 'ROBO',
        'RUIN', 'SENO', 'TETA', 'VACA', 'VAGA', 'VAGO', 'VAKA', 'VUEI',
        'VUEY', 'WUEI', 'WUEY'
    ];

    private $curp;

    public function __construct(string $curp)
    {
        $this->curp = strtoupper($curp);
    }

    #region static Methods

    /** @return int */
    public static function getValorChar($char)
    {
        return mb_strpos(static::CHARS, $char) ?: 0;
    }

    public static function calcularUltimoDigito(string $curp)
    {
        $suma = 0;
        for ($i = 0; $i < 17; $i++) {
            $char = substr($curp, $i, 1);
            $val = static::getValorChar($char);
            $suma += $val * (18 - $i);
        }
        return (abs($suma % 10 - 10) % 10);
    }

    public static function hasPalabraInconveniente($curp)
    {
        $precurp = substr($curp, 0, 4);
        return in_array($precurp, static::INCONVENIENTES);
    }

    public static function verificarUltimoDigito(string $curp)
    {
        $actual = static::calcularUltimoDigito($curp);
        $expected = substr($curp, 17, 1);
        return $actual == $expected;
    }

    private static function getVocales(string $string)
    {
        return preg_replace('/[^AEIOU]/', '', $string);
    }

    private static function getConsonantes(string $string)
    {
        return preg_replace('/[^BCDFGHJKLMNÑPQRSTVWXYZ]/', '', $string);
    }

    private static function normalizarCadena(string $string)
    {
        $string = static::quitarAcentos($string);
        $string = mb_strtoupper($string);
        $string = str_replace('Ñ', 'X', $string);
        $string = preg_replace(static::IGNORAR_REGEXP, '', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        return $string;
    }

    private static function getPrimerToken(string $string)
    {
        $tokens = explode(' ', $string, 2);
        return reset($tokens);
    }

    private static function quitarAcentos(string $string)
    {
        return strtr($string, [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'Ü' => 'U',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ü' => 'u'
        ]);
    }
    #endregion

    public function esValida()
    {
        $match = preg_match(static::PATTERN_REGEXP, $this->curp);
        if ($match == false) {
            return false;
        }
        if (static::hasPalabraInconveniente($this->curp)) {
            return false;
        }
        return static::verificarUltimoDigito($this->curp);
    }

    public function getSexo()
    {
        $sexo = substr($this->curp, 10, 1);
        return SexosEnum::fromValue($sexo);
    }

    public function getFechaNacimiento()
    {
        $char = substr($this->curp, 16, 1);
        if ($char === false || $char === '') {
            return null;
        }
        $year = is_numeric($char) ? 1900 : 2000;
        $year += substr($this->curp, 4, 2);
        $month = substr($this->curp, 6, 2);
        $day = substr($this->curp, 8, 2);

        return new DateTimeImmutable("{$year}-{$month}-{$day}");
    }

    public function getEntidadFederativa()
    {
        $entfed = substr($this->curp, 11, 2);
        return EntidadesFederativasEnum::fromValue($entfed);
    }

    public function esApellido1Valido(string $apellido1)
    {
        $apellido1 = static::normalizarCadena($apellido1);
        $apellido1 = static::getPrimerToken($apellido1);

        $actual = substr($apellido1, 0, 1) ?: 'X';
        $expected = substr($this->curp, 0, 1);
        if ($expected !== $actual) {
            return false;
        }

        $actual = substr(static::getConsonantes(substr($apellido1, 1)), 0, 1) ?: 'X';
        $expected = substr($this->curp, 13, 1);
        if ($expected !== $actual) {
            return false;
        }

        $actual = substr(static::getVocales(substr($apellido1, 1)), 0, 1) ?: 'X';
        $expected = substr($this->curp, 1, 1);
        if ($expected === $actual) {
            return true;
        }

        $precurp = substr($this->curp, 0, 4);
        $precurp = substr_replace($precurp, $actual, 1, 1);
        if ($expected == 'X' && in_array($precurp, static::INCONVENIENTES)) {
            return true;
        }

        return false;
    }

    public function esApellido2Valido(string $apellido2)
    {
        $apellido2 = static::normalizarCadena($apellido2);
        $apellido2 = static::getPrimerToken($apellido2);

        $actual = substr($apellido2, 0, 1) ?: 'X';
        $expected = substr($this->curp, 2, 1);
        if ($expected !== $actual) {
            return false;
        }

        $actual = substr(static::getConsonantes(substr($apellido2, 1)), 0, 1) ?: 'X';
        $expected = substr($this->curp, 14, 1);
        if ($expected !== $actual) {
            return false;
        }

        return true;
    }

    public function esNombreValido(string $nombre)
    {
        $nombre = static::normalizarCadena($nombre);
        switch ($this->getSexo()->getValue()) {
            case SexosEnum::HOMBRE:
                $nombre = preg_replace('/^(JOSE|J\.?)\s/', '', $nombre);
                break;
            case SexosEnum::MUJER:
                $nombre = preg_replace('/^(MARIA|MA?\.?)\s/', '', $nombre);
                break;
        }
        $nombre = static::getPrimerToken($nombre);

        $actual = substr($nombre, 0, 1) ?: 'X';
        $expected = substr($this->curp, 3, 1);
        if ($expected !== $actual) {
            return false;
        }

        $actual = substr(static::getConsonantes(substr($nombre, 1)), 0, 1) ?: 'X';
        $expected = substr($this->curp, 15, 1);
        if ($expected !== $actual) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return $this->curp;
    }
}
