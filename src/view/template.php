<!DOCTYPE html>
<html lang="fi">
    <head>
        <title>Show me the money! - <?=$this->e($title)?></title>
        <meta charset="UTF-8">
        <link href="styles/tyylit.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <h1><a href="<?=BASEURL?>">Sijoituskone</a></h1>
            <nav>
            <a href="etusivu" title="Etusivu">Etusivu</a>
            <a href="lisaa" title="Lisaa tiedot">Lisää tiedot</a>
            <a href="tulosta" title="Tulosta tiedot">Tulosta tiedot</a>
            <a href="lisaa_tili" title="Luo uusi tili">Luo uusi tili</a>
            </nav>
    </header>

    <section>
        <?=$this->section('content')?>
    </section>
    <footer>
      <hr>
      <div>Make it rain inc.</div>
    </footer>
  </body>
</html>