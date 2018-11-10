<?php
for ( $i=1; $i<=100; $i++ ) {
    if ( $i % 5 === 0  &&  $i % 7 === 0  ) {
        echo "Fizz Buzz";
    } elseif ( $i % 7 === 0 ) {
        echo "Buzz";
    } elseif (  $i % 5 === 0) {
        echo "Fizz";
    } else {
        echo $i;
    }
    echo '<br>';
}