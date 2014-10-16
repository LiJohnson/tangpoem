<?php
include '../config.php';
include CLASS_PATH . '/BaseDao.php';
include DAO_PATH . '/PoemDao.php';
include DAO_PATH . '/AuthorDao.php';
include INCLUDE_PATH . '/function.php';

header("Content-Type:text/html; charset=utf-8");

$poemDao = new PoemDao();
$authorDao = new AuthorDao();
$poemDao->setDebug(1);

$authorDao->setDebug(1);
$data = file_get_contents("shi.json");
$data = json_decode($data,true);

$authors = array();
$poems = array();

foreach ($data as $key => $cate) {
	foreach ($cate['p'] as $i => $poem) {
		
		$author = $authorDao->getOne( array('name' => $poem['author']) );
		
		if( $author )continue;
		$authorDao->save( array( 'name' => $poem['author']) );
	}
}

foreach ($data as $key => $cate) {
	foreach ($cate['p'] as $i => $poem) {
		$author = $authorDao->getOne( array('name' => $poem['author']) );
		$poemDao->save(array(
			'authorId' =>$author['authorId'],
			'title' => $poem['title'],
			'type' => $cate['cate'],
			'content'=> ser( $poem['content'] ),
			'info' => ser( array(
				'rhymed' => join($poem['rhymed'],"\n"),
				'note' => join($poem['note'],"\n"),
				'comment' => join($poem['comment'],"\n"),
				'url' => $poem['url']
				))
			));
	}
}

?>