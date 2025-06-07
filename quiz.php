<?php
  session_start();

  // Quiz steps and options
  $questions = [
    //activity level
    1 => ["What's your activity level?", ["Pretty low, I stronly dislike exercise and activity.", 
          "Moderate, I exercise to stay healthy but it's nothing intense.", 
          "High, I try to get out and break a sweat several days a week.",
          "Very High, I love challenging myself athletically every day."]],
    //introvert dog or extrovert dog
    2 => ["Are you an introvert or extrovert?", ["Introvert for sure", "Extrovert for sure","Somewhere in the middle"]],
    //family or outdoors, while updating activity level and introvert extrovert
    3 => ["What's your absolute favorite thing to do?", ["Read a good book", "Travel to new vacation spots"
        ,"Go on a bike ride", "Hike and camp outdoors","Hang with friends and family"]],
    //beginner friendly dog or challenging dog
    4 => ["Would you rather...",["Try something you've never done before", "Do something you already know you enjoy"]],
    //Updating family or self oriented
    5 => ["What's most important to you?",["Your relationships", "Self-improvement", "Memorable experiences",
        "Staying healthy","The environment"]],
     //Updating family or self oriented and energy level
    6 => ["What describes you best?",["Low-maintenance","Up for anything","Family-oriented"]],
    //Updating family or self oriented and energy level
    7 => ["On the weekend, would you rather...",["Explore a new city","Go backpacking in the woods",
        "Have a backyard BBQ","Run a 5K", "Work on a creative project"]],
    //the level of obedience. Peeps with stronger leadership can handle tougher dog 
    8 => ["You tend to ...",["Go with the flow","Take the lead","Come up with ingenious solutions", 
        "Silently Observe", "Refuse to give in"]],
    //dog size
    9 => ["Which dog sounds most like your vibe?", [
    "Small and snuggly (fits in your bag)",
    "Medium-sized and versatile (lap dog or adventure buddy)",
    "Big and bold (goofy giant with a heart of gold)"]],
    //shedding, long hair short hair
    10 => ["How do you feel about dog hair everywhere?", [
    "I need my home spotless — low/no shedding please",
    "I'm okay vacuuming often — love fluffy dogs",
    "I'll brush it daily if they're cute enough"]]
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