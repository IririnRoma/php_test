<?php

$text = <<<TXT
<p class="big">
    
	Год основания:<b>1589 г.</b> Волгоград отмечает день города в <b>2-е воскресенье сентября</b>. <br>В <b>2023 году</b> эта дата - <b>10 сентября</b>.
</p>
<p class="float">
	<img src="https://www.calend.ru/img/content_events/i0/961.jpg" alt="Волгоград" width="300" height="200" itemprop="image">
	<span class="caption gray">Скульптура «Родина-мать зовет!» входит в число семи чудес России (Фото: Art Konovalov, по лицензии shutterstock.com)</span>
</p>
<p>
	<i><b>Великая Отечественная война в истории города</b></i></p><p><i>Важнейшей операцией Советской Армии в Великой Отечественной войне стала <a href="https://www.calend.ru/holidays/0/0/1869/">Сталинградская битва</a> (17.07.1942 - 02.02.1943). Целью боевых действий советских войск являлись оборона  Сталинграда и разгром действовавшей на сталинградском направлении группировки противника. Победа советских войск в Сталинградской битве имела решающее значение для победы Советского Союза в Великой Отечественной войне.</i>
</p>
TXT;

// Функция обрезки текста до указанного количества слов
function truncateHtml($html, $maxWords) {
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new DOMXPath($dom);
    $body = $dom->getElementsByTagName('body')->item(0);

    $wordCount = 0;

    // Рекурсивная обрезка текста до указанного количества слов
    $truncateNode = function ($node) use (&$truncateNode, &$wordCount, $maxWords) {
        if ($wordCount >= $maxWords) {
            return false;
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $words = preg_split('/\s+/u', $node->nodeValue, -1, PREG_SPLIT_NO_EMPTY);
            if ($wordCount + count($words) > $maxWords) {
                $node->nodeValue = implode(' ', array_slice($words, 0, $maxWords - $wordCount)) . '...';
                $wordCount = $maxWords;
            } else {
                $wordCount += count($words);
            }
        } elseif ($node->nodeType === XML_ELEMENT_NODE) {
            foreach (iterator_to_array($node->childNodes) as $child) {
                if (!$truncateNode($child)) {
                    while ($child->nextSibling) {
                        $node->removeChild($child->nextSibling);
                    }
                    return false;
                }
            }
        }

        return true;
    };

    $truncateNode($body);

    // Возвращаем обрезанный HTML
    return $dom->saveHTML($body);
}

// Обрезаем текст до 29 слов
$truncatedText = truncateHtml($text, 29);

echo $truncatedText;
