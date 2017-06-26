<?php
  // DEFINICJA ADRESU URL
	DEFINE("URL", "http://sjp.pwn.pl/");

  // ILOŚĆ STRON DLA DANEJ LITERY (Z www.sjp.pwn.pl)
  $pages = array(
    'A' => 202, 'B' => 236, 'C' => 202, 'D' => 264, 'E' => 97, 'F' => 121, 'G' => 164,
    'H' => 100, 'I' => 82, 'J' => 75, 'K' => 413, 'L' => 115, 'M' => 269, 'N' => 334,
    'O' => 312, 'P' => 843, 'R' => 267, 'S' => 483, 'T' => 190, 'U' => 134, 'V' => 8,
    'W' => 401, 'X' => 1, 'Y' => 2, 'Z' => 341
  );

  // FUNKCJA PRZETWARZAJĄCA DANE Z ADRESU URL
	function getData($url)
	{
    // POBIERANIE STOPKI DO ZMIENNEJ $data
    $data = @file_get_contents($url);

    // JEŚLI NIE SZUKAMY WYRAZU (TO ZMIENNA GLOBALNA NIE $_GET['szukaj'] ISTNIEJE)
    if(!isset($_GET['szukaj']))
    {
      // PODZIELENIE CAŁEJ STRONY WZGLĘDEM ZNACZNIKA: row col-wrapper alfa 
      $data = explode('<div class="row col-wrapper alfa ">', $data);
      // PODZIELENIE PRAWEJ STRONY WZGLĘDEM ZNACZNIKA: row col-wrapper
      $data = explode('<div class="row col-wrapper">', $data[1]);
      // USUNIĘCIE ZNAKU HTML Z LEWEJ STRONY (INTERESUJE NAS TYLKO WYRAZ, NIE CAŁA STRONA)
      $data = strip_tags($data[0]);
      // USUNIĘCIE BIAŁYCH ZNAKÓW (DUŻE ILOŚCI SPACJI, NOWE LINIE)
      $data = preg_replace('/\s+/', ' ', trim($data));
      // ROZBICIE WYRAZÓW (MAMY KAŻDY WYRAZ W TABLICY)
      $data = explode(' ', $data);
      // ZMIENNA POMOCNICZA (POZBYCIE SIĘ KRÓTKICH WYRAZÓW NP. "A")
      $ret = array();
      // PRZEJŚCIE PO WYRAZACH
      foreach($data as $word)
      {
        // JEŚLI WYRAZ MA WIĘCEJ NIŻ 3 LITERY I NIE MA GO W TABLICY
        if(strlen($word) > 3 && !in_array($word, $ret))
		  // DODAJNIE WYRAZU DO TABLICY
          array_push($ret, $word);
      }
    }
	// JEŚLI SZUKAMY WYRAZU
    else
    {
      // PODZIELENIE STRONY WZGLĘDEM ZNACZNIKA: col-sm-12 col-md-5 col-lg-6 search-content
      $data = explode('<div class="col-sm-12 col-md-5 col-lg-6 search-content">', $data);
      // USUNIĘCIE ZNACZNIKA: a (LINKI/ODNOŚNIKI)
      $data = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $data[1]);
      // USUNIĘCIE IKON (fontawesome)
      $data = str_replace('<i class="fa fa-chevron-circle-down"></i>', '', $data);
      $data = str_replace('<i class="fa fa-chevron-right"></i>', '', $data);
      $data = str_replace('<i class="fa fa-star"></i>', '- ', $data);
      // USUNIĘCIE ZNAKÓW I TEKSTÓW KTÓRE NIE SĄ POTRZEBNE
      $data = str_replace('•••', '', $data);
      $data = str_replace('Więcej porad', '', $data);
      $data = str_replace('<span class="prefix">•••</span>', '', $data);
      $data = str_replace('Więcej', '', $data);
      // PODZIELENIE DOŁU STRONY (INTERESUJE NAS TYLKO TEKST, NIE CAŁA STRONA)
      $data = explode('<div class="wyniki enc-wyniki enc-anchor">', $data);
      $data = explode('<div id="float-banner-bottom" class="row col-wrapper">', $data[0]);
      $ret = $data[0];
    }

    return $ret;
	}

  // JEŚLI NIE SZUKAMY TEKSTU POKŻ WYRAZY
  if(!isset($_GET['szukaj']))
    $data = getData(URL.'lista/'.@$_GET['litera'].';'.@$_GET['strona'].'.html');
  // JEŚLI SZUKAMY TEKST POKAŻ WYNIKI
  else
    $data = getData(URL.'szukaj/'.@$_GET['szukaj'].'.html');
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- SZUKANIE ODPOWIEDNIEJ LITERY -->
    <title>Słownik PWN <?php echo((isset($_GET['litera']))?'&middot; '.$_GET['litera']:''); ?></title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
    <link href="css/toolkit.css" rel="stylesheet">
    <link href="css/application.css" rel="stylesheet">
    <link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">

  </head>
<body class="bpi">
<div class="bpv" id="app-growl"></div>

