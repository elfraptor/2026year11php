<?php
include_once "indexHeader.php";
?>
<h1>Variables</h1>
  <!-- User Input Form for Variables -->
  <?php
  // Initialize variables
  $x = "";
  $y = "";
  $showResults = false;

  // Check if form has been submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $x = isset($_POST["x"]) ? floatval($_POST["x"]) : 0;
      $y = isset($_POST["y"]) ? floatval($_POST["y"]) : 0;
      $showResults = true;
    }
    ?>

  <h3>Enter values for x and y:</h3>
    <form method="POST" action="">
    <label for="x">Enter x: </label>
      <input type="number" id="x" name="x" step="any" required>
      <br><br>
    <label for="y">Enter y: </label>
      <input type="number" id="y" name="y" step="any" required>
      <br><br>
      <input type="submit" value="Calculate">
    </form>

    <?php
    // Display results if form was submitted
    if ($showResults) {
        $hello = "Hello World!";
        $yCubed = $y ** 3;
        echo ("<hr>");
      echo ("<h3>Results:</h3>");
        echo($hello);
        echo ("<br>The square of $x is " . $x**2);
        echo ("<br>The cube of $y is " . $yCubed . ".");
        echo ("<br>The sum of $x and $y is " . ($x+$y) . ".");
        echo ("<br>The difference of $x and $y is " . ($x-$y) . ".");
        echo ("<br>The product of $x and $y is " . ($x*$y) . ".");
        if ($y != 0) {
            echo ("<br>The floor quotient of $x and $y is ". (int)($x/$y) . ".");
            echo ("<br>The rounded quotient of $x and $y is ". round($x/$y) . ".");
            echo ("<br>The ceiling quotient of $x and $y is ". ceil($x/$y) . ".");
            echo ("<br>The modulus of $x and $y is " . ($x%$y) . ".");
          } else {
          echo ("<br><span style='color:red;'>Cannot divide by zero. Please enter a non-zero value for y.</span>");
          }
        }
        ?>
        <?php
        include_once "indexFooter.php";
        ?>