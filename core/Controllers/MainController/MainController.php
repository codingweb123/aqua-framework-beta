<?php
namespace MainController;
use Database\Database;
class MainController
{
    public function index(): void
    {
        view("home", wcompact("config"));
    }
}