<nav class="ck rj aeq ro vq app-navbar">
  <button class="re rh ayd" type="button" data-toggle="collapse" data-target="#navbarResponsive"
    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    <span class="rf"></span>
  </button>

  <a class="e" href="index.php">Słownik PWN</a>

  <div class="collapse f" id="navbarResponsive">
    <ul class="navbar-nav ain" style="margin: 0 auto;">
      <?php
        // POKAŻ MENU PĘTLI OD A DO Z
        for($litera='A'; $litera < 'Z'; $litera++)
          echo('<li class="qx"><a class="qv" href="index.php?litera='.$litera.'">'.$litera.'</a></li>');
		echo('<li class="qx"><a class="qv" href="index.php?litera='.'Z'.'">'.'Z'.'</a></li>');
      ?>
    </ul>
  </div>
</nav>

<div class="by ahy">
  <div class="dq">
    <div class="fh">
      <div class="rp bqq agk">
        <div class="rq awx">
          <h6 class="rr">Mateusz Misiak</h6>
          <p class="agk">Ekstrakcja słowników online na przykładzie <a href="http://sjp.pwn.pl">Słownika PWN</a></p>
        </div>
      </div>
    </div>

    <div class="fk">
      <ul class="ca bqe bqf agk">
        <form action="index.php" method="get">
        <li class="tu b ahx">
          <div class="input-group">
            <input type="text" name="szukaj" class="form-control" placeholder="Szukaj słowa..">
            <div class="om">
              <button type="button" class="cg pl">
                <span class="fa fa-search"></span>
              </button>
            </div>
          </div>
        </li>
        </form>
<?php if(!isset($_GET['szukaj'])) { ?>
        <li class="tu b ahx">
          <div class="tv">
            <div class="bqj" style="text-align: center;">
              <?php
                if(!isset($_GET['litera']))
                  echo('<h5>Wybierz literę aby przeszukać słownik.</h5>');
                else
                {
                  // POKAŻ PIERWSZĄ STRONĘ
                  echo('<a href="index.php?litera='.$_GET['litera'].'" class="cg pl">&laquo;</a>&nbsp');

                  // AKTUALNA STRONA, JEŚLI NIE WYRANO USTAW 1
                  $page = (isset($_GET['strona']))?$_GET['strona']:1;

                  // LICZNIK STRONNICOWANIA OD LEWEJ DO AKTUALNEJ STRONY
                  $left = 0;
                  for($i=($page-3); $i >= 1 && $left < 3; $i++)
                  {
                    echo('<a href="index.php?litera='.$_GET['litera'].'&strona='.$i.'" class="cg pl">'.$i.'</a>');
                    $left++;
                  }

                  // POKAŻ AKTUALNĄ STRONĘ
                  echo('<a href="#" class="cg pl disabled">'.$page.'</a>');

                  // POKAŻ PRAWĄ STRONĘ STRONNICOWANIA
                  $right = 0;
                  for($i=$page+1; $i <= $pages[$_GET['litera']] && $right < (3+(3-$left)); $i++)
                  {
                    echo('<a href="index.php?litera='.$_GET['litera'].'&strona='.$i.'" class="cg pl">'.$i.'</a>');
                    $right++;
                  }

                  // POKAŻ OSTATNIĄ STORNĘ
                  echo('&nbsp<a href="index.php?litera='.$_GET['litera'].'&strona='.$pages[$_GET['litera']].'" class="cg pl">&raquo;</a>');
                }
              ?>
            </div>
          </div>
        </li>
        <?php if(isset($_GET['litera'])) { ?>
          <li class="tu b ahx">
            <div class="tv">
              <div class="bqj" style="text-align: center;">
                <h6>Wyrazy na literę "<?php echo($_GET['litera']); ?>"</h6>
              </div>
            </div>
          </li>
          <br>
          <?php
            // PĘTLA PO WSZYSTKICH WYRAZACH
            foreach($data as $word)
            {
              echo('
                <li class="tu b ahx">
                  <div class="tv">
                    <div class="bqj">
                      <h6><a href="index.php?szukaj='.$word.'">'.$word.'</a></h6>
                    </div>
                  </div>
                </li>
              ');
            }
          ?>
      <?php } ?>
<?php } else { ?>
  <li class="tu b ahx">
    <div class="tv" style="width:100%;">
      <div class="bqj">
          <!-- strip_tags USUWA ZNACZNIKI HTML ZA WYJĄTKIEM PODANYCH -->
          <?php echo(strip_tags($data, "<b><br></br><h1><span><div><i>")); ?>
      </div>
    </div>
  </li>
<?php } ?>
      </ul>
    </div>
    <div class="fh">
      <div class="alert to alert-dismissible aye" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <b>Uwaga!</b> Aplikacja wykorzystuje wyłącznie bazę ze strony <a href="http://sjp.pwn.pl/">http://sjp.pwn.pl/</a>.
      </div>

      <div class="rp bqu">
        <div class="rq">© 2017 Mateusz Misiak</div>
      </div>
    </div>
  </div>
</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/toolkit.js"></script>
    <script src="js/application.js"></script>
    <script>
      $(function(){
        if (window.BS&&window.BS.loader&&window.BS.loader.length) {
          while(BS.loader.length){(BS.loader.pop())()}
        }
      })
    </script>
  </body>
</html>