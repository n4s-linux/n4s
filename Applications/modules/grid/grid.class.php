<?php


    class grid extends module
    {
        public function main()
        {
            $array[] = array("navn"=>"Mikkel Christensen","telefon"=>"41282808","email"=>"mikkel@mikjar.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Mikkel Christensen","telefon"=>"21282808","email"=>"mikkel@mikjar.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Sachin Jordan","telefon"=>"41282808","email"=>"jordan@gmail.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Zakariah Weir","telefon"=>"41282808","email"=>"weir@hotmail.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Dario Atkinson","telefon"=>"41282808","email"=>"dario@atkinson.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Theia Rich","telefon"=>"41282808","email"=>"rich@theia.com", "saldo"=>rand(1,5)*10);
            $array[] = array("navn"=>"Nida Baird","telefon"=>"41282808","email"=>"nida@industries.com", "saldo"=>rand(1,5)*10);

            $grid = new Datagrid(
                new arrayDataSource($array)
            );

            $grid->setFields(array("navn","telefon","email","saldo"));
            
            $grid->field("saldo")
                ->registerHandler(new myFieldHandler())
                ->setLabel("Indestående");

//            $this->stpl->assign("content", $grid->render().file_get_contents(__DIR__."/form.tpl"));
            $this->stpl->assign("content", $grid->render());
        }

    }
