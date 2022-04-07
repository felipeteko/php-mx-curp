<?php

namespace Francerz\MX_CURP\Tests;

use DateTimeImmutable;
use Francerz\MX_CURP\CURP;
use Francerz\MX_CURP\EntidadesFederativasEnum;
use Francerz\MX_CURP\SexosEnum;
use PHPUnit\Framework\TestCase;

class CURPTest extends TestCase
{
    public function testGetCharValue()
    {
        $this->assertEquals(0, CURP::getValorChar('0'));
        $this->assertEquals(1, CURP::getValorChar('1'));
        $this->assertEquals(2, CURP::getValorChar('2'));
        $this->assertEquals(3, CURP::getValorChar('3'));
        $this->assertEquals(4, CURP::getValorChar('4'));
        $this->assertEquals(5, CURP::getValorChar('5'));
        $this->assertEquals(6, CURP::getValorChar('6'));
        $this->assertEquals(7, CURP::getValorChar('7'));
        $this->assertEquals(8, CURP::getValorChar('8'));
        $this->assertEquals(9, CURP::getValorChar('9'));
        $this->assertEquals(10, CURP::getValorChar('A'));
        $this->assertEquals(11, CURP::getValorChar('B'));
        $this->assertEquals(12, CURP::getValorChar('C'));
        $this->assertEquals(13, CURP::getValorChar('D'));
        $this->assertEquals(14, CURP::getValorChar('E'));
        $this->assertEquals(15, CURP::getValorChar('F'));
        $this->assertEquals(16, CURP::getValorChar('G'));
        $this->assertEquals(17, CURP::getValorChar('H'));
        $this->assertEquals(18, CURP::getValorChar('I'));
        $this->assertEquals(19, CURP::getValorChar('J'));
        $this->assertEquals(20, CURP::getValorChar('K'));
        $this->assertEquals(21, CURP::getValorChar('L'));
        $this->assertEquals(22, CURP::getValorChar('M'));
        $this->assertEquals(23, CURP::getValorChar('N'));
        $this->assertEquals(24, CURP::getValorChar('Ñ'));
        $this->assertEquals(25, CURP::getValorChar('O'));
        $this->assertEquals(26, CURP::getValorChar('P'));
        $this->assertEquals(27, CURP::getValorChar('Q'));
        $this->assertEquals(28, CURP::getValorChar('R'));
        $this->assertEquals(29, CURP::getValorChar('S'));
        $this->assertEquals(30, CURP::getValorChar('T'));
        $this->assertEquals(31, CURP::getValorChar('U'));
        $this->assertEquals(32, CURP::getValorChar('V'));
        $this->assertEquals(33, CURP::getValorChar('W'));
        $this->assertEquals(34, CURP::getValorChar('X'));
        $this->assertEquals(35, CURP::getValorChar('Y'));
        $this->assertEquals(36, CURP::getValorChar('Z'));
    }
    public function testCurp()
    {
        // echo "AAAAA " . CURP::calcularUltimoDigito('LACS720605MCMRBG06') . " AAAAAA";

        $curp = new CURP('GABO040830HCMRRSAO');
        $this->assertFalse($curp->esValida());
        $curp = new CURP('GABO040830HCMRRSA0');
        $this->assertTrue($curp->esValida());

        $curp = new CURP('PERJ911109HCMRDN05');
        $this->assertTrue($curp->esValida());
        $this->assertTrue($curp->getSexo()->is(SexosEnum::HOMBRE));
        $this->assertTrue($curp->esNombreValido('Juan'));
        $this->assertTrue($curp->esApellido1Valido('Pérez'));
        $this->assertTrue($curp->esApellido1Valido('Preciado'));
        $this->assertTrue($curp->esApellido2Valido('Rodríguez'));
        $this->assertTrue($curp->esApellido2Valido('Ruedas'));
        $this->assertTrue($curp->getEntidadFederativa()->is(EntidadesFederativasEnum::COLIMA));
        $this->assertEquals(new DateTimeImmutable('1991-11-09'), $curp->getFechaNacimiento());
        $this->assertFalse($curp->esNombreValido('Javier'));
        $this->assertFalse($curp->esNombreValido('Antonio'));
        $this->assertFalse($curp->esApellido1Valido('Parada'));
        $this->assertFalse($curp->esApellido1Valido('Peláez'));

        $curp = new CURP('GORG871016MMNNMD00');
        $this->assertTrue($curp->esValida());
        $this->assertTrue($curp->getSexo()->is(SexosEnum::MUJER));
        $this->assertTrue($curp->esNombreValido('Guadalupe'));
        $this->assertTrue($curp->esApellido1Valido('González'));
        $this->assertTrue($curp->esApellido2Valido('Ramírez'));
        $this->assertTrue($curp->getEntidadFederativa()->is(EntidadesFederativasEnum::MICHOACAN));
        $this->assertEquals(new DateTimeImmutable('1987-10-16'), $curp->getFechaNacimiento());

        $curp = new CURP('TOSR750518HSLRLS05');
        $this->assertTrue($curp->esValida());
        $this->assertTrue($curp->getSexo()->is(SexosEnum::HOMBRE));
        $this->assertTrue($curp->esNombreValido('José del Rosario'));
        $this->assertTrue($curp->esNombreValido('J. del Rosario'));
        $this->assertTrue($curp->esNombreValido('J del Rosario'));
        $this->assertTrue($curp->esApellido1Valido('del Toro'));
        $this->assertTrue($curp->esApellido2Valido('Da Silva'));
        $this->assertTrue($curp->getEntidadFederativa()->is(EntidadesFederativasEnum::SINALOA));
        $this->assertEquals(new DateTimeImmutable('1975-05-18'), $curp->getFechaNacimiento());

        $curp = new CURP('MOPA030227MCCRLNA4');
        $this->assertTrue($curp->esValida());
        $this->assertTrue($curp->getSexo()->is(SexosEnum::MUJER));
        $this->assertTrue($curp->esNombreValido('María de los Ángeles'));
        $this->assertTrue($curp->esNombreValido('Ma. de los Ángeles'));
        $this->assertTrue($curp->esNombreValido('Ma de los Ángeles'));
        $this->assertTrue($curp->esNombreValido('M. de los Ángeles'));
        $this->assertTrue($curp->esNombreValido('M de los Ángeles'));
        $this->assertTrue($curp->esApellido1Valido('de la Mora'));
        $this->assertTrue($curp->esApellido2Valido('del Pilar'));
        $this->assertTrue($curp->getEntidadFederativa()->is(EntidadesFederativasEnum::CAMPECHE));
        $this->assertEquals(new DateTimeImmutable('2003-02-27'), $curp->getFechaNacimiento());

        $curp = new CURP('AAAM000000HAAAAR00');
        $this->assertTrue($curp->getSexo()->is(SexosEnum::HOMBRE));
        $this->assertTrue($curp->esNombreValido('José María'));

        $curp = new CURP('AAAJ000000MAAAAS00');
        $this->assertTrue($curp->getSexo()->is(SexosEnum::MUJER));
        $this->assertTrue($curp->esNombreValido('María José'));

        $curp = new CURP('AAAJ000000HAAAAS00');
        $this->assertTrue($curp->getSexo()->is(SexosEnum::HOMBRE));
        $this->assertTrue($curp->esNombreValido('José'));

        $curp = new CURP('AAAM000000MAAAAR00');
        $this->assertTrue($curp->getSexo()->is(SexosEnum::MUJER));
        $this->assertTrue($curp->esNombreValido('María'));

        $curp = new CURP('ZXZA000000MAAXXA00');
        $this->assertTrue($curp->esApellido1Valido('Z Flores'));
        $this->assertTrue($curp->esApellido2Valido('Z Flores'));

        $curp = new CURP('EXEA000000MAAKKA00');
        $this->assertTrue($curp->esApellido1Valido('Ek'));
        $this->assertTrue($curp->esApellido2Valido('Ek'));

        $curp = new CURP('PEPA000000MAAXXA00');
        $this->assertTrue($curp->esApellido1Valido('Peñuñuri'));
        $this->assertTrue($curp->esApellido2Valido('Peñuñuri'));

        $curp = new CURP('XIXA000000HAAQQA00');
        $this->assertTrue($curp->esApellido1Valido('Ñique'));
        $this->assertTrue($curp->esApellido2Valido('Ñique'));

        $curp = new CURP('XXSA000000MAAXNL00');
        $this->assertTrue($curp->esApellido1Valido(''));
        $this->assertTrue($curp->esApellido2Valido('Sánchez'));

        $curp = new CURP('MOXA000000HAARXL00');
        $this->assertTrue($curp->esApellido1Valido('Moreno'));
        $this->assertTrue($curp->esApellido2Valido(''));

        $curp = new CURP('BXCA000000HAAZNR00');
        $this->assertFalse(CURP::hasPalabraInconveniente((string)$curp));
        $this->assertTrue($curp->esApellido1Valido('Baeza'));
        $this->assertTrue($curp->esApellido2Valido('Contreras'));
        $this->assertTrue($curp->esNombreValido('José Armando'));
    }
}
