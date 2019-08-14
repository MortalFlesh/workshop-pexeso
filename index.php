<?php
session_start();

class Image {
    public $id;
    public $source;

    public function __construct($id) {
        $this->id = $id;
        $this->source = 'images/image_' . $id . '.jpg';
    }
}

class Player {
    public $id;
    public $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

class Table {
    public $players;
    public $images;

    public function __construct($players, $images)
    {
        $this->players = $players;
        $this->images = array_merge($images, $images);

        shuffle($this->images);
    }
}

function showTable($table, $currentPlayer, $selectedImages)
{
    ?><div class="players"><?php
    foreach ($table->players as $player) {
        $isPlaying = "";
        if ($currentPlayer->id === $player->id) {
            $isPlaying = "is-playing";
        }

        ?><h3 class="player <?=$isPlaying?>">
            <?=$player->name?> (<?=$_SESSION["score"][$player->id]?>)
        </h3><?php
    }
    ?>
    <a style="float: right;" href="?game=new">Nová hra</a>
    <br class="clear" /></div><?php

    ?><div class="table"><?php
    foreach ($table->images as $index => $image) {
        $currentImage = $index + 1;
        $isHidden = !in_array($currentImage, $selectedImages);

        if ($isHidden) {
            if ($selectedImages['first'] > 0 && $selectedImages['second'] === 0) {
                ?><div class="image">
                    <a href="?firstImage=<?=$selectedImages['first']?>&secondImage=<?=$currentImage?>"><img src="images/hidden.jpg" alt="hidden" /></a>
                </div><?php
            } else {
                ?><div class="image">
                    <a href="?firstImage=<?=$currentImage?>"><img src="images/hidden.jpg" alt="hidden" /></a>
                </div><?php
            }
        } else {
            ?><div class="image">
                <img src="<?=$image->source?>" alt="<?=$image->name?>" />
            </div><?php
        }
    }
    ?><br class="clear" /></div><?php
}

// Game

if (!array_key_exists('table', $_SESSION) || ($_GET["game"] ?? "") === "new") {
    $table = new Table([1 => new Player(1, 'Peter'), 2 => new Player(2, 'Deni'), ], [new Image(1), new Image(2), new Image(3), new Image(4)]);
    $_SESSION["table"] = serialize($table);
    $_SESSION["player"] = 1;
    $_SESSION["score"] = [
        1 => 0,
        2 => 0,
    ];
} else {
    $table = unserialize($_SESSION["table"]);
}

$currentPlayer = $table->players[$_SESSION["player"]];

$firstImage = (int) ($_GET["firstImage"] ?? 0);
if ($firstImage > 0) {
    $secondImage = (int) ($_GET["secondImage"] ?? 0);
} else {
    $secondImage = 0;
}

$status = 'Na řadě je hráč ' . $currentPlayer->name;

if ($firstImage > 0 && $secondImage > 0) {
    $isSame = $table->images[$firstImage]->id === $table->images[$secondImage]->id;

    if ($isSame) {
        $_SESSION["score"][$currentPlayer->id]++;

        $status = 'Hráč ' . $currentPlayer->name . ' uhádl a pokračuje. <a href="?game=continue">Pokračovat</a>';
    } else {
        if ($currentPlayer->id = 1) {
            $_SESSION["player"] = 2;
        } else {
            $_SESSION["player"] = 1;
        }

        $status = 'Hráč ' . $currentPlayer->name . ' neuhádl a hraje další hráč. <a href="?game=continue">Pokračovat</a>';
    }
}

$selectedImages = [
    "first" => $firstImage,
    "second" => $secondImage
];

?>
<html>
    <head>
        <title>Pexeso</title>
        <style>
            .clear {
                clear: both;
            }

            .players {
                border: 1px solid black;
                border-radius: 5px;
                padding: 10px;
            }

            .player {
                float: left;
                padding: 5px 10px;
                margin: 0;
                margin-right: 10px;
            }

            .is-playing {
                color: green;
            }

            .table {
                padding: 20px;
                margin: 20px auto;
                border: 5px solid black;
                background: #fadcb4;
            }

            .image {
                float: left;
                height: 200px;
                width: 200px;
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <?=showTable($table, $currentPlayer, $selectedImages)?>

        <div>
            <?=$status?>
        </div>
    </body>
</html>
