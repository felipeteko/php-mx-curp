Herramientas en PHP para CURP de México
=======================================

Conjunto de herramientas en PHP para la validación de la Cláve Única de Registro
de Población en México.

Instalación
---------------------------------------

La instalación se puede realizar mediante [composer](https://getcomposer.org).

```sh
composer require francerz/mx-curp
```

Utilización
---------------------------------------

```php

use Francerz\MX_CURP\CURP;
use Francerz\MX_CURP\EntidadesFederativasEnum;
use Francerz\MX_CURP\SexosEnum;

$curp = new CURP('PERJ911109HCMRDN05');

/*
    VALIDAR CURP
    - Verifica estructura de la cadena.
    - Verifica presencia de palabras inconvenientes.
    - Prueba congruencia del dígito verificador.
*/
if (!$curp->esValida()) {
    throw new Exception("La CURP introducida es inválida.");
}

/*
    OBTENER FECHA DE NACIMIENTO
    Obtiene la fecha de nacimiento presente en la CURP en un objeto
    DateTimeImmutable.
*/
$fechaNacimiento = $curp->getFechaNacimiento();
// Salida: 1991-11-09T00:00:00+00:00
echo $fechaNacimiento->format(DateTimeInterface::W3C) . PHP_EOL;

/*
    OBTENER SEXO
    Obtiene el valor representativo del sexo
*/
switch ($curp->getSexo()) {
    case SexosEnum::HOMBRE:
        echo "Es HOMBRE" . PHP_EOL;
        break;
    case SexosEnum::MUJER:
        echo "Es MUJER" . PHP_EOL;
        break;
}

/*
    OBTENER ENTIDAD DE NACIMIENTO
    Obtiene los dígitos característicos de la entidad federativa de nacimiento.
*/
switch ($curp->getEntidadFederativa()) {
    case EntidadesFederativasEnum::AGUASCALIENTES:
        echo "Nació en Aguascalientes." . PHP_EOL;
        break;
    case EntidadesFederativasEnum::COLIMA:
        echo "Nació en Colima." . PHP_EOL;
        break;
    case EntidadesFederativasEnum::ZACATECAS:
        echo "Nació en Zacatecas." . PHP_EOL;
        break;
    case EntidadesFederativasEnum::NACIDO_EXTRANJERO:
        echo "Nació en el Extranjero." . PHP_EOL;
        break;
}

/*
    VERIFICAR CORRESPONDIENCIA DEL NOMBRE
    Realiza pruebas de los caracteres clave del nombre(s) para verificar su
    correspondencia.
*/
if (!$curp->esNombreValido('Juan')) {
    throw new Exception('El nombre no corresponde a la CURP.');
}

/*
    VERIFICAR CORRESPONDIENCIA DEL PRIMER APELLIDO
    Realiza pruebas de los caracteres clave del primer apellido para verificar
    su correspondencia.
*/
if (!$curp->esApellido1Valido('Pérez')) {
    throw new Exception('El primer apellido no corresponde a la CURP.');
}

/*
    VERIFICAR CORRESPONDIENCIA DEL SEGUNDO APELLIDO
    Realiza pruebas de los caracteres clave del segundo apellido para verificar
    su correspondencia.
*/
if (!$curp->esApellido2Valido('Rodríguez')) {
    throw new Exception('El segundo apellido no corresponde a la CURP.');
}

echo "Todo parece estar en orden." . PHP_EOL;
```
