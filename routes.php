<?php
use Backend\Helpers\Backend;
/** October CMS plugin: MG.PageBuilder ...*/
$backend = new Backend;

Route::post('/'.$backend->uri().'/mg/pagebuilder/saveimage/{type}/{count?}', 'MG\PageBuilder\Controllers\SaveImage@index')->middleware('web');
Route::get('/'.$backend->uri().'/mg/pagebuilder/blocksdata', 'MG\PageBuilder\Controllers\BlocksData@index')->middleware('web');
Route::get('/'.$backend->uri().'/mg/pagebuilder/snippetsdata', 'MG\PageBuilder\Controllers\SnippetsData@index')->middleware('web');
