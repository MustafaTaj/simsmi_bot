<?php
/**
 * @author Mohamed Ali Musa - (XC0d3rZ); 
 * @since  2015-10-11
 * @version  1.1
 * @project noor bot 1.2v
 * @modified 00-00-00
 * @copyright 2013 - 2015 IT-Cyp3rs
 * @about Noor is web bot fetching  answers for any question 
 *
 **/
class Noor
{
    var $answer;
    var $question;
    // Send request via cURL
    protected function cURL($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $_return = curl_exec($curl);
        curl_close($curl);
        return $_return;
    }
    // Fetching  Tags
    protected function result($Data)
    {
        // Data
        $answer = $Data;
        $preg = array();
        $preg['confidence'] = '%<span class="confidence_num">(.*?)</span>%m';
        $preg['answer'] = '/<div class="answer_text">(.*?)<\/div>/s';
        $preg['title'] = '%<title>(.*?)</title>%m';
        // Get result
        $looking = preg_match($preg['answer'], $answer, $result);
        // IF we have result .
        if ($looking) {
            preg_match($preg['title'], $answer, $title);
            preg_match($preg['confidence'], $answer, $confidence);
            // Replace Unused worlds .
            $result[0] = str_ireplace("(see the related links below)", '', $result[0]);
            // Save result as array .
            $_array = array(
                'answer' => htmlspecialchars(strip_tags($result[0])),
                'confidence' => $confidence[1] . "%",
                'title' => $title[1]);
            // return result .
            $_return = $_array;
        } else {
            $_return = null;
        }
        return $_return;
    }
    protected function MuiltfetchAnswer($url)
    {
        $Query = $this->cURL($url);
        preg_match_all('/<a href=" (.*?)" class="display_link" rel="nofollow">/s', $Query,
            $urls);
        if (is_array($urls)) {
            $n = count($urls[1]);
            var_dump($url[1]);
            for ($i = 0; $i < $n; $i++) {
                $Get = $this->cURL($urls[1][$i]);
                $Sent = $this->result($Get);
                if ($Sent['answer']):
                    $_return[] = $Sent;
                endif;
            }
        } else {
            $_return = "i can't find answers for you'r question :(";
        }
        return $_return;
    }
    protected function fetchAnswer($qu)
    {
        if (!empty($qu)) {
            $url = 'http://wiki.answers.com/search?q=' . rawurlencode($qu);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $a = curl_exec($ch);
            curl_close($ch);
            $headers = explode("\n", $a);
            $redir = $url;
            $j = count($headers);
            for ($i = 0; $i < $j; $i++) {
                if (strpos($headers[$i], "Location:") !== false) {
                    $redir = trim(str_replace("Location:", "", $headers[$i]));
                    break;
                }
            } 
            $answer = file_get_contents($url) ; //$this->cURL($url);
            $result = $this->result($answer);
            if ($result['answer']) {

                $_return = $result;
            } else
                $_return = $this->MuiltfetchAnswer($url);
        } else
            $_return = "please enter your question";
        return $_return;
    }
    function Get($q)
    {
        $this->question = $q;
        $this->answer = $this->fetchAnswer($this->question);
        return $this->answer;
    }
}
?>
