<p align="center">
  <a href="https://github.com/brenno-duarte/correios-api/releases"><img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/brenno-duarte/correios-api?style=flat-square"></a>
  <a href="https://github.com/brenno-duarte/correios-api/blob/master/LICENSE"><img alt="GitHub" src="https://img.shields.io/github/license/brenno-duarte/correios-api?style=flat-square"></a>
</p>

## Sobre

API não oficial em PHP para os principais serviços dos Correios.

## Instalação via Composer

```
composer brenno-duarte/CorreiosAPI
```

## Inicializando

```php
require "vendor/autoload.php";

use CorreiosAPI\CorreiosAPI;

$cep = new CorreiosAPI();
```

## Como usar

### Buscar CEP

```php
$res = $cep->buscaCEP('01408001');

var_dump($res);
```

Caso não saiba o CEP da rua, utilize `null` no primeiro parâmetro e informe o endereço nos parâmetros seguintes.

```php
$res = $cep->buscaCEP(null, "CE", "Iguatu", "Nelson");

var_dump($res);
```

### Calculo preço e prazo

Informe o `$servico`, `$cepOrigem`, `$cepDestino`, `$peso`, `$formato`, `$comprimento`, `$altura`, `$largura` e `$diametro`.

```php
$res = $cep->calcPrecoPrazo('40010', '01408001', '63504210', '2', '1', '80', '20', '20', '91');

var_dump($res);
```

`$maoPropria`, `$valordeclarado`, `$avisoRecebimento` e `$retorno` são informados por padrão, mas você também pode informa-los nos 4 últimos parâmetros.

```php
$res = $cep->calcPrecoPrazo('40010', '01408001', '63504210', '2', '1', '80', '20', '20', '91', 'N', '0', 'N', 'xml');

var_dump($res);
```

### Rastrear encomenda

Consulte a situação de seus objetos nos Correios. Informe o código de rastreio do objeto como parâmetro.

```php
$res = $cep->rastrear("AA123456789BR");

var_dump($res);
```

### Disque coleta

O Disque Coleta é o serviço de coleta domiciliar de encomendas nacionais dos Correios.

O serviço possui área de cobertura restrita. Para verificar se a sua localidade está dentro da área de cobertura, informe seu CEP como parâmetro.

```php
$res = $cep->disqueColeta('01408001');

var_dump($res);
```

### Restrição de entrega

Verifique se há alguma restrição para a entrega informando o serviço, CEP de origem e CEP de destino.
 
```php
$res = $cep->restricaoEntrega("04510", "63504210", "01408001");

var_dump($res);
```

Uma maneira rápida e descomplicada de consultar Certificados de Licenciamento de Veículos e Carteiras de Habilitação postados pelo DETRAN/Ceará nos Correios, 
oferecendo comodidade e praticidade para você.

Assim, após a quitação dos extratos competentes emitidos pelo DETRAN/CE e decorridos mais de trinta dias sem o recebimento do documento pretendido, 
consulte a situação atual dele, por parâmetro a PLACA do seu veículo e obtenha a resposta desejada.

```php
$res = $cep->consultaDetranCE("pnx9620");

var_dump($res);
```

## License

[MIT](https://github.com/brenno-duarte/correios-api/blob/master/LICENSE)
