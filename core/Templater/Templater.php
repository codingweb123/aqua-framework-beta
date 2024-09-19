<?php
namespace Templater;
class Templater {
    public function log($message, $type = "info"): bool|null
    {
        [$logPath, $msg] = [get_root_path()."logs".DIRECTORY_SEPARATOR."core.log", "[".strtoupper($type)."] $message\n"];
        if (is_writable($logPath))
        {
            if (!$handle = fopen($logPath, "a+")) return false;
            if (fwrite($handle, $msg) === FALSE) return false;
            fclose($handle);
        }
    }
    public static function compile(): bool
    {
        $templatesViewsDir = dirForScan("resources","views");
        $templateCompiledDir = dirForScan("resources","compiled");
        foreach ($templatesViewsDir[1] as $template)
        {
            $compiledHash = md5($template).".aq.php";
            $handle = file($templatesViewsDir[0].$template);
            $handle2 = fopen($templateCompiledDir[0].$compiledHash, "w+");
            if (!$handle) return false;
            self::syntaxToCode($handle, $handle2, $template);
        }
        return true;
    }
    private static function syntaxToCode($handle, $handle2, $fileName): void
    {
        $fileName = str_replace(".aq.php", "", $fileName);
        $slotFileName = str_replace(DIRECTORY_SEPARATOR, ".", $fileName);
        $oldText = implode($handle);
        $oldText = self::phpMatch($oldText);
        $oldText = self::varsMatch($oldText);
        $oldText = self::slotContentMatch($oldText, $slotFileName);
        $oldText = self::expandedSlotContentMatch($oldText, $slotFileName);
        $oldText = self::componentMatch($oldText);
        $oldText = self::componentWithoutSlotMatch($oldText);
        fwrite($handle2, $oldText);
        fclose($handle2);
    }
    private static function phpMatch($oldText)
    {
        preg_match_all('/@php\s*?(.*?)@endphp/s', $oldText, $phpMatches);
        if (isset($phpMatches[1]))
            foreach ($phpMatches[1] as $key => $match) {
                $oldText = str_replace($phpMatches[0][$key], "<?php $match ?>", $oldText);
            }
        return $oldText;
    }
    private static function varsMatch($oldText)
    {
        preg_match_all('/{{\s*(\S.*?)\s*}}/s', $oldText, $linesMatches);
        if (isset($linesMatches[1]))
            foreach ($linesMatches[1] as $key => $match) {
                $var = $linesMatches[1][$key];
                $oldText = str_replace($linesMatches[0][$key], "<?= $var ?>", $oldText);
            }
        return $oldText;
    }
    private static function slotContentMatch($oldText, $slotFileName)
    {
        preg_match_all('/<x-slot(.*?)\/>/s', $oldText, $slotMatches);
        if (isset($slotMatches[1]))
            foreach ($slotMatches[1] as $key => $match) {
                $oldText = str_replace("<x-slot".$slotMatches[1][$key]."/>", "<?= decodeVulnerablesWithHTML(PaperWork::server()['AQUA_TOOLS']['slot']['$slotFileName'] ?? ''); ?>", $oldText);
            }
        return $oldText;
    }
    private static function expandedSlotContentMatch($oldText, $slotFileName)
    {
        preg_match_all('/<x-slot>(.*?)<\/x-slot>/s', $oldText, $slotMatches);
        if (isset($slotMatches[1]))
            foreach ($slotMatches[1] as $key => $match) {
                $oldText = str_replace("<x-slot>".$slotMatches[1][$key]."</x-slot>", "<?= decodeVulnerablesWithHTML(PaperWork::server()['AQUA_TOOLS']['slot']['$slotFileName'] ?? '".trim($slotMatches[1][$key])."'); ?>", $oldText);
            }
        return $oldText;
    }
    private static function componentMatch($oldText)
    {
        preg_match_all('/<x-component ?(\S*="[^"]+")*>(.*)<\/x-component>/s', $oldText, $componentMatches);
        if (isset($componentMatches[1]))
            foreach ($componentMatches[1] as $key => $match) {
                $componentName = str_replace('"', "", explode('="', $componentMatches[1][$key])[1]);
                $componentSlot = $componentMatches[2][$key];
                $oldText = str_replace($componentMatches[0][$key], "<?php ob_start(); ?> $componentSlot <?php \$_SERVER['AQUA_TOOLS']['slot']['$componentName'] = ob_get_contents(); ob_end_clean(); ?>\n<?php if (getSlot(PaperWork::server()['AQUA_TOOLS'], '$componentName')) view('$componentName', ['config' => \$config, 'slot' => ['$componentName', PaperWork::server()['AQUA_TOOLS']['slot']['$componentName']]]); else view('$componentName', ['config' => \$config]); ?>", $oldText);
                if (trim($componentSlot) != "") $oldText = self::componentMatch($oldText);
            }
        return $oldText;
    }
    private static function componentWithoutSlotMatch($oldText)
    {
        preg_match_all('/<x-component ?(\S*="[^"]+")* ?\/>/s', $oldText, $componentMatches);
        if (isset($componentMatches[1]))
            foreach ($componentMatches[1] as $key => $match) {
                $componentName = str_replace('"', "", explode('="', $componentMatches[1][$key])[1]);
                $oldText = str_replace($componentMatches[0][$key], "<?php view('$componentName', ['config' => \$config]); ?>", $oldText);
            }
        return $oldText;
    }
}