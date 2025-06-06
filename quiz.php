<?php
  session_start();

  // Quiz steps and options
  $questions = [
    1 => ["Do you want a big dog?", ["yes", "no"]],
    2 => ["Are you okay with lots of shedding?", ["yes", "no"]],
    3 => ["Do you need a family-friendly dog?", ["yes", "no"]]
  ];

  $step = isset($_GET['step']) ? intval($_GET['step']) : 1;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['quiz']["answer_$step"] = $_POST['answer'] ?? '';
    $next = $step + 1;
    if ($next > count($questions)) {
      header("Location: store_answers.php");
    } else {
      header("Location: quiz.php?step=$next");
    }
    exit;
  }

  if (!isset($questions[$step])) {
    echo "Invalid step.";
    exit;
  }

  // Prepare template variables
  $questionText = $questions[$step][0];
  $options = '';
  foreach ($questions[$step][1] as $option) {
    $options .= "<label><input type='radio' name='answer' value='$option' required> " . ucfirst($option) . "</label><br>";
  }

  // Load and render the template
  ob_start();
  include 'templates/quiz.html'; // OR 'templates/quiz.html.php' if renamed
  $template = ob_get_clean();

  // Replace placeholders
  $template = str_replace('{{step}}', $step, $template);
  $template = str_replace('{{total}}', count($questions), $template);
  $template = str_replace('{{question}}', $questionText, $template);
  $template = str_replace('{{options}}', $options, $template);

  echo $template;
?>