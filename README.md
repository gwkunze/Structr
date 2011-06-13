# Structr #

With the advent of document based stores like CouchDB and MongoDB complex data structures are a lot more common within web applications. You no longer have a separate array containing a blog post and another containing all comments but a single document containing most, if not all data. The schema-less design most of these document stores use can lead to huge mess if one doesn't exercise sufficient discipline.

With Structr you can define the format of these documents within your application so you can validate and/or transform documents. Furthermore Structr can change variable types or run custom code on parts of the document.

Besides complex documents Structr can be used to parse most PHP data such as request parameters, for example you can use it to define valid values for a paging GET parameter.


Example
=======

``` php
<?php

use Structr\Structr;

$value = array(
	"id" => 23,
	"author" => "John",
	"title" => "Foo",
	"text" => "The quick brown fox jumps over the lazy dog's back",
	"tags" => array("foo", "bar", "baz"),
	"comments" => array(
		array(
			"author" => "Mike",
			"text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
		)
	)
);

Structr::define("comment")
	->isMap()
		->key("author")->isString()->end()
		->key("text")->isString()->end()
	->end()
	;

$document = Structr::ize($value)
	->isMap()
		->key("id")
			->valuePrototype()
				->isInteger()->end()
			->endPrototype()
		->endKey()
		->key("author")
			->defaultValue("No Author")
			->valuePrototype()
				->isString()->end()
			->endPrototype()
		->endKey()
		->key("title")
			->valuePrototype()
				->isString()->end()
			->endPrototype()
		->endKey()
		->key("text")
			->valuePrototype()
				->isString()->end()
			->endPrototype()
		->endKey()
		->key("tags")
			->valuePrototype()
				->isList()
					->listPrototype()
						->isString()->end()
					->endPrototype()
				->end()
				->post(function($v) { sort($v); return $v; })
			->endPrototype()
		->endKey()
		->key("comments")
			->valuePrototype()
				->isList()
					->listPrototype()
						->is("comment")->end()
					->endPrototype()
				->end()
			->endPrototype()
		->endKey()
	->end()
	->run();

```

This definition is very verbose, but also very flexible. More examples are forthcoming, but for now you can take a look at the tests directory.