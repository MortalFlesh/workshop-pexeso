<?php

class Image {
    public $id;
    public $source;
    public $isHidden;

    public function __construct($id) {
        $this->id = $id;
        $this->source = 'images/image_' . $id . '.jpg';
        $this->isHidden = false;
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

$table = new Table([
    new Player(1, 'Peter'),
    new Player(2, 'Deni'),
], [
    new Image(1),
    new Image(2),
    new Image(3),
    new Image(4),
]);

function showTable($table)
{
    ?><div class="players"><?php
    foreach ($table->players as $player) {
        ?><h3 class="player">
            <?=$player->name?>
        </h3><?php
    }
    ?><br class="clear" /></div><?php

    ?><div class="table"><?php
    foreach ($table->images as $image) {
        if ($image->isHidden) {
            ?><div class="image">
                <img src="images/hidden.jpg" alt="hidden" />
            </div><?php
        } else {
            ?><div class="image">
                <img src="<?=$image->source?>" alt="<?=$image->name?>" />
            </div><?php
        }
    }
    ?><br class="clear" /></div><?php
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
        <?=showTable($table)?>
    </body>
</html>
