<?php
header('Content-type: application/rss+xml');
date_default_timezone_set('UTC');

function uuid() {
  // From http://php.net/manual/en/function.com-create-guid.php
  return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

$goodIngestCount = $_GET['good'];
$badIngestCount  = $_GET['bad'];
$minFileSize     = $_GET['minSize'];
$maxFileSize     = $_GET['maxSize'];
$minSpeed        = $_GET['minSpeed'];
$maxSpeed        = $_GET['maxSpeed'];

$id = uuid();
$host = ($_SERVER['REQUEST_SCHEME'] ?: 'http') . '://' . $_SERVER['SERVER_NAME'];
$scriptPathUrl = $host . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));

$ingests = array_merge(array_fill(0, $goodIngestCount, true), array_fill(0, $badIngestCount, false));
shuffle($ingests);
?>
<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:ext="http://ooyala.com/syndication/ext/" xmlns:mediasl="http://www.slide.com/funspace/developer/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:boxee="http://boxee.tv/spec/rss/" version="2.0">
<channel>
<title>Test Feed <?= $id ?></title>
<description>Test Feed</description>
<link><?= $host ?></link>
<?php
foreach($ingests as $ingest):
  if($ingest) {
    $vidSrc = $scriptPathUrl
                . '/slow.php?size='
                . rand($minFileSize, $maxFileSize)
                . '&speed='
                . rand($minSpeed, $maxSpeed);
  } else {
    $vidSrc = $scriptPathUrl . '/404';
  }

  $ingestId = uuid();
?>
  <item>
    <title>Video <?= $ingestId ?></title>
    <guid isPermaLink="false"><?= $ingestId ?></guid>
    <link><?= $host ?></link>
    <pubDate><?= date('r') ?></pubDate>
    <media:title>Video <?= $ingestId ?></media:title>
    <media:thumbnail url="<?= $scriptPathUrl ?>/resources/thumb.png" width="358" height="204"/>
    <media:group>
      <media:content url="<?= $vidSrc ?>" type="video/mp4" medium="video" expression="full" bitrate="950" framerate="23.976" samplingrate="44.1" duration="225" lang="en" width="480" height="360"/>
    </media:group>
  </item>
<?php endforeach; ?>
</channel>
</rss>
