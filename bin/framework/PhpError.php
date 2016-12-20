<?php
namespace Framework;

class PhpError
{
    /**
     * 定義されている エラーレベル定数(E_USER_***) のリストを返す。
     * @return array name=>errorLevel のhash
     */
    static public function buildErrorLevels()
    {
        $levels = array_fill_keys(array_filter(array_keys(get_defined_constants()),
            function ($name) {
                return (strncmp('E_USER_', $name, 7) === 0);
            }
        ), array());
        array_walk($levels, function (&$item, $key) {
            if (defined($key)) {
                $item['name'] = $key;
                $item['value'] = constant($key);
            }
        });
        usort($levels, function ($item1, $item2) {
            return ($item1['value'] > $item2['value']) ? +1 : -1;
        });
        return $levels;
    }

    /**
     * スタックトレースの配列をエラー表示用に整形して返す。
     * パスワードのようなデータも出力されるので、本番環境では $dumpArgs は true にしないこと。
     * @param array $arrTrace
     * @param bool $dumpArgs (optional) 引数を出力するか否か。
     * @return array 整形済み
     */
    public static function formatTrace($arrTrace, $dumpArgs = false)
    {
        $stack = array();
        foreach ($arrTrace as $i => $t) {
            // 引数は型が分かるよう文字列に整形
            $args = '';
            if ($dumpArgs) {
                if (isset($t['args']) && !empty($t['args'])) {
                    // 配列は一階層目のみ回す
                    $args = implode(', ', array_map(function ($arg) {
                        if (is_array($arg)) {
                            $vars = array();
                            foreach ($arg as $key => $var) {
                                $vars[] = sprintf('%s=>%s',
                                    self::formatVar($key), self::formatVar($var));
                            }
                            return sprintf('Array[%s]', implode(', ', $vars));
                        }
                        return self::formatVar($arg);
                    }, $t['args']));
                }
            }
            $stack[] = sprintf('#%d %s(%d): %s%s%s(%s)',
                $i,
                (isset($t['file'])) ? $t['file'] : '', // ファイル
                (isset($t['line'])) ? $t['line'] : '', // 行番号
                (isset($t['class'])) ? $t['class'] : '', // クラス名
                (isset($t['type'])) ? $t['type'] : '', // コール方式(->, ::)
                (isset($t['function'])) ? $t['function'] : '', // 関数名、メソッド名
                $args);
        }
        return $stack;
    }

    /**
     * @param array $arrTrace
     * @param bool $dumpArgs
     * @return string
     */
    static public function formatTraceAsString($arrTrace, $dumpArgs = false)
    {
        $arrTrace = self::formatTrace($arrTrace, $dumpArgs);
        return implode("\n", $arrTrace);
    }

    /**
     * 変数を文字列表現にして、型情報を加えて返します。
     * @param mixed
     * @return string
     */
    public static function formatVar($var)
    {
        if (is_null($var)) {
            return 'NULL';
        }
        if (is_int($var)) {
            return sprintf('Int(%d)', $var);
        }
        if (is_float($var)) {
            return sprintf('Float(%F)', $var);
        }
        if (is_string($var)) {
            return sprintf('"%s"', $var);
        }
        if (is_bool($var)) {
            return sprintf('Bool(%s)', $var ? 'true' : 'false');
        }
        if (is_array($var)) {
            return 'Array';
        }
        if (is_object($var)) {
            return sprintf('Object(%s)', get_class($var), $var);
        }
        return sprintf('%s', gettype($var));
    }

}