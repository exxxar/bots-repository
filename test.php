<?php
function romanToArabic($romanNumeral) {
$arabicNumber = 0;

  $romanMap = [
      "M" => 1000, "CM" => 900, "D" => 500, "CD" => 400,
      "C" => 100, "XC" => 90, "L" => 50, "XL" => 40,
      "X" => 10, "IX" => 9, "V" => 5, "IV" => 4, "I" => 1
  ];

  for ($i = 0; $i < strlen($romanNumeral); $i++) {
      if (isset($romanMap[$romanNumeral[$i]])) {
          $arabicNumber += $romanMap[$romanNumeral[$i]];

          if ($i > 0 && $romanMap[$romanNumeral[$i]] > $romanMap[$romanNumeral[$i - 1]]) {
              $arabicNumber -= 2 * $romanMap[$romanNumeral[$i - 1]];
          }
      }
  }

  return $arabicNumber;
}

$romanNumber = "MCMXC";
$arabicRepresentation = romanToArabic($romanNumber);
echo "Арабское представление числа $romanNumber: $arabicRepresentation";
