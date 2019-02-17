<?php

/**
 * Генератор бредового текста
 */

namespace Ravepackage\Rave;

class Rave
{
    protected $map;

    /**
     * @param $file     file        текстовый файл
     */
    public function init($file)
    {
        $text = file_get_contents($file);

//        $text = "Hello, World!!!";

        if (isset($text) && !empty($text)) {
            preg_match_all("/[a-zA-Zа-яА-Я]+|[.,!?]/u", $text, $words);
            $this->setMap($words);
        }
    }

    /**
     * Инициализирует карту словарных переходов
     * @param $wordsArray       array       Массив слов из обрабатываемого текстового файла
     */
    protected function setMap($wordsArray)
    {
        $words = $wordsArray[0];
        $count = count($words);

        $this->map['#START'][] = $words[0];

        for ($i = 1; $i < $count; $i++) {
            $this->map[$words[$i - 1]][] = $words[$i];
        }
    }

    /**
     * Генерирует фразу на основе карты слов
     * @return string
     */
    public function getText()
    {
        $word = $this->map['#START'][0];
        $phrase = [];
        do {
            $index = array_rand($this->map[$word]);
            $word = $this->map[$word][$index];

            if (count($phrase) > 0 && !in_array($word, ['.', '!', '?'])) {
                $phrase[] = ' ' . $word;
            } else {
                $phrase[] = $word;
            }

        } while (!in_array($word, ['.', '!', '?']));
        $phrase[0] = ucfirst($phrase[0]);
        return implode('', $phrase);
    }
}