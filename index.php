<?php

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

function showTable($table, $selectedImages)
{
    ?><div class="players"><?php
    foreach ($table->players as $player) {
        ?><h3 class="player">
            <?=$player->name?>
        </h3><?php
    }
    ?><br class="clear" /></div><?php

    ?><div class="table"><?php
    foreach ($table->images as $index => $image) {
        $currentImage = $index + 1;
        $isHidden = !in_array($currentImage, $selectedImages);

        if ($isHidden) {
            if ($selectedImages['first'] > 0) {
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

$table = new Table([new Player(1, 'Peter'), new Player(2, 'Deni'), ], [new Image(1), new Image(2), new Image(3), new Image(4)]);
// todo - current table setup must be in session

$firstImage = (int) ($_GET["firstImage"] ?? 0);
if ($firstImage > 0) {
    $secondImage = (int) ($_GET["secondImage"] ?? 0);
} else {
    $secondImage = 0;
}

if ($firstImage > 0 && $secondImage > 0) {
    $isSame = $table->images[$firstImage]->id === $table->images[$secondImage]->id;

    if ($isSame) {
        // todo -  player[current]++
        // set current player
    }

    $selectedImages = [
        "first" => 0,
        "second" => 0,
    ];
} else {
    $selectedImages = [
        "first" => $firstImage,
        "second" => $secondImage
    ];
}

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
        <?=showTable($table, $selectedImages)?>
    </body>
</html>
