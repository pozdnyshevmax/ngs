<?php 

	$json_string = file_get_contents("http://rabota.ngs.ru/api/v1/vacancies/");
	Header("Content-Type: text/html;charset=UTF-8");
	$obj=json_decode($json_string);
	
	$vacansies = $obj->vacancies;
	$rubric_stat = Array();
	$word_stat = Array();
	foreach($vacansies as $vac){
		$vac_date = substr($vac->mod_date,0,10);
		if($vac_date != date("Y-m-d")) continue; // Today?
		foreach($vac->rubrics as $rubric){
			if(isset($rubric_stat[$rubric->title]))
				$rubric_stat[$rubric->title]++;
			else 
				$rubric_stat[$rubric->title] = 1;
		}
		$keywords = preg_split("/\W/u", $vac->header); // only not alpha characters in utf 
		foreach($keywords as $word){
			if($word == "") continue; // skip empty strings
			$word = mb_strtolower($word,'UTF-8');
			if(isset($word_stat[$word]))
				$word_stat[$word]++;
			else 
				$word_stat[$word] = 1;
		}
	}
	
	arsort($rubric_stat);
	echo "<table><tr><td>Rubric</td><td>Count</td></tr>";
	foreach($rubric_stat as $key => $value){
		echo "<tr><td>". $key ."</td><td>". $value ."</td></tr>";
	}
	echo "</table><br><br>";
	
	arsort($word_stat);
	echo "<table><tr><td>Word</td><td>Count</td></tr>";
	foreach($word_stat as $key => $value){
		echo "<tr><td>". $key ."</td><td>". $value ."</td></tr>";
	}
	echo "</table>";
?>
