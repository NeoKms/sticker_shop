<?php

class Controller
{
    public $view = 'admin';
    public $title;

    function __construct()
    {
        $this->title = Config::get('sitename');
    }

    function ConsoleAlert($errors){
        ?>
        <script>
            console.log('<?=json_encode($errors)?>');
        </script>
        <?php
    }
}
