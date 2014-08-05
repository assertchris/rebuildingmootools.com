<?php

/*
 * Define a few helper functions, to read Markdown files
 * and parse them to HTML...
 */

function read($translation, $path)
{
  return file_get_contents(
    App::make("path.base") . "/translations/{$translation}/manuscript/{$path}"
  );
}

function parse($markdown)
{
  $markdown = str_replace("{lang=js}", "", $markdown);
  $markdown = str_replace("A>", ">", $markdown);

  return Markdown::string($markdown);
}

/*
 * Define a few utility methods for formatting URLs
 */

function titleFromRoute($route)
{
  $route = trim($route, "/");
  $route = preg_replace("/[-]([a-z0-9])/", " $1", $route);
  $route = preg_replace("/([0-9]+)/", "$1: ", $route);

  return ucwords($route);
}

/*
 * Define the English routes...
 */

$routes = [
  "/part-1-coffeescript" => "01-coffeescript.md",
  "/part-2-grunt"        => "02-grunt.md",
  "/part-3-buster"       => "03-buster.md",
  "/part-4-typeof"       => "04-typeof.md"
];

Route::get("/", function () use ($routes) {
  $markdown = read("en", "00-introduction.md");
  $markup   = parse($markdown);

  return View::make("page/index", compact("markup", "routes") + ["prefix" => ""]);
});

foreach ($routes as $route => $path) {

  Route::get($route, function () use ($path, $routes) {
    $markdown = read("en", $path);
    $markup   = parse($markdown);

    return View::make("page/part", compact("markup", "routes") + ["prefix" => ""]);
  });

}
