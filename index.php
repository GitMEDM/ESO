<?php
  // DEFINICJA ADRESU URL
	DEFINE("URL", "http://sjp.pwn.pl/");

  // ILOŒÆ STRON DLA DANEJ LITERY (Z www.sjp.pwn.pl)
  $pages = array(
    'A' => 202, 'B' => 236, 'C' => 202, 'D' => 264, 'E' => 97, 'F' => 121, 'G' => 164,
    'H' => 100, 'I' => 82, 'J' => 75, 'K' => 413, 'L' => 115, 'M' => 269, 'N' => 334,
    'O' => 312, 'P' => 843, 'R' => 267, 'S' => 483, 'T' => 190, 'U' => 134, 'V' => 8,
    'W' => 401, 'X' => 1, 'Y' => 2, 'Z' => 341
  );

  // FUNKCJA PRZETWARZAJ¥CA DANE Z ADRESU URL
	function getData($url)
	{
    // POBIERANIE STOPKI DO ZMIENNEJ $data
    $data = @file_get_contents($url);

    // JEŒLI NIE SZUKAMY WYRAZU (TO ZMIENNA GLOBALNA NIE $_GET['szukaj'] ISTNIEJE)
    if(!isset($_GET['szukaj']))
    {
      // PODZIELENIE CA£EJ STRONY WZGLÊDEM ZNACZNIKA: row col-wrapper alfa 
      $data = explode('<div class="row col-wrapper alfa ">', $data);
      // PODZIELENIE PRAWEJ STRONY WZGLÊDEM ZNACZNIKA: row col-wrapper
      $data = explode('<div class="row col-wrapper">', $data[1]);
      // USUNIÊCIE ZNAKU HTML Z LEWEJ STRONY (INTERESUJE NAS TYLKO WYRAZ, NIE CA£A STRONA)
      $data = strip_tags($data[0]);
      // USUNIÊCIE BIA£YCH ZNAKÓW (DU¯E ILOŒCI SPACJI, NOWE LINIE)
      $data = preg_replace('/\s+/', ' ', trim($data));
      // ROZBICIE WYRAZÓW (MAMY KA¯DY WYRAZ W TABLICY)
      $data = explode(' ', $data);
      // ZMIENNA POMOCNICZA (POZBYCIE SIÊ KRÓTKICH WYRAZÓW NP. "A")
      $ret = array();
      // PRZEJŒCIE PO WYRAZACH
      foreach($data as $word)
      {
        // JEŒLI WYRAZ MA WIÊCEJ NI¯ 3 LITERY I NIE MA GO W TABLICY
        if(strlen($word) > 3 && !in_array($word, $ret))
		  // DODAJNIE WYRAZU DO TABLICY
          array_push($ret, $word);
      }
    }
	// JEŒLI SZUKAMY WYRAZU
    else
    {
      // PODZIELENIE STRONY WZGLÊDEM ZNACZNIKA: col-sm-12 col-md-5 col-lg-6 search-content
      $data = explode('<div class="col-sm-12 col-md-5 col-lg-6 search-content">', $data);
      // USUNIÊCIE ZNACZNIKA: a (LINKI/ODNOŒNIKI)
      $data = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $data[1]);
      // USUNIÊCIE IKON (fontawesome)
      $data = str_replace('<i class="fa fa-chevron-circle-down"></i>', '', $data);
      $data = str_replace('<i class="fa fa-chevron-right"></i>', '', $data);
      $data = str_replace('<i class="fa fa-star"></i>', '- ', $data);
      // USUNIÊCIE ZNAKÓW I TEKSTÓW KTÓRE NIE S¥ POTRZEBNE
      $data = str_replace('•••', '', $data);
      $data = str_replace('Wiêcej porad', '', $data);
      $data = str_replace('<span class="prefix">•••</span>', '', $data);
      $data = str_replace('Wiêcej', '', $data);
      // PODZIELENIE DO£U STRONY (INTERESUJE NAS TYLKO TEKST, NIE CA£A STRONA)
      $data = explode('<div class="wyniki enc-wyniki enc-anchor">', $data);
      $data = explode('<div id="float-banner-bottom" class="row col-wrapper">', $data[0]);
      $ret = $data[0];
    }

    return $ret;
	}

  // JEŒLI NIE SZUKAMY TEKSTU POK¯ WYRAZY
  if(!isset($_GET['szukaj']))
    $data = getData(URL.'lista/'.@$_GET['litera'].';'.@$_GET['strona'].'.html');
  // JEŒLI SZUKAMY TEKST POKA¯ WYNIKI
  else
    $data = getData(URL.'szukaj/'.@$_GET['szukaj'].'.html');
?>