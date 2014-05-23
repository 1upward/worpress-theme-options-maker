<?php

function tonjoo_tom_options() {

	$data1 = array( 
				array('Halaman Homepage','','Header','','Theme option untuk mengisi konten di halaman homepage'),
				array('Title pertama 1st Container','hm-1st-title1','Input Text','','Untuk mengisi judul di container pertama'),
				array('Title kedua 1st Container','hm-1st-title2','Input Text','',''),
				array('Description 1st Container','hm-1st-desc','Text Area','','Untuk mengisi deskripsi / konten di container pertama')
			);

	$data2 = array( 
				array('Service Page','','Header','','Theme option untuk mengisi konten di halaman homepage'),
				array('Title pertama 1st Container','hm-1st-title1','Input Text','','Untuk mengisi judul di container pertama'),
				array('Title kedua 1st Container','hm-1st-title2','Input Text','',''),
				array('Description 1st Container','hm-1st-desc','Text Area','','Untuk mengisi deskripsi / konten di container pertama')
			);

	$options[] = array(
		'name' => 'Homepage',
		'data' => json_encode($data1)
		);

	$options[] = array(
		'name' => 'Service Page',
		'data' => json_encode($data2)
		);

	return $options;
}