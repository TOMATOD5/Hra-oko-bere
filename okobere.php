<!DOCTYPE html>
<html lang="cs-cz">
<head>
    <meta charset="utf-8" />
    <title>Hra oko bere</title>
</head>
    <body>

    <?php

header('Content-type: text/html; charset=utf8');

$cards = array();
$count = 0;
$buttonText = 'Start';

// při odeslání formuláře
if (((isset($_POST['nextcard'])) && ($_POST['cards'])) || (isset($_POST['stop'])))
{
    // do pole karet se nahraje již promíchané pole
    $cards = explode(',', $_POST['cards']);
    // počet vypsaných karet v minulém kole
    $count = $_POST['count'];

    // pokud hráč neukončil hru
    if (($count >= 0) && (!isset($_POST['stop'])))
    {
        // táhne další kartu
        $count++;
        $buttonText = 'Další karta';
    }
}
// jinak se nahraje nesetříděné pole karet a to se promíchá
else
{
    $cards = array (
        "♠2", "♣2", "♥2", "♦2",
        "♠3", "♣3", "♥3", "♦3",
        "♠4", "♣4", "♥4", "♦4",
        "♠5", "♣5", "♥5", "♦5",
        "♠6", "♣6", "♥6", "♦6",
        "♠7", "♣7", "♥7", "♦7",
        "♠8", "♣8", "♥8", "♦8",
        "♠9", "♣9", "♥9", "♦9",
        "♠10", "♣10", "♥10", "♦10",
        "♠J", "♣J", "♥J", "♦J",
        "♠Q", "♣Q", "♥Q", "♦Q",
        "♠K", "♣K", "♥K", "♦K",
        "♠A", "♣A", "♥A", "♦A",
    );

    shuffle($cards);
}

// hodnoty nečíselných karet
$cardsScore = array('J' => 10, 'Q' => 10, 'K' => 10, 'A' => 11);
$playerScore = 0;

// výpis karet
echo('<table cellspacing="5"><tr>');
for ($i = 0; $i < $count; $i++)
{
    echo('<td width="70px" height="100px" style="border:2px solid black; text-align:center;"><h1>' . $cards[$i] . '</h1></td>');

    // pokud hráč ukončil hru spočítá se jeho skóre
    if (isset($_POST['stop']))
    {
        $card = str_replace(array('♠', '♣', '♥', '♦'), '', $cards[$i]);
        if (is_numeric($card))
            $playerScore += $card;
        else
            $playerScore += $cardsScore[$card];
    }
}
echo('</tr></table>');

// pokud hráč ukončil hru - vyhodnotí se
if (isset($_POST['stop']))
{
    // skóre počítače se generuje náhodně
    $computerScoreArray = array(16, 17, 18, 19, 20, 21, 'moc');
    $computerScore = $computerScoreArray[rand(0, count($computerScoreArray) - 1)];

    // výpis skóre hráče
    echo('Tvoje skóre: ');
    if ($playerScore > 21)
        echo('moc');
    else
        echo($playerScore);

    // skóre pc
    echo('<br />Skóre počítače: ' . $computerScore . '<br />');

    // vyhodnocení
    // počítač vyhraje za podmínky
    // 1) pokud je hodnota pc číselná (v zadání je i 'moc') a je větší než hráčova
    // 2) skóre hráče je vyšší než 21
    if ((is_numeric($computerScore) && ($computerScore >= $playerScore))
        || ($playerScore > 21))
        echo('Vyhrál počítač');
    else
        echo('Vyhrál jsi ty!');

    // balíček se zamíchá a hra se při dalším kole resetuje
    shuffle($cards);
    $count = 0;
}

// výpis formuláře
echo('
    <form method="post">
        <input type="hidden" name="cards" value="' . implode(',', $cards) . '" />
        <input type="hidden" name="count" value="' . $count .'" />
        <input type="submit" name="nextcard" value="' . $buttonText . '" />
        <input type="submit" name="stop" value="Stop" />
    </form>
');
?>
    </body>
</html>

