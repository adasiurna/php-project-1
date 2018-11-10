<?php

$petras = rand(10, 20);
$jonas = rand(5, 25);

echo('Petras: '.$petras.' Jonas: '.$jonas.'<br>');
if ($petras > $jonas)
{
    echo('Laimėjo: Petras');      

} elseif ( $petras < $jonas ) {
    echo('Laimėjo: Jonas');
} else {
    echo('Lygiosios!');
}

?